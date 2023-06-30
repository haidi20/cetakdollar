<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Models\Attendance;
use App\Models\AttendanceFingerspot;
use App\Models\AttendanceHasEmployee;
use App\Models\Employee;
use App\Models\FingerTool;
use App\Models\VwAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use LDAP\Result;
use PhpParser\Node\Stmt\TryCatch;

class AttendanceController extends Controller
{
    public function index()
    {
        $vue = true;
        $baseUrl = Url::to('/');
        $user = auth()->user();

        return view("pages.attendance.index", compact("vue", "user", "baseUrl"));
    }

    public function fetchDataMain()
    {
        $data = [];
        $positionId = request("position_id");
        $month = Carbon::parse(request("month"));
        // $dateRange = $this->dateRange($month->format("Y-m"));
        $dateRange = $this->dateRangeCustom($month, "Y-m-d", "string", true);
        $employees = new Employee;

        if ($positionId != 'all') {
            $employees = $employees->where("position_id", $positionId);
        }

        $employees = $employees->orderBy("name", "desc")->get();

        $data = $employees->map(function ($employee) use ($dateRange, $month) {
            $mainData = [
                'id_finger' => $employee->id_finger,
                'employee_name' => $employee->name,
                'position_name' => $employee->position_name,
            ];

            foreach ($dateRange as $date) {
                $attendanceHasEmployee =  $employee->attendanceHasEmployees
                    ->firstWhere('date', $date);

                if ($attendanceHasEmployee) {
                    $mainData[$date] = (object) [
                        "is_exists" => true,
                        "hour_start" => $this->setTime($attendanceHasEmployee->hour_start),
                        "hour_rest_start" => $this->setTime($attendanceHasEmployee->hour_rest_start),
                        "hour_rest_end" => $this->setTime($attendanceHasEmployee->hour_rest_end),
                        "hour_end" => $this->setTime($attendanceHasEmployee->hour_end),
                    ];
                } else {
                    $mainData[$date] = (object) [
                        "is_exists" => false,
                        "hour_start" => "00:00",
                        "hour_rest_start" => "00:00",
                        "hour_rest_end" => "00:00",
                        "hour_end" => "00:00",
                    ];
                }
            }

            return $mainData;
        });

        return response()->json([
            "data" => $data,
            "dateRange" => $dateRange,
            "request" => request()->all(),
        ]);
    }

    public function fetchDataDetail()
    {
        $result = [];
        $employeeId = request("employee_id");
        $employee = null;
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");

        if ($employeeId) {
            $employee = Employee::find($employeeId);
        }

        $dateRange = $this->dateRangeCustom($month, "d", "object", true);

        foreach ($dateRange as $index => $date) {
            $row = (object) [
                "date" => $date->date,
                "day" => $date->day,
            ];

            if ($employee != null) {
                $attendanceHasEmployee =  $employee->attendanceHasEmployees
                    ->firstWhere('date', $date->date_full);

                if ($attendanceHasEmployee) {
                    $row->is_exists = true;
                    $row->hour_start = $this->setTime($attendanceHasEmployee->hour_start, true);
                    $row->hour_end = $this->setTime($attendanceHasEmployee->hour_end, true);
                    $row->duration_work = $attendanceHasEmployee->duration_work_readable;
                    $row->hour_rest_start = $this->setTime($attendanceHasEmployee->hour_rest_start, true);
                    $row->hour_rest_end = $this->setTime($attendanceHasEmployee->hour_rest_end, true);
                    $row->duration_rest = $attendanceHasEmployee->duration_rest_readable;
                    $row->hour_overtime_start = $this->setTime($attendanceHasEmployee->hour_overtime_start, true);
                    $row->hour_overtime_end = $this->setTime($attendanceHasEmployee->hour_overtime_end, true);
                    $row->duration_overtime = $attendanceHasEmployee->duration_overtime_readable;
                } else {
                    $row->is_exists = false;
                }
            }

            array_push($result, $row);
        }

        return response()->json([
            "data" => $result,
            "monthReadAble" => $monthReadAble,
            "employee" => $employee,
            "request" => request()->all(),
        ]);
    }

