<?php

namespace App\Http\Controllers;

use App\Exports\OvertimeExport;
use App\Models\JobStatusHasParent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

class OvertimeReportController extends Controller
{
    public function index()
    {
        $vue = true;
        $baseUrl = Url::to('/');
        $user = auth()->user();

        return view("pages.overtime-report.index", compact("vue", "user", "baseUrl"));
    }

    public function fetchData()
    {
        $nameModel = "App\Models\JobOrderHasEmployee";
        $dateStart = Carbon::parse(request("date_start"));
        $dateEnd = Carbon::parse(request("date_end"));

        $overtimes = JobStatusHasParent::where(["status" => "overtime"])
            ->whereDate("datetime_start", ">=", $dateStart)
            ->whereDate("datetime_start", "<=", $dateEnd)
            ->get();

        return response()->json([
            "overtimes" => $overtimes,
        ]);
    }

    public function export()
    {
        $data = $this->fetchData()->original["overtimes"];
        $dateStart = Carbon::parse(request("date_start"));
        $dateEnd = Carbon::parse(request("date_end"));
        $dateStartReadable = $dateStart->isoFormat("dddd, D MMMM YYYY");
        $dateEndReadable = $dateEnd->isoFormat("dddd, D MMMM YYYY");
        $nameFile = "export/SPL_{$dateStartReadable}-{$dateEndReadable}.xlsx";

        try {
            $path = public_path($nameFile);

            if ($path) {
                @unlink($path);
            }

            Excel::store(new OvertimeExport($data), $nameFile, 'real_public', \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                "success" => true,
                "data" => $data,
                "linkDownload" => route('report.overtime.download', ["path" => $nameFile]),
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

    private function fetchDataOld()
    {
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");

        $overtimes = [
            (object)[
                "id" => 1,
                "employee_name" => "Muhammad Adi",
                "position_name" => "Welder",
                "job_order_code" => "jo001",
                "job_order_name" => "Perbaikan Mesin",
                "date_time_start" => "Jum'at, 05 Mei 2023 17:00",
                "date_time_end" => "Jum'at, 05 Mei 2023 20:00",
                "duration" => "3 Jam",
                "note" => "menyelesaikan perbaikan yang tinggal dikit selesai."
            ],
        ];

        return response()->json([
            "overtimes" => $overtimes,
            "monthReadAble" => $monthReadAble,
        ]);
    }
}
