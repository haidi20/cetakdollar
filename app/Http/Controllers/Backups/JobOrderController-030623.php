<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\JobOrderAssessment;
use App\Models\JobOrderHasEmployee;
use App\Models\JobOrderHasEmployeeHistory;
use App\Models\JobOrderHasStatus;
use App\Models\JobOrderHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;


class JobOrderController extends Controller
{
    private $nameModel = "App\Models\JobOrder";
    private $nameModelJobOrderEmployee = "App\Models\JobOrderHasEmployee";

    public function index()
    {
        $vue = true;
        $baseUrl = Url::to('/');
        $user = auth()->user();

        return view("pages.job-order.index", compact("vue", "user", "baseUrl"));
    }

    public function fetchData()
    {
        $user = User::find(request("user_id"));
        $month = Carbon::parse(request("month"));

        $jobOrders = JobOrder::with(["jobOrderHasEmployees", "jobOrderAssessments"])
            ->whereYear("datetime_start", $month->format("Y"))
            ->whereMonth("datetime_start", $month->format("m"))
            ->orderBy("created_at", "desc");

        // jika pengawas, secara default menampilkan datanya berdasarkan dia yang buat
        // terkecuali di filter data pilih dari 'pengawas lain' baru muncul job order dari pengawas lain
        if ($user->group_name == "Pengawas") {
            if (request("type_by") == "creator") {
                $jobOrders = $jobOrders->where("created_by", $user->id);
            } else {
                $jobOrders = $jobOrders->where("created_by", "!=", $user->id);
            }
        } else if ($user->group_name == "Quality Control") {
            $jobOrders = $jobOrders->where("created_by", "!=", $user->id);
        }

        $jobOrders = $jobOrders->get();

        return response()->json([
            "jobOrders" => $jobOrders,
        ]);
    }

    public function store()
    {
        // return request()->all();
        $jobStatusController = new JobStatusController;

        if (count(request("employee_selecteds")) == 0) {
            return response()->json([
                'success' => false,
                'message' => "Maaf, harus pilih karyawan terlebih dahulu",
            ], 500);
        }

        try {
            DB::beginTransaction();

            if (request("id")) {
                $jobOrder = JobOrder::find(request("id"));

                $storeType = "update";
                $message = "diperbaharui";
            } else {
                $jobOrder = new JobOrder;

                $storeType = "store";
                $message = "ditambahkan";
                $jobOrder->status = "active";
            }

            $date = Carbon::now();
            $date->setTimeFromTimeString(request("hour_start"));
            // $date = Carbon::createFromFormat("h:m", request("hour_start"))->format("Y-m-d h:m");

            $jobOrder->project_id = request("project_id");
            $jobOrder->job_id = request("job_id");
            $jobOrder->job_level = request("job_level");
            $jobOrder->job_note = request("job_note");
            //datetime_end inputnya di storeAction
            $jobOrder->datetime_start = $date;
            $jobOrder->datetime_estimation_end = Carbon::parse(request("datetime_estimation_end"));
            $jobOrder->estimation = request("estimation");
            $jobOrder->time_type = request("time_type");
            $jobOrder->category = request("category");
            $jobOrder->note = request("note");
            $jobOrder->save();

            // tambah data jobOrderHasStatus hanya ketika data baru
            if (request("id") == null) {
                $jobStatusController->storeJobStatusHasParent($jobOrder, null, $date, $this->nameModel);
            }

            $this->storeJobOrderHasEmployee($jobOrder, $jobOrder->status, $date, null, $storeType);
            $this->storeJobOrderHistory($jobOrder);

            DB::commit();

            return response()->json([
                'success' => true,
                'requests' => request()->all(),
                'date' => $date,
                'message' => "Berhasil {$message}",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => "Gagal {$message}",
            ], 500);
        }
    }

    public function storeAction()
    {
        // return request()->all();
        $jobStatusController = new JobStatusController;

        try {
            DB::beginTransaction();

            $dateEnd = null;
            $date = Carbon::parse(request("date") . ' ' . request("hour"))->format("Y-m-d H:i");
            $message = "diperbaharui";

            $jobOrder = JobOrder::find(request("id"));

            if (request("status") == 'finish') {
                $jobOrder->status = request("status");
                $jobOrder->datetime_end = $date;

                $dateEnd = $date;
            }

            $jobOrder->status = request("status");
            $jobOrder->status_note = request("status_note");
            $jobOrder->save();

            $jobStatusController->storeJobStatusHasParent($jobOrder, request("status_last"), $date, $this->nameModel);
            $this->storeJobOrderHistory($jobOrder);
            $this->storeJobOrderHasEmployee($jobOrder, $jobOrder->status, $date, $dateEnd, "storeAction");

            DB::commit();

            return response()->json([
                'success' => true,
                'request' => request()->all(),
                'jobOrder' => $jobOrder,
                'message' => "Berhasil {$message}",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => "Gagal {$message}",
            ], 500);
        }
    }