    public function export()
    {
        $data = $this->fetchDataMain()->original["data"];
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");
        $dateRange = $this->dateRangeCustom($month, "Y-m-d", "string", true);
        $nameFile = "export/absensi_{$monthReadAble}.xlsx";

        try {
            $path = public_path($nameFile);

            if ($path) {
                @unlink($path);
            }

            Excel::store(new AttendanceExport($data, $dateRange), $nameFile, 'real_public', \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                "success" => true,
                "data" => $data,
                "linkDownload" => route('attendance.download', ["path" => $nameFile]),
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            // $routeAction = Route::currentRouteAction();
            // $log = new LogController;
            // $log->store(request("user_id"), $e->getMessage(), $routeAction);

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

    public function print()
    {
        $data = $this->fetchDataDetail()->original["data"];
        $employee = $this->fetchDataDetail()->original["employee"];

        // return $data;

        return view("pages.attendance.partials.print", compact("data", "employee"));
    }

    public function store()
    {
        set_time_limit(840); // 14 menit
        $dateNow = Carbon::now()->format("Y-m-d");
        $dateStart = request("date_start", $dateNow);
        $dateStart = Carbon::parse($dateStart)->format("Y-m-d");

        // try {
        //     // DB::beginTransaction();

        //     $this->storeFingerSpot();

        //     $this->storeHasEmployee();

        //     // DB::commit();

        //     return response()->json([
        //         'success' => true,
        //         'dateStart' => $dateStart,
        //         'message' => "Berhasil Proses Data Finger",
        //     ], 200);
        // } catch (\Exception $e) {
        //     // DB::rollback();

        //     Log::error($e);

        //     $routeAction = Route::currentRouteAction();
        //     $log = new LogController;
        //     $log->store($e->getMessage(), $routeAction);

        //     return response()->json([
        //         'success' => false,
        //         'message' => "Gagal Proses Data Finger",
        //     ], 500);
        // }

        $this->storeFingerSpot();

        $this->storeHasEmployee();
    }

    public function storeFingerSpot()
    {
        set_time_limit(840); // 14 menit
        $fingerTools = FingerTool::all();
        $dateNow = Carbon::now()->format("Y-m-d");
        $dateStart = request("date_start", $dateNow);
        $dateStart = Carbon::parse($dateStart)->format("Y-m-d");
        $dateEnd = request(
            "date_end",
            $dateStart
        );
        $dateEnd = Carbon::parse($dateEnd)->format("Y-m-d");

        $responseData = [];
        $url = "https://developer.fingerspot.io/api/get_attlog";

        foreach ($fingerTools as $index => $item) {
            if ($item->cloud_id == null) {
                continue;
            }

            $data = [
                "trans_id" => "1",
                "cloud_id" => $item->cloud_id,
                "start_date" => $dateStart,
                "end_date" => $dateEnd,
            ];
            $headers = [
                "Authorization: Bearer R7Y9BW9KPPT36P36",
                "Content-Type: application/json"
            ];

            $options = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $headers,
            ];

            $curl = curl_init();
            curl_setopt_array($curl, $options);

            $response = curl_exec($curl);
            curl_close($curl);

            if ($response === false) {
                // Error occurred
                $error = curl_error($curl);
                echo "Error: " . $error;
            } else {
                // Process the response
                // $responseData = json_decode($response, true);
                $response = json_decode($response, true);
                foreach ($response["data"] as $key => $value) {
                    $data = [
                        "pin" => $value["pin"],
                        "scan_date" => $value["scan_date"],
                        "cloud_id" => $item->cloud_id,
                        "status_scan" => $value["status_scan"],
                        "verify" => $value["verify"],
                    ];

                    array_push($responseData, $data);
                }
                // Handle the response data accordingly
            }
        }

        foreach ($responseData as $index => $item) {
            AttendanceFingerspot::updateOrCreate([
                "pin" => $item['pin'],
                "scan_date" => $item['scan_date'],
                "cloud_id" => $item['cloud_id'],
            ], [
                "status_scan" => $item['status_scan'],
                "verify" => $item['verify'],
            ]);
        }

        // return response()->json([
        //     "dateStart" => $dateStart,
        //     "data" => $responseData,
        // ]);
    }

    public function storeHasEmployee()
    {
        set_time_limit(840); // 14 menit
        $dateNow = Carbon::now()->format("Y-m-d");
        $monthNow = Carbon::now()->format("Y-m");
        $date = request("date", $dateNow);
        $month = Carbon::parse(request("month", $monthNow));
        $dateSecond = request("date_second", $dateNow);

        if (request("date_start") != null) {
            $date = request("date_start");
            $dateSecond = request("date_end");
        }

        $date = Carbon::parse($date)->format("Y-m-d");
        $dateSecond = Carbon::parse($dateSecond)->format("Y-m-d");
        $dateRange = $this->dateRange($month->format("Y-m"));

        $conditionHourEnd = function ($item) {
            $result = $item->hour_end;

            if ($item->hour_overtime_start != null) {
                if ($item->hour_end > $item->hour_overtime_start) {
                    return $item->hour_overtime_start;
                }
            }

            return $result;
        };

        $differentMinuteWork = function ($item, $hourEnd) {
            $hourStart = Carbon::parse($item->hour_start);
            $hourEnd = Carbon::parse($hourEnd);

            return $hourStart->diffInMinutes($hourEnd);
        };

        // return $getAttendance;

        // $attendanceFingerspot = VwAttendance::whereDate("date", $date)->get();
        // $attendanceFingerspot->map(function ($query) {
        if ($month != null) {
            foreach ($dateRange as $index => $date) {
                $spAttendance = "CALL sp_attendance('{$date}')";
                $getAttendance = DB::select($spAttendance);

                foreach ($getAttendance as $index => $item) {
                    AttendanceHasEmployee::updateOrCreate(
                        [
                            // "pin" => $item->pin,
                            "employee_id" => $item->employee_id,
                            "date" => $item->date,
                        ],
                        [
                            "hour_start" => $item->hour_start,
                            "hour_end" => $conditionHourEnd($item),
                            // "duration_work" => $item->duration_work,
                            "duration_work" => $differentMinuteWork($item, $conditionHourEnd($item)),
                            "hour_rest_start" => $item->hour_rest_start,
                            "hour_rest_end" => $item->hour_rest_end,
                            "duration_rest" => $item->duration_rest,
                            "hour_overtime_start" => $item->hour_overtime_start,
                            "hour_overtime_end" => $item->hour_overtime_end,
                            "duration_overtime" => $item->duration_overtime,
                            "hour_overtime_job_order_start" => $item->hour_overtime_job_order_start,
                            "hour_overtime_job_order_end" => $item->hour_overtime_job_order_end,
                            "duration_overtime_job_order" => $item->duration_overtime_job_order,
                            // "date_overtime_job_order_start" => $item->date_overtime_job_order_start,
                            // "date_overtime_job_order_end" => $item->date_overtime_job_order_end,
                            // "name" => $item->name,
                            // "cloud_id" => $item->cloud_id,
                        ],
                    );
                }
            }
        } else {
            $spAttendance = "CALL sp_attendance('{$date}')";
            $getAttendance = DB::select($spAttendance);

            foreach ($getAttendance as $index => $item) {
                AttendanceHasEmployee::updateOrCreate(
                    [
                        // "pin" => $item->pin,
                        "employee_id" => $item->employee_id,
                        "date" => $item->date,
                    ],
                    [
                        "hour_start" => $item->hour_start,
                        "hour_end" => $conditionHourEnd($item),
                        "duration_work" => $differentMinuteWork($item, $conditionHourEnd($item)),
                        "hour_rest_start" => $item->hour_rest_start,
                        "hour_rest_end" => $item->hour_rest_end,
                        "duration_rest" => $item->duration_rest,
                        "hour_overtime_start" => $item->hour_overtime_start,
                        "hour_overtime_end" => $item->hour_overtime_end,
                        "duration_overtime" => $item->duration_overtime,
                        "hour_overtime_job_order_start" => $item->hour_overtime_job_order_start,
                        "hour_overtime_job_order_end" => $item->hour_overtime_job_order_end,
                        "duration_overtime_job_order" => $item->duration_overtime_job_order,
                        // "date_overtime_job_order_start" => $item->date_overtime_job_order_start,
                        // "date_overtime_job_order_end" => $item->date_overtime_job_order_end,
                        // "name" => $item->name,
                        // "cloud_id" => $item->cloud_id,
                    ],
                );
            }
        }

        if ($dateSecond != null) {
            $spAttendanceSecond = "CALL sp_attendance('{$date}')";
            $getAttendanceSecond = DB::select($spAttendanceSecond);

            foreach ($getAttendanceSecond as $index => $item) {
                AttendanceHasEmployee::updateOrCreate(
                    [
                        // "pin" => $item->pin,
                        "employee_id" => $item->employee_id,
                        "date" => $item->date,
                    ],
                    [
                        "hour_start" => $item->hour_start,
                        "hour_end" => $conditionHourEnd($item),
                        "duration_work" => $differentMinuteWork($item, $conditionHourEnd($item)),
                        "hour_rest_start" => $item->hour_rest_start,
                        "hour_rest_end" => $item->hour_rest_end,
                        "duration_rest" => $item->duration_rest,
                        "hour_overtime_start" => $item->hour_overtime_start,
                        "hour_overtime_end" => $item->hour_overtime_end,
                        "duration_overtime" => $item->duration_overtime,
                        "hour_overtime_job_order_start" => $item->hour_overtime_job_order_start,
                        "hour_overtime_job_order_end" => $item->hour_overtime_job_order_end,
                        "duration_overtime_job_order" => $item->duration_overtime_job_order,
                        // "date_overtime_job_order_start" => $item->date_overtime_job_order_start,
                        // "date_overtime_job_order_end" => $item->date_overtime_job_order_end,
                        // "name" => $item->name,
                        // "cloud_id" => $item->cloud_id,
                    ],
                );
            }
        }
    }

    public function storeHasEmployeeOld()
    {
        $dateNow = Carbon::now()->format("Y-m-d");
        $date = request("date", $dateNow);

        if (request("date_start") != null) {
            $date = request("date_start");
        }

        $date = Carbon::parse($date)->format("Y-m-d");

        $query = "CALL SP_ATTENDANCE_HAS_EMPLOYEES('{$date}')";
        $result = DB::select($query);

        // return response()->json([
        //     "date" => $date,
        //     // "data" => $result,
        // ]);
    }

    private function setTime($time, $isNull = false)
    {
        $result = "00:00";

        if ($isNull) {
            $result = null;
        }

        if ($time != null) {
            $result = Carbon::parse($time)->format("H:i");
        }

        return $result;
    }

    private function fetchDataMainOld()
    {
        $result = [];
        $positionId = request("position_id");
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");
        $dateRange = $this->dateRange($month->format("Y-m"));

        $rosters = [
            (object)[
                "id" => 1,
                "id_finger" => 04,
                "employee_name" => "Muhammad Adi",
                "position_name" => "Welder",
            ]
        ];

        foreach ($rosters as $key => $item) {
            $mainData = [];
            $mainData['id'] = $item->id;
            $mainData['id_finger'] = $item->id_finger;
            $mainData['employee_name'] = $item->employee_name;
            $mainData['position_name'] = $item->position_name;

            foreach ($dateRange as $index => $date) {
                $mainData[$date] = (object) [
                    "hour_start" => "08:00",
                    "hour_rest_start" => "12:00",
                    "hour_rest_end" => "13:00",
                    "hour_end" => "17:00",
                ];
            }

            array_push($result, $mainData);
        }

        return response()->json([
            "data" => $result,
            "dateRange" => $dateRange,
        ]);
    }

    private function fetchDataDetailOld()
    {
        $result = [];
        $employeeId = request("employee_id");
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");

        $dateRange = $this->dateRangeCustom($month, "d", "object", true);

        foreach ($dateRange as $index => $date) {
            $row = (object) [
                "date" => $date->date,
                "day" => $date->day, // Add the value for "day"
                "hour_start" => "", // Add the value for "hour_start"
                "hour_end" => "", // Add the value for "hour_end"
                "duration" => "", // Add the value for "duration"
                "hour_rest_start" => "", // Add the value for "hour_rest_start"
                "hour_rest_end" => "", // Add the value for "hour_rest_end"
                "duration_hour_work" => "", // Add the value for "duration_hour_work"
            ];

            array_push($result, $row);
        }

        return response()->json([
            "data" => $result,
            "monthReadAble" => $monthReadAble,
        ]);
    }
}
