<?php

namespace App\Http\Controllers;

use App\Exports\RosterExport;
use App\Models\Employee;
use App\Models\Roster;
use App\Models\RosterDaily;
use App\Models\RosterStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class RosterController extends Controller
{
    public $path = 'export\roster.xlsx';

    public function index()
    {
        $vue = true;
        $baseUrl = Url::to('/');
        $user = auth()->user();

        return view("pages.roster.index", compact("vue", "user", "baseUrl"));
    }

    public function fetchData()
    {
        $result = [];
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");
        $dateRange = $this->dateRangeCustom($month, "Y-m-d", "string", true);

        $employees = Employee::active()->orderBy("name", "asc")->get();

        foreach ($employees as $key => $item) {
            $roster = Roster::where([
                "employee_id" => $item->id,
            ])->whereYear("month", $month->format("Y"))
                ->whereMonth("month", $month->format("m"))
                ->first();

            $mainData = [];
            $mainData['id'] = $item->id;
            $mainData['id_finger'] = $item->id_finger;
            $mainData['employee_id'] = $item->id;
            $mainData['employee_name'] = $item->name;
            $mainData['position_name'] = $item->position_name;
            $mainData['position_id'] = $item->position_id;
            $mainData['work_schedule'] = $item->work_schedule;
            $mainData['day_off_one'] = $roster ? $roster->day_off_one : null;
            $mainData['day_off_two'] = $roster ? $roster->day_off_two : null;
            $mainData['month'] = $roster ? $roster->month : null;

            if ($roster) {
                $mainData['date_vacation'] = [
                    $roster->date_vacation_start,
                    $roster->date_vacation_end,
                ];
            } else {
                $mainData['date_vacation'] = [null, null];
            }

            foreach ($dateRange as $index => $date) {
                $rosterDaily = RosterDaily::where(["employee_id" => $item->id])
                    ->whereDate("date", $date)
                    ->orderBy("created_at", "desc")
                    ->first();

                $mainData[$date] = [
                    "id" => $rosterDaily != null ? $rosterDaily->id : null,
                    "value" => $rosterDaily != null ? $rosterDaily->roster_status_initial : null,
                    "roster_status_id" => $rosterDaily != null ? $rosterDaily->roster_status_id : null,
                    "color" => $rosterDaily != null ? $rosterDaily->roster_status_color : null,
                    "date" => $rosterDaily != null ? $rosterDaily->date : null,
                ];
            }

            array_push($result, $mainData);
        }

        return response()->json([
            "data" => $result,
            "dateRange" => $dateRange,
            "monthReadAble" => $monthReadAble,
        ]);
    }

    public function fetchTotal($setRosterStatusInitial = null)
    {
        $result = [];
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");
        $dateRange = $this->dateRange($month->format("Y-m"));

        $rosterStatusInitial = request("roster_status_initial", $setRosterStatusInitial);

        $rosterStatusId = RosterStatus::where("initial", $rosterStatusInitial)->first();
        $rosterStatusId = $rosterStatusId ? $rosterStatusId->id : 0;

        $listDriverId = [1];

        foreach ($dateRange as $index => $date) {
            $query = RosterDaily::whereIn("employee_id", $listDriverId);

            if ($rosterStatusInitial != "ALL") {
                $query = $query->where("roster_status_id", $rosterStatusId);
            }

            $result[$date] = $query->whereDate("date", $date)->count();
        }

        return response()->json([
            "data" => $result,
            "monthReadAble" => $monthReadAble,
            "rosterStatusInitial" => $rosterStatusInitial,
        ]);
    }

    public function export()
    {
        $totalRoster = [];
        $rosterStatsuses = RosterStatus::all();
        $data = $this->fetchData()->original["data"];
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");
        $dateRange = $this->dateRange($month->format("Y-m"));

        // foreach ($rosterStatsuses as $key => $value) {
        //     $totalRoster[$value->initial] = $this->fetchTotalRoster($value->initial)->original["data"];
        // }
        // $totalRoster["ALL"] = $this->fetchTotalRoster("ALL")->original["data"];

        try {
            Excel::store(new RosterExport($data, $dateRange), $this->path, 'real_public', \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                "success" => true,
                "data" => $data,
                "linkDownload" => route('roster.download'),
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal export data',
            ], 500);
        }
    }

    public function download()
    {
        $path = storage_path($this->path);

        return Response::download($path);
    }


    public function store()
    {
        $getData = (object) [
            "employee_id" => request("employee_id"),
            "employee_name" => request("employee_name"),
            "work_schedule" => request("work_schedule"),
            "day_off_one" => request("day_off_one"),
            "day_off_two" => request("day_off_two"),
            "month" => request("month"),
            "date_vacation" => request("date_vacation"),
        ];

        // return response()->json([
        //     'success' => true,
        //     'getData' => $getData,
        //     'message' => "Berhasil ditambahkan",
        // ], 200);

        try {
            DB::beginTransaction();

            // store hari kerja
            $this->storeWorkDay($getData);

            // store off pertama
            $this->storeOff($getData, "day_off_one");

            // store off kedua
            if ($getData->day_off_two != null) {
                $this->storeOff($getData, "day_off_two");
            }


            // insert data cuti
            if ($getData->date_vacation != null) {
                $this->storeVacation($getData);
            }


            $this->storeRoster($getData);

            DB::commit();

            // create roster history

            return response()->json([
                'success' => true,
                'data' => request()->all(),
                'message' => "Berhasil ditambahkan",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            // create roster history

            return response()->json([
                'success' => false,
                'message' => 'Gagal ditambahkan',
            ], 500);
        }

        return response()->json([
            "request" => $getData,
        ]);
    }

    private function storeVacation($getData)
    {
        $rosterStatusId = RosterStatus::where("initial", "C")->first()->id;
        $rosterDailyData = [];

        $start = Carbon::parse($getData->date_vacation[0]);
        $end = Carbon::parse($getData->date_vacation[1]);

        while ($start->lte($end)) {
            $rosterDailyData[] = ["date" => $start->format('Y-m-d')];
            $start->addDay();
        }

        foreach ($rosterDailyData as $index => $item) {
            RosterDaily::updateOrCreate(
                [
                    "employee_id" => $getData->employee_id,
                    "date" => $item["date"],
                ],
                [
                    "roster_status_id" => $rosterStatusId,
                ]
            );
        }
    }

    private function storeOff($getData, $nameObject)
    {
        $getDataOff = $nameObject == "day_off_one" ? $getData->day_off_one : $getData->day_off_two;
        $rosterStatusId = RosterStatus::where("initial", "OFF")->first()->id;
        $getDatesOffOne = $this->getDatesByDayName($getDataOff, $getData->month);

        foreach ($getDatesOffOne as $index => $item) {
            RosterDaily::updateOrCreate(
                [
                    "employee_id" => $getData->employee_id,
                    "date" => Carbon::parse($item)->format("Y-m-d"),
                ],
                [
                    "roster_status_id" => $rosterStatusId,
                ]
            );
        }
    }

    private function storeWorkDay($getData)
    {
        $rosterStatusId = RosterStatus::where("initial", "M")->first()->id;
        $start = Carbon::parse($getData->month . '-01')->firstOfMonth();
        $end = Carbon::parse($getData->month . '-01')->endOfMonth();

        while ($start->lte($end)) {
            $rosterDailyData[] = ["date" => $start->format('Y-m-d')];
            $start->addDay();
        }

        foreach ($rosterDailyData as $index => $item) {
            RosterDaily::updateOrCreate(
                [
                    "employee_id" => $getData->employee_id,
                    "date" => Carbon::parse($item["date"])->format("Y-m-d"),
                ],
                [
                    "roster_status_id" => $rosterStatusId,
                ]
            );
        }
    }

    private function storeRoster($getData)
    {
        $roster = Roster::updateOrCreate([
            "employee_id" => $getData->employee_id,
            "month" => Carbon::parse($getData->month),
        ], [
            "day_off_one" => $getData->day_off_one,
            "day_off_two" => $getData->day_off_two,
            "date_vacation_start" => $getData->date_vacation != null ? $getData->date_vacation[0] : null,
            "date_vacation_end" => $getData->date_vacation != null ? $getData->date_vacation[1] : null,
        ]);
    }
}