    public function storeActionAssessment()
    {
        // return request()->all();
        $jobOrderId = request("id");
        $jobStatusController = new JobStatusController;

        try {
            DB::beginTransaction();

            $jobOrderAssessment = JobOrderAssessment::updateOrCreate([
                "job_order_id" => $jobOrderId,
                "employee_id" => request("user_id"),
            ], [
                "note" => request("status_note"),
            ]);

            $allJobOrderAssessmentHasEmployee = JobOrderAssessment::where([
                "job_order_id" => $jobOrderId,
            ]);

            if ($allJobOrderAssessmentHasEmployee->count() >= 2) {
                $date = Carbon::parse(request("date") . ' ' . request("hour"))->format("Y-m-d H:i");

                $jobOrder = JobOrder::find($jobOrderId);
                $jobOrder->status = "finish";
                $jobOrder->datetime_end = $date;
                $jobOrder->save();

                $jobStatusController->storeJobStatusHasParent($jobOrder, "active", $date, $this->nameModel);
                // $this->storeJobOrderHasEmployee($jobOrder, $jobOrder->status, $jobOrder->datetime_start, $jobOrder->datetime_end);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'request' => request()->all(),
                'message' => "Berhasil Penilaian",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => "Gagal Penilaian",
            ], 500);
        }
    }

    public function storeActionJobOrderHasEmployee()
    {
        return request()->all();
        $jobOrderId = request("id");
        $jobStatusController = new JobStatusController;
    }

    public function destroy()
    {
        try {
            DB::beginTransaction();

            $jobOrder = JobOrder::find(request("id"));
            $jobOrder->update([
                'deleted_by' => request("user_id"),
            ]);
            $this->storeJobOrderHistory($jobOrder, true);
            $jobOrder->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal dihapus',
            ], 500);
        }
    }

    private function storeJobOrderHasEmployee($jobOrder, $status, $dateStart, $dateEnd = null, $storeType)
    {
        $jobStatusController = new JobStatusController;

        // $jobOrderHasEmployeeDelete = JobOrderHasEmployee::where([
        //     "job_order_id" => $jobOrder->id,
        // ]);
        // $jobOrderHasEmployeeDelete->delete();

        if ($dateEnd != null) {
            $date = $dateEnd;
        } else {
            $date = $dateStart;
        }

        if (count(request("employee_selecteds")) > 0) {

            if ($status == "correction") {
                $status = "active";
            }

            foreach (request("employee_selecteds") as $index => $item) {
                // $jobOrderHasEmployee = JobOrderHasEmployee::create([
                //     "job_order_id" => $jobOrder->id,
                //     "employee_id" => $item["employee_id"],
                //     "status" => $status,
                //     "datetime_start" => $dateStart,
                // ]);

                if (isset($item["id"])) {
                    $jobOrderHasEmployee = JobOrderHasEmployee::find($item["id"]);
                } else {
                    $jobOrderHasEmployee = new JobOrderHasEmployee;
                }

                if ($dateEnd != null) {
                    $jobOrderHasEmployee->datetime_end = $dateEnd;
                } else {
                    $jobOrderHasEmployee->datetime_start = $dateStart;
                }

                $jobOrderHasEmployee->job_order_id = $jobOrder->id;
                $jobOrderHasEmployee->employee_id = $item["employee_id"];
                $jobOrderHasEmployee->status = $status;
                $jobOrderHasEmployee->save();

                if ($storeType == 'storeAction') {
                    $jobStatusController->storeJobStatusHasParent($jobOrderHasEmployee, null, $date, $this->nameModelJobOrderEmployee);
                }

                $this->storeJobOrderHasEmployeeHistory($jobOrderHasEmployee);
            }
        }
    }

    private function storeJobOrderHistory($jobOrder, $isDelete = false)
    {
        $jobOrderHistory = new JobOrderHistory;
        $jobOrderHistory->job_order_id = $jobOrder->id;
        $jobOrderHistory->project_id = $jobOrder->project_id;
        $jobOrderHistory->job_id = $jobOrder->job_id;
        $jobOrderHistory->job_level = $jobOrder->job_level;
        $jobOrderHistory->job_note = $jobOrder->job_note;
        $jobOrderHistory->status = $jobOrder->status;
        $jobOrderHistory->datetime_start = $jobOrder->datetime_start;
        $jobOrderHistory->datetime_end = $jobOrder->datetime_end;
        $jobOrderHistory->datetime_estimation_end = $jobOrder->datetime_estimation_end;
        $jobOrderHistory->estimation = $jobOrder->estimation;
        $jobOrderHistory->time_type = $jobOrder->time_type;
        $jobOrderHistory->category = $jobOrder->category;
        $jobOrderHistory->note = $jobOrder->note;
        $jobOrderHistory->status_note = $jobOrder->status_note;
        $jobOrderHistory->deleted_by = $jobOrder->deleted_by;

        if ($isDelete) {
            $jobOrderHistory->deleted_at = Carbon::now();
        }

        $jobOrderHistory->save();
    }

    private function storeJobOrderHasEmployeeHistory($jobOrderHasEmployee)
    {
        $jobOrderHasEmployeeHistory = new JobOrderHasEmployeeHistory;
        $jobOrderHasEmployeeHistory->job_order_has_employee_id = $jobOrderHasEmployee->id;
        $jobOrderHasEmployeeHistory->employee_id = $jobOrderHasEmployee->employee_id;
        $jobOrderHasEmployeeHistory->job_order_id = $jobOrderHasEmployee->job_order_id;
        $jobOrderHasEmployeeHistory->status = $jobOrderHasEmployee->status;
        $jobOrderHasEmployeeHistory->datetime_start = $jobOrderHasEmployee->datetime_start;
        $jobOrderHasEmployeeHistory->datetime_end = $jobOrderHasEmployee->datetime_end;
        $jobOrderHasEmployeeHistory->save();
    }
}
