<?php

namespace App\Http\Controllers;

use App\Models\JobStatusHasParent;
use App\Models\JobStatusHasParentHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class JobStatusController extends Controller
{
    public function storeJobStatusHasParent($parent, $statusLast = null, $date, $nameModel)
    {
        // $statusLast = request("status_last");
        $parentNote = null;
        if (isset($parent["status_note"])) {
            $parentNote = $parent["status_note"];
        }

        if ($statusLast != null) {

            $jobStatusHasParent = JobStatusHasParent::where([
                "status" => $statusLast,
                "parent_id" => $parent->id,
                "parent_model" => $nameModel,
            ])->orderBy("created_at", "desc")->first();

            // if ($jobStatusHasParent) {
            $jobStatusHasParent->update([
                "note_end" => $parentNote,
                "datetime_end" => $date,
            ]);

            $getValidationDatetime = $this->getValidationDatetime($jobStatusHasParent->datetime_start, $jobStatusHasParent->datetime_end);

            if ($getValidationDatetime) {
                return (object) [
                    'error' => true,
                    'message' => "Maaf, waktu selesai tidak boleh kurang dari waktu mulai",
                ];
            }

            $this->storeJobStatusHasParentHistory($jobStatusHasParent, false);
            // }
        } else {
            $jobStatusHasParent = new JobStatusHasParent;
            $jobStatusHasParent->parent_id = $parent->id;
            $jobStatusHasParent->parent_model = $nameModel;
            $jobStatusHasParent->job_order_id = $parent->id;
            $jobStatusHasParent->status = $parent->status;
            $jobStatusHasParent->datetime_start = $date;
            $jobStatusHasParent->note_start = $parentNote;

            if ($nameModel == "App\Models\JobOrderHasEmployee") {
                $jobStatusHasParent->job_order_id = $parent->job_order_id;
                $jobStatusHasParent->employee_id = $parent->employee_id;
            }

            $jobStatusHasParent->save();

            $this->storeJobStatusHasParentHistory($jobStatusHasParent, false);
        }
    }

    public function storeOvertimeRevision()
    {
        $datetimeStart = Carbon::parse(request("datetime_start"));
        $datetimeEnd = Carbon::parse(request("datetime_end"));

        if ($datetimeStart->greaterThan($datetimeEnd)) {
            return response()->json([
                'success' => false,
                'message' => "Maaf, Waktu mulai lembur lebih besar dari waktu selesai lembur",
            ], 500);
        }

        try {
            DB::beginTransaction();

            $jobStatusHasParent = JobStatusHasParent::find(request("id"));
            $jobStatusHasParent->datetime_start = request("datetime_start");
            $jobStatusHasParent->datetime_end = request("datetime_end");
            $jobStatusHasParent->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Perbaharui Data'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return response()->json([
                'success' => false,
                'message' => 'Maaf, gagal Perbaharui data'
            ], 500);
        }
    }

    public function updateJobStatusHasParent($parent, $nameModel)
    {
        $jobStatusHasParent = JobStatusHasParent::where([
            "parent_id" => $parent->id,
            "parent_model" => $nameModel,
            "status" => $parent->status,
            "datetime_end" => null
        ])->orderBy("created_at", "desc")->first();

        $jobStatusHasParent->datetime_start = $parent->datetime_start;
        $jobStatusHasParent->note_start = $parent->note_start;
        $jobStatusHasParent->save();
    }

    public function destroyJobStatusHasParent($jobOrder, $nameModel)
    {
        $jobStatusHasParent = JobStatusHasParent::where([
            "parent_id" => $jobOrder->id,
            "parent_model" => $nameModel,
        ]);
        $jobStatusHasParent->update([
            'deleted_by' => request("user_id"),
        ]);

        foreach ($jobStatusHasParent->get() as $index => $item) {
            $getData = JobStatusHasParent::find($item->id);

            $this->storeJobStatusHasParentHistory($getData, true);
        }

        $jobStatusHasParent->delete();
    }

    private function storeJobStatusHasParentHistory($jobStatusHasParent, $isDelete = false)
    {
        $jobStatusHasParentHistory = new JobStatusHasParentHistory;
        $jobStatusHasParentHistory->job_status_has_parent_id = $jobStatusHasParent->id;
        $jobStatusHasParentHistory->parent_id = $jobStatusHasParent->parent_id;
        $jobStatusHasParentHistory->parent_model = $jobStatusHasParent->parent_model;
        $jobStatusHasParentHistory->status = $jobStatusHasParent->status;
        $jobStatusHasParentHistory->datetime_start = $jobStatusHasParent->datetime_start;
        $jobStatusHasParentHistory->datetime_end = $jobStatusHasParent->datetime_end;
        $jobStatusHasParentHistory->note_start = $jobStatusHasParent->note_start;
        $jobStatusHasParentHistory->note_end = $jobStatusHasParent->note_end;
        $jobStatusHasParentHistory->deleted_by = $jobStatusHasParent->deleted_by;

        if ($isDelete) {
            $jobStatusHasParentHistory->deleted_at = Carbon::now();
        }

        $jobStatusHasParentHistory->save();
    }

    private function getValidationDatetime($start, $end)
    {
        if ($end < $start) {
            return true;
        }

        return false;
    }
}
