<?php

namespace App\Http\Controllers;

use App\Models\Barge;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\FingerTool;
use App\Models\Location;
use App\Models\Position;
use App\Models\Finger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use PDF;
use App\Exports\LaporanMutasiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Sheets\EmployeePositionSheet;
use App\Exports\Sheets\EmployeeLocationSheet;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\DataTables;
use App\DataTables\EmployeesDataTable;
use App\DataTables\EmployeesExpDataTable;
use App\Models\Departmen;
use App\Models\salaryAdjustment;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;


class EmployeeController extends Controller
{
    private $public_path = '/storage/pegawai/';

    // public function getDepartmens($companyId)
    // {
    //     $departmens = Departmen::with('company')->where('company_id', $companyId)->get();

    //     return response()->json($departmens);
    // }

    // public function getPositions($departmenId)
    // {
    //     $positions = Position::with('departmen')->where('departmen_id', $departmenId)->get();

    //     return response()->json($positions);
    // }

    public function getEmployeeFingers($employeeId)
    {
        $fingers = Finger::with('finger_tool')->where('employee_id', $employeeId)->get();

        return response()->json([
            'success' => true,
            'data' => $fingers
        ]);
    }

    public function deleteEmployeeFingers(Request $request)
    {
        $fingerId = $request->input('fingerId');

        // Temukan data finger berdasarkan ID
        $finger = Finger::find($fingerId);

        if ($finger) {
            // Hapus data finger
            $finger->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data finger berhasil dihapus.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data finger tidak ditemukan.'
            ]);
        }
    }

    public function index(EmployeesDataTable $dataTable, EmployeesExpDataTable $dataTableExp)
    {
        $employees = Employee::all();
        $companies = Company::all();
        $positions = Position::all();
        $locations = Location::all();
        $employee_types = EmployeeType::all();
        $barges = Barge::all();
        $departments = Departmen::all();
        $finger_tools = FingerTool::all();
        $fingers = Finger::all();

        $dataTableEmployee = $dataTable->html();
        // $dataTableExpEmployee = $dataTableExp->html();

        $compact = compact('dataTableEmployee', 'employees', 'companies', 'barges', 'departments', 'positions', 'employee_types', 'locations', 'finger_tools', 'fingers');

        return $dataTable->render('pages.master.employee.index', $compact);
    }

    private function buttonDatatables($columnsArrExPr)
    {
        return [
            // ['extend' => 'csv', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export CSV'],
            // ['extend' => 'pdf', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export PDF'],
            ['extend' => 'excel', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export Excel'],
            // ['extend' => 'print', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Print'],
        ];
    }


    // untuk kebutuhan di vuejs
    // semua karyawan
    public function fetchData()
    {
        $employees = Employee::active()->orderBy("name", "asc")->get();

        return response()->json([
            "employees" => $employees,
        ]);
    }

    public function fetchOption()
    {
        $employees = Employee::active()
            ->select("id", "position_id", "name",)
            ->orderBy("name", "asc")
            ->get();

        return response()->json([
            "employees" => $employees,
        ]);
    }

    public function fetchForeman()
    {
        $foremans = Employee::active()->whereHas("position", function ($query) {
            $query->where("name", "Pengawas");
        })->get();

        return response()->json([
            "foremans" => $foremans,
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            if (request("id")) {
                // Logika saat mengubah data
                $employee = Employee::find(request("id"));
                $employee->updated_by = Auth::user()->id;
                $employee->employee_status = request("employee_status");

                $message = "diperbaharui";
            } else {
                // Logika saat menambahkan data baru
                $employee = new Employee;
                $employee->created_by = Auth::user()->id;

                $message = "ditambahkan";
            }

            // DATA PERSONAL
            $employee->nip = request("nip");
            $employee->nik = request("nik");
            $employee->name = request("name");
            $employee->birth_place = request("birth_place");
            $employee->birth_date = request("birth_date");
            $employee->phone = request("phone");
            $employee->religion = request("religion");
            $employee->address = request("address");

            // $photo = '';
            // if ($request->file('photo')) {
            //     $image = $request->file('photo');
            //     $extension = $image->getClientOriginalExtension();
            //     $fileName = 'photo-' . Str::random(10) . '.' . $extension;
            //     Storage::disk('pegawai')->putFileAs('', $image, $fileName, 'public');
            //     $photo = $fileName;
            // }
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo')->store('employee', 'public');
                $employee->photo = $photo;
            }

            // DATA KEPEGAWAIAN
            $employee->enter_date = Carbon::parse($employee->created_at);
            $employee->npwp = request("npwp");
            $employee->no_bpjs = request("no_bpjs");
            $employee->company_id = request("company_id");
            $employee->position_id = request("position_id");
            $employee->location_id = request("location_id");
            $employee->employee_type_id = request("employee_type_id");
            $employee->contract_start = request("contract_start");
            $employee->contract_end = request("contract_end");
            $employee->latest_education = request("latest_education");
            $employee->working_hour = request("working_hour");
            $employee->married_status = request("married_status");
            $employee->update(['bpjsTK' => $employee->bpjs_tk]);
            $employee->update(['bpjsTKPT' => $employee->bpjs_tk_pt]);
            $employee->update(['bpjsKES' => $employee->bpjs_kes]);
            $employee->update(['bpjsKESPT' => $employee->bpjs_kes_pt]);
            $employee->update(['bpjsTRAINING' => $employee->bpjs_training]);
            $employee->out_date = request("out_date");
            $employee->reason = request("reason");

            // DATA GAJI DAN REKENING
            $employee->basic_salary = request("basic_salary");
            $employee->allowance = request("allowance");
            $employee->meal_allowance_per_attend = request("meal_allowance_per_attend");
            $employee->transport_allowance_per_attend = request("transport_allowance_per_attend");
            $employee->attend_allowance_per_attend = request("attend_allowance_per_attend");
            $employee->overtime_rate_per_hour = request("overtime_rate_per_hour");
            $employee->vat_per_year = request("vat_per_year");
            $employee->rekening_number = request("rekening_number");
            $employee->rekening_name = request("rekening_name");
            $employee->bank_name = request("bank_name");
            $employee->branch = request("branch");

            // DATA FINGER
            // $employee->finger_doc_1 = request("finger_doc_1");
            // $employee->finger_doc_2 = request("finger_doc_2");

            $employee->save();

            $finger = Finger::firstOrCreate(
                [
                    'employee_id' => $employee->id,
                    'finger_tool_id' => request('finger_tool_id')
                ],
                [
                    'id_finger' => request('id_finger')
                ]
            );

            // Jika finger sudah ada dan berhasil ditemukan, lakukan update pada id_finger
            if (!$finger->wasRecentlyCreated) {
                $finger->id_finger = request('id_finger');
                $finger->save();
            }

            // $salary_adjustment = SalaryAdjustment::create([
            //     'name' => $request->name,
            // ]);
            // $salary_adjustment->save();

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

    public function bpjsTK(Request $request)
    {
        $id = $request->id;
        $mode = $request->mode;
        $employee = Employee::find($id);
        if ($mode == "true") {
            $employee->bpjs_tk = 'Y';
            // return 1;
        } elseif ($mode == "false") {
            $employee->bpjs_tk = 'N';
            // return 2;
        }
        $employee->update();

        return response()->json($employee, 200);
    }

    public function bpjsTKPT(Request $request)
    {
        $id = $request->id;
        $mode = $request->mode;
        $employee = Employee::find($id);
        if ($mode == "true") {
            $employee->bpjs_tk_pt = 'Y';
            // return 1;
        } elseif ($mode == "false") {
            $employee->bpjs_tk_pt = 'N';
            // return 2;
        }
        $employee->update();

        return response()->json($employee, 200);
    }

    public function bpjsKES(Request $request)
    {
        $id = $request->id;
        $mode = $request->mode;
        $employee = Employee::find($id);
        if ($mode == "true") {
            $employee->bpjs_kes = 'Y';
            // return 1;
        } elseif ($mode == "false") {
            $employee->bpjs_kes = 'N';
            // return 2;
        }
        $employee->update();

        return response()->json($employee, 200);
    }

    public function bpjsKESPT(Request $request)
    {
        $id = $request->id;
        $mode = $request->mode;
        $employee = Employee::find($id);
        if ($mode == "true") {
            $employee->bpjs_kes_pt = 'Y';
            // return 1;
        } elseif ($mode == "false") {
            $employee->bpjs_kes_pt = 'N';
            // return 2;
        }
        $employee->update();

        return response()->json($employee, 200);
    }

    public function bpjsTRAINING(Request $request)
    {
        $id = $request->id;
        $mode = $request->mode;
        $employee = Employee::find($id);
        if ($mode == "true") {
            $employee->bpjs_training = 'Y';
            // return 1;
        } elseif ($mode == "false") {
            $employee->bpjs_training = 'N';
            // return 2;
        }
        $employee->update();

        return response()->json($employee, 200);
    }

    public function exportExcelPositionEmployee($position_id)
    {
        // Menambahkan filter posisi berdasarkan position_id pada query data karyawan
        $employees = Employee::where('position_id', $position_id)->get();

        $data = ['employees' => $employees, 'position_id' => $position_id];

        return Excel::download(new EmployeePositionSheet($data), 'laporan_pegawai_' . $position_id . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function exportExcelLocationEmployee($location_id)
    {
        // Menambahkan filter posisi berdasarkan position_id pada query data karyawan
        $employees = Employee::where('location_id', $location_id)->get();

        $data = ['employees' => $employees, 'location_id' => $location_id];

        return Excel::download(new EmployeeLocationSheet($data), 'laporan_pegawai_' . $location_id . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }


    public function destroy()
    {
        try {
            DB::beginTransaction();

            $employee = Employee::find(request("id"));
            $employee->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $employee->delete();

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
}
