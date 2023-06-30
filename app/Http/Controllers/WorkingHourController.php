<?php

namespace App\Http\Controllers;

use App\Models\WorkingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

class WorkingHourController extends Controller
{
    public function index()
    {
        $id = WorkingHour::pluck('id');
        $startTime = WorkingHour::pluck('start_time');
        $afterWork = WorkingHour::pluck('after_work');
        $afterWorkLimit = WorkingHour::pluck('after_work_limit');
        $startRest = WorkingHour::pluck('start_rest');
        $endRest = WorkingHour::pluck('end_rest');
        // $maximumDelay = WorkingHour::pluck('maximum_delay');
        $fastestTime = WorkingHour::pluck('fastest_time');
        $overtimeWork = WorkingHour::pluck('overtime_work');
        $saturdayWorkHour = WorkingHour::pluck('saturday_work_hour');

        return view("pages.master.working-hour.index", compact("id", "startTime", "afterWork", "afterWorkLimit", "startRest", "endRest", "fastestTime", "overtimeWork", "saturdayWorkHour"));
    }

    public function store(Request $request)
    {
        // return request()->all();

        try {
            DB::beginTransaction();

            if (request("id")) {
                $workingHour = WorkingHour::find(request("id"));

                $message = "diperbaharui";
            } else {
                $workingHour = new WorkingHour;

                $message = "ditambahkan";
            }

            $start_time = Carbon::createFromFormat('H:i', request('start_time'))->format('H:i');
            $after_work = Carbon::createFromFormat('H:i', request('after_work'))->format('H:i');
            $after_work_limit = Carbon::createFromFormat('H:i', request('after_work_limit'))->format('H:i');
            $start_rest = Carbon::createFromFormat('H:i', request('start_rest'))->format('H:i');
            $end_rest = Carbon::createFromFormat('H:i', request('end_rest'))->format('H:i');
            // $maximum_delay = Carbon::createFromFormat('H:i', request('maximum_delay'))->format('H:i');
            $fastest_time = Carbon::createFromFormat('H:i', request('fastest_time'))->format('H:i');
            $overtime_work = Carbon::createFromFormat('H:i', request('overtime_work'))->format('H:i');
            $saturday_work_hour = Carbon::createFromFormat('H:i', request('saturday_work_hour'))->format('H:i');

            $workingHour->start_time = $start_time;
            $workingHour->after_work = $after_work;
            $workingHour->after_work_limit = $after_work_limit;
            $workingHour->start_rest = $start_rest;
            $workingHour->end_rest = $end_rest;
            // $workingHour->maximum_delay = $maximum_delay;
            $workingHour->fastest_time = $fastest_time;
            $workingHour->overtime_work = $overtime_work;
            $workingHour->saturday_work_hour = $saturday_work_hour;
            $workingHour->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [],
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
}
