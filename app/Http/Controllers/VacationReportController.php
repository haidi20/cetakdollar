<?php

namespace App\Http\Controllers;

use App\Exports\VacationReportExport;
use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

// vacation = cuti
class VacationReportController extends Controller
{
    public function index()
    {
        $dateStart = Carbon::now()->startOfMonth();
        $dateEnd = Carbon::now()->endOfMonth();

        return view("pages.vacation-report.index", compact("dateStart", "dateEnd"));
    }

    public function fetchData()
    {
        // $dateStart = Carbon::parse(request("date_start"));
        // $dateEnd = Carbon::parse(request("date_end"));
        $month = Carbon::parse(request("month"));

        // $vacations = Vacation::whereDate("date_start", ">=", $dateStart->format("Y-m-d"))
        //     ->whereDate("date_start", "<=", $dateEnd->format("Y-m-d"))
        //     ->orderBy("created_at", "desc")
        //     ->get();

        $vacations = Vacation::whereYear("date_start", $month->format("Y"))
            ->whereMonth("date_start", $month->format("m"))
            ->orderBy("created_at", "desc")
            ->get();

        return response()->json([
            // "dateStart" => $dateStart->format("Y-m-d"),
            // "dateEnd" => $dateEnd->format("Y-m-d"),
            "month" => $month->format("Y-m-d"),
            "vacations" => $vacations,
        ]);
    }

    public function export()
    {
        $data = $this->fetchData()->original["vacations"];
        $dateStart = Carbon::parse(request("date_start"));
        $dateEnd = Carbon::parse(request("date_end"));
        $dateStartReadable = $dateStart->isoFormat("dddd, D MMMM YYYY");
        $dateEndReadable = $dateEnd->isoFormat("dddd, D MMMM YYYY");
        $nameFile = "export/cuti_{$dateStartReadable}-{$dateEndReadable}.xlsx";

        try {
            $path = public_path($nameFile);

            if ($path) {
                @unlink($path);
            }

            Excel::store(new VacationReportExport($data), $nameFile, 'real_public', \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                "success" => true,
                "data" => $data,
                "linkDownload" => route('report.vacation.download', ["path" => $nameFile]),
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
}
