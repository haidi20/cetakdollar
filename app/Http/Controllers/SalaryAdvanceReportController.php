<?php

namespace App\Http\Controllers;

use App\Exports\SalaryAdvanceExport;
use App\Models\SalaryAdvance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

class SalaryAdvanceReportController extends Controller
{
    private $nameModel = "App\\Models\\SalaryAdvance";

    public function index()
    {
        $vue = true;
        $baseUrl = Url::to('/');
        $user = auth()->user();

        return view("pages.salary-advance-report.index", compact("vue", "user", "baseUrl"));
    }

    public function fetchData($reqStatus = null)
    {
        $dateStart = Carbon::parse(request("date_start"));
        $dateEnd = Carbon::parse(request("date_end"));
        $nameModel = $this->nameModel;
        $userId = (int) request("user_id");
        $status = request("status");

        // is_just_by_status = agar bisa lihat data keseluruhan berdasarkan status accept, reject, atau waiting
        // *note lihat referensi di library.php bagian status

        // $isJustByStatus = (bool) request("is_just_by_status", false);
        // $isByUser = $isJustByStatus ? false : true;
        $roleId = User::find($userId)->role_id;
        // $isByUser = $roleId == 1 || $roleId == 2 ? false : true;
        $isByUser = $roleId == 1 ? false : true;

        try {
            $approvalAgreement = new ApprovalAgreementController;

            $salaryAdvances = SalaryAdvance::whereDate("created_at", ">=", $dateStart->format("Y-m-d"))
                ->whereDate("created_at", "<=", $dateEnd->format("Y-m-d"));

            $salaryAdvances = $approvalAgreement->whereByApproval(
                $salaryAdvances,
                $userId,
                $nameModel,
                $dateStart,
                $dateEnd,
                $isByUser,
            );

            $salaryAdvances = $salaryAdvances->orderBy("created_at", "desc")->get();

            $salaryAdvances = $salaryAdvances->map(function ($query) {
                $salaryAdvanceLasts = SalaryAdvance::where("employee_id", $query->employee_id)
                    ->where("created_at", "<", $query->created_at);
                $checkLastData = $salaryAdvanceLasts->count();


                if ($checkLastData > 0) {
                    $totalRemainingDebt = 0;
                    foreach ($salaryAdvanceLasts->get() as $index => $item) {
                        // $totalRemainingDebt += $item->remaining_debt;
                        $totalRemainingDebt = $item->remaining_debt;
                    }

                    $remainingDebt = $totalRemainingDebt;
                    // $remainingDebt = "Rp. " . number_format($remainingDebt, 0, ',', '.');
                } else {
                    $remainingDebt = "Rp. 0";
                }

                $query["remaining_debt_readable"] = $remainingDebt;

                return $query;
            });

            $salaryAdvances = $approvalAgreement->mapApprovalAgreement(
                $salaryAdvances,
                $this->nameModel,
                $userId,
                $isByUser
            );

            if ($status != "all") {
                $salaryAdvances = $salaryAdvances->where("approval_status", $status);
            }

            return response()->json([
                "salaryAdvances" => $salaryAdvances,
                "request" => request()->all(),
                "userId" => $userId,
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);

            return response()->json([
                'success' => false,
                'message' => "Gagal dapatkan data",
            ], 500);
        }
    }

    public function export()
    {
        $data = $this->fetchData()->original["salaryAdvances"];
        $dateStart = Carbon::parse(request("date_start"));
        $dateEnd = Carbon::parse(request("date_end"));
        $dateStartReadable = $dateStart->isoFormat("dddd, D MMMM YYYY");
        $dateEndReadable = $dateEnd->isoFormat("dddd, D MMMM YYYY");
        $nameFile = "export/kasbon_{$dateStartReadable}-{$dateEndReadable}.xlsx";

        try {
            $path = public_path($nameFile);

            if ($path) {
                @unlink($path);
            }

            Excel::store(new SalaryAdvanceExport($data), $nameFile, 'real_public', \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                "success" => true,
                "request" => request()->all(),
                "data" => $data,
                "linkDownload" => route('roster.download', ["path" => $nameFile]),
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);

            return response()->json([
                'success' => false,
                'message' => 'Gagal export data',
            ], 500);
        }
    }

    public function download()
    {
        $path = public_path(request("path"));

        return Response::download($path);
    }

    // LAPORAN KASBON
    public function indexOld()
    {
        // selanjutnya pindah ke fetchData dapatkan datanya.
        $salaryAdvances = [
            (object)[
                "id" => 1,
                "employee_name" => "Muhammad Adi",
                "amount" => "5.000.000",
                "monthly_deduction" => "1.000.000",
                "duration" => "5 bulan",
                "net_salary" => "4.000.000",
                "remaining_debt" => "1.200.000",
                "date" => "Jum'at, 5 Mei 2023",
                "status" => "accept",
                "reason" => "kebutuhan beli handphone baru",
            ],
        ];

        return view("pages.salary-advance-report.index", compact("salaryAdvances"));
    }
}
