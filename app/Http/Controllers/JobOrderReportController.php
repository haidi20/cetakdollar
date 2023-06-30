<?php

namespace App\Http\Controllers;

use App\Exports\JobOrderExport;
use App\Exports\OvertimeExport;
use App\Models\JobOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

class JobOrderReportController extends Controller
{
    public function index()
    {
        $vue = true;
        $baseUrl = Url::to('/');
        $user = auth()->user();

        return view("pages.job-order-report.index", compact("vue", "user", "baseUrl"));
    }

    public function fetchData()
    {
        $dateStart = Carbon::parse(request("date_start"));
        $dateEnd = Carbon::parse(request("date_end"));
        $jobOrders = JobOrder::whereDate("datetime_start", ">=", $dateStart)
            ->whereDate("datetime_start", "<=", $dateEnd)
            ->orderBy("created_at", "desc")
            ->get();

        return response()->json([
            "jobOrders" => $jobOrders,
        ]);
    }

    public function export()
    {
        $data = $this->fetchData()->original["jobOrders"];
        $dateStart = Carbon::parse(request("date_start"));
        $dateEnd = Carbon::parse(request("date_end"));
        $dateStartReadable = $dateStart->isoFormat("dddd, D MMMM YYYY");
        $dateEndReadable = $dateEnd->isoFormat("dddd, D MMMM YYYY");
        $nameFile = "export/job_order_{$dateStartReadable}-{$dateEndReadable}.xlsx";

        try {
            $path = public_path($nameFile);

            if ($path) {
                @unlink($path);
            }

            Excel::store(new JobOrderExport($data), $nameFile, 'real_public', \Maatwebsite\Excel\Excel::XLSX);

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
        $jobOrders = [
            (object)[
                "id" => 1,
                "code" => "",
                "project_name" => "Kapal A",
                "job_order_category_name" => "Harian",
                "job_code" => "SC002",
                "job_name" => "Pemakaian Docking Area Per Hari",
                "job_note" => "pemakaian docking A",
                "level" => "mudah",
                "time_type" => "day",
                "date_time_start" => "Jum'at, 04 Mei 2023",
                "date_time_end" => "Jum'at, 05 Mei 2023",
                "duration" => "2 hari",
                "note" => "segera di selesaikan",
            ]
        ];

        return response()->json([
            "jobOrders" => $jobOrders,
        ]);
    }
}
