<?php

namespace App\Http\Controllers;

use App\Models\ApprovalLevel;
use App\Models\ApprovalLevelDetail;
use App\Models\SalaryAdvance;
use App\Models\SalaryAdvanceDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class SalaryAdvanceController extends Controller
{

    /* KASBON
        Salary advance adalah istilah yang digunakan untuk merujuk pada uang yang diberikan kepada karyawan sebelum tanggal gajian mereka,
        yang nantinya akan dikurangi dari gaji mereka pada tanggal gajian selanjutnya.
    */

    private $nameModel = "App\\Models\\SalaryAdvance";

    public function index()
    {
        $vue = true;
        $baseUrl = Url::to('/');
        $user = auth()->user();

        return view("pages.salary-advance.index", compact("vue", "user", "baseUrl"));
    }

    public function fetchData()
    {
        $userId = request("user_id");
        $search = request("search");
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");
        $approvalAgreement = new ApprovalAgreementController;

        $salaryAdvances = new SalaryAdvance;

        if (request("is_filter_month") == "true") {
            $salaryAdvances = $salaryAdvances->whereYear("created_at", $month->format("Y"))
                ->whereMonth("created_at", $month->format("m"));
        }

        if ($search != null) {
            $salaryAdvances = $salaryAdvances->where(function ($query) use ($search) {
                $query->whereHas("employee", function ($employeeQuery) use ($search) {
                    $employeeQuery->where("name", "like", "%" . $search . "%")
                        ->orWhereHas("position", function ($positionQuery) use ($search) {
                            $positionQuery->where("name", "like", "%" . $search . "%");
                        });
                });
            })->orWhere("reason", "like", "%" . $search . "%")
                ->orWhere("loan_amount", "like", "%" . $search . "%");
        }

        $salaryAdvances = $salaryAdvances->orderBy("created_at", "desc")->get();
        $salaryAdvances = $approvalAgreement->mapApprovalAgreement($salaryAdvances, $this->nameModel, $userId,  true);

        if (request("type") != "all") {
            $salaryAdvances = $salaryAdvances->where("approval_status", request("type"));
        }

        return response()->json([
            "salaryAdvances" => $salaryAdvances,
            "is_filter_month" => request("is_filter_month"),
        ]);
    }

    // ketika pengawas input data kasbon
    public function store(Request $request)
    {
        // return request()->all();
        $approvalLevel = ApprovalLevel::where("name", "Kasbon")->first();
        $userId = request("user_id");

        // return request()->all();

        try {
            DB::beginTransaction();

            if (request("id")) {
                $salaryAdvance = SalaryAdvance::find(request("id"));

                $message = "diperbaharui";
            } else {
                $salaryAdvance = new SalaryAdvance;

                $message = "ditambahkan";
            }

            $salaryAdvance->employee_id = request("employee_id");
            $salaryAdvance->loan_amount = request("loan_amount");
            $salaryAdvance->approval_level_id = $approvalLevel->id;
            $salaryAdvance->reason = request("reason");
            $salaryAdvance->save();

            if (request("id") == null) {
                $this->insertApprovalLevel($salaryAdvance, $userId, null, null);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil {$message}",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);

            return response()->json([
                'success' => false,
                'message' => "Gagal {$message}",
            ], 500);
        }
    }

    // proses persetujuan biasa
    // role : direktur dan kasir
    public function storeApproval()
    {
        $note = request("note");
        $userId = request("user_id");
        $duration = request("duration");
        $loanAmount = request("loan_amount");
        $paymentMethod = request("payment_method");
        $approvalStatus = request("approval_status");
        $monthlyDeduction = request("monthly_deduction");
        $approvalAgreementNote = request("approval_agreement_note");
        $monthLoanComplite = Carbon::now()->addMonths($duration - 1);


        try {
            DB::beginTransaction();

            $salaryAdvance = SalaryAdvance::find(request("id"));
            $message = "melakukan persetujuan";

            if ($approvalStatus == 'reject') {
                $salaryAdvance->status = "reject";
            } else {
                $approval = ApprovalLevel::where("name", "Kasbon")->first();

                $getMaxLevelApproval = ApprovalLevelDetail::where([
                    "approval_level_id" => $approval->id,
                ])->orderBy("level", "desc")->first();

                if ($userId == $getMaxLevelApproval->user_id) {
                    $salaryAdvance->status = "accept";
                }
            }


            if($salaryAdvance->status == "accept"){
                // $this->insertDetailSalaryAdvance();

                $dt = Carbon::now();


                if($dt->format('d') >= 25){
                    $date_start = $dt->addMonth(1)->startOfMonth()->format('Y-m-d');
                    $date_end = Carbon::now()->addMonth(($salaryAdvance->duration + 1))->startOfMonth()->format('Y-m-d');
                }else{
                    $date_start = $dt->format('Y-m-d');
                    $date_end = Carbon::now()->addMonth(($salaryAdvance->duration - 1))->format('Y-m-d');
                }

                // SalaryAdvanceDetail
                $new_salary_advance_detail = SalaryAdvanceDetail::create([
                    'salary_advance_id'=>$salaryAdvance->id,
                    'employee_id'=>$salaryAdvance->employee_id,
                    'date_start'=>$date_start,
                    'date_end'=>$date_end,
                    'amount'=>$salaryAdvance->monthly_deduction,
                ]);

            }

            // $salaryAdvance->note = $note;
            $salaryAdvance->loan_amount =  $loanAmount;
            $salaryAdvance->duration = $this->setFormulaByApprovalStatus($approvalStatus, $duration);
            $salaryAdvance->payment_method = $this->setFormulaByApprovalStatus($approvalStatus, $paymentMethod);
            $salaryAdvance->monthly_deduction = $this->setFormulaByApprovalStatus($approvalStatus, $monthlyDeduction);
            $salaryAdvance->month_loan_complite = $this->setFormulaByApprovalStatus($approvalStatus, $monthLoanComplite);
            $salaryAdvance->save();

            $this->insertApprovalLevel($salaryAdvance, $userId, $approvalStatus, $approvalAgreementNote);

            DB::commit();

            return response()->json([
                'success' => true,
                'requests' => request()->all(),
                'message' => "Berhasil {$message}",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);

            return response()->json([
                'success' => false,
                'message' => "Gagal {$message}",
            ], 500);
        }
    }

    public function destroy()
    {
        try {
            DB::beginTransaction();

            $salaryAdvance = SalaryAdvance::find(request("id"));
            $salaryAdvance->update([
                'deleted_by' => request("user_id"),
            ]);
            $salaryAdvance->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);

            return response()->json([
                'success' => false,
                'message' => 'Gagal dihapus',
            ], 500);
        }
    }

    // proses persetujuan dengan menentukan potongan perbulan
    // role : HRD
    private function storeApprovalSetup()
    {
    }

    private function insertApprovalLevel($salaryAdvance, $userId, $approvalStatus = null, $approvalNote = null)
    {
        $approvalAgreement = new ApprovalAgreementController;
        $approvalLevel = ApprovalLevel::where("name", "Kasbon")->first();

        $requestApprovalAgreement["approval_level_id"] = $approvalLevel->id;
        $requestApprovalAgreement["model_id"] =  $salaryAdvance->id;
        $requestApprovalAgreement["user_id"] =  $userId;
        $requestApprovalAgreement["name_model"] =  $this->nameModel;

        // accept_onbehalf = perwakilan / atas nama
        if ($approvalStatus == "accept_onbehalf") {
            $requestApprovalAgreement["user_behalf_id"] = $userId;
            // $approvalStatus = "accept";
        } else {
            $requestApprovalAgreement["user_behalf_id"] = null;
        }

        $requestApprovalAgreement["status_approval"] = $approvalStatus != null ? $approvalStatus : "review";

        // process insert to approval agreement
        $approvalAgreement->storeApprovalAgreement(
            $requestApprovalAgreement["approval_level_id"],
            $requestApprovalAgreement["model_id"],
            $requestApprovalAgreement["user_id"],
            $requestApprovalAgreement["name_model"],
            $requestApprovalAgreement["status_approval"],
            $requestApprovalAgreement["user_behalf_id"],
            $approvalNote,
        );
    }

    private function setFormulaByApprovalStatus($approvalStatus, $item)
    {
        $result = null;

        if ($approvalStatus == 'accept' || $approvalStatus == 'accept_onbehalf') {
            $result = $item;
        }

        return $result;
    }

    private function fetchDataOld()
    {
        $salaryAdvances = [
            (object)[
                "id" => 1,
                "employee_name" => "Muhammad Adi",
                "amount" => "5.000.000",
                "monthly_deduction" => "1.000.000",
                "duration" => "5 bulan",
                "net_salary" => "4.000.000",
                "date" => "Jum'at, 5 Mei 2023",
            ]
        ];

        return response()->json([
            "salaryAdvances" => $salaryAdvances,
        ]);
    }
}
