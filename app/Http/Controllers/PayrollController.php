<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceHasEmployee;
use App\Models\AttendancePayrol;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayrollController extends Controller
{
    public function monthly()
    {
        $month = Carbon::now();
        $monthNow = $month->format("Y-m");
        $monthReadAble = $month->isoFormat("MMMM YYYY");
        $employees =  Employee::select('id','name')->get();

        $data = (object) [
            //
        ];

        return view("pages.payroll.monthly", compact(
            "data",
            "month",
            "employees",
            "monthNow",
        ));
    }

    public function fetchInformation()
    {
        $employeeId = request("employee_id");
        $month = Carbon::parse(request("month_filter", Carbon::now()));
        $monthNow = $month->format("Y-m");
        $monthReadAble = $month->isoFormat("MMMM YYYY");

        $employee =  Employee::findOrFail($employeeId);

        return response()->json([
            "employee" => $employee,
            "monthReadAble" => $monthReadAble,
        ]);
    }

    public function fetchSalary()
    {
        $employee_id = request("employee_id");

        $month_filter  = request('month_filter').'-01';
       
        $data = Payroll::whereDate('bulan',$month_filter)->where('employee_id',$employee_id)->firstOrFail();


        // Payroll::whereDate('')
        // $month = Carbon::parse(request("month_filter", Carbon::now()));
        // $monthNow = $month->format("Y-m");
        // $monthReadAble = $month->isoFormat("MMMM YYYY");

        // $data = (object) [
        //     // A. Pendapatan
        //     "jumlah_gaji_dasar" => 1,
        //     "nominal_gaji_dasar" => "3.394.000",
        //     "jumlah_tunjangan_tetap" => 1,
        //     "nominal_tunjangan_tetap" => "-",
        //     "jumlah_uang_makan" => 10,
        //     "nominal_uang_makan" => "-",
        //     // total berapa jam lembur
        //     "jumlah_lembur" => 7,
        //     "nominal_lembur" => "137.326",
        //     "nominal_tambahan_lain_lain" => "130.538",
        //     "jumlah_pendapatan_kotor" => "3.661.864",
        //     // B. Pemotongan
        //     "nominal_bpjs_dibayar_karyawan" => "135.781",
        //     "nominal_pajak_penghasilan_pph21" => "-",
        //     "nominal_potongan_lain_lain" => "-",
        //     "jumlah_potongan" => "135.781",
        //     "gaji_bersih" => "3.526.083",
        // ];

        return response()->json([
            "data" => $data,
        ]);
    }

    public function fetchBpjs()
    {
        $employeeId = request("employee_id");
        $month = Carbon::parse(request("month_filter", Carbon::now()));
        $monthNow = $month->format("Y-m");
        $monthReadAble = $month->isoFormat("MMMM YYYY");

        $data = (object) [
            "dasar_upah_bpjs_tk" => "3.394.513",
            "dasar_upah_bpjs_kesehatan" => "3.394.513",
        ];

        $jaminanSosial = [
            (object)[
                "nama" => "Hari Tua (JHT)",
                "perusahaan_persen" => "3,70",
                "perusahaan_nominal" => "125.597",
                "karyawan_persen" => "2,00",
                "karyawan_nominal" => "67.890",
            ],
            (object)[
                "nama" => "Kecelakaan (JKK)",
                "perusahaan_persen" => "1,74",
                "perusahaan_nominal" => "59.065",
                "karyawan_persen" => "0,00",
                "karyawan_nominal" => "0",
            ],
            (object)[
                "nama" => "Kematian (JKM)",
                "perusahaan_persen" => "0,30",
                "perusahaan_nominal" => "10.184",
                "karyawan_persen" => "0,00",
                "karyawan_nominal" => "0",
            ],
            (object)[
                "nama" => "Pensiun (JP)",
                "perusahaan_persen" => "2,00",
                "perusahaan_nominal" => "67.890",
                "karyawan_persen" => "1,00",
                "karyawan_nominal" => "33.945",
            ],
            (object)[
                "nama" => "Kesehatan (BPJS)",
                "perusahaan_persen" => "4,00",
                "perusahaan_nominal" => "135.781",
                "karyawan_persen" => "1,00",
                "karyawan_nominal" => "33.945",
            ],
        ];

        return response()->json([
            "data" => $data,
            "jaminanSosial" => $jaminanSosial,
        ]);
    }

    public function fetchPph21()
    {
        $employeeId = request("employee_id");
        $month = Carbon::parse(request("month_filter", Carbon::now()));
        $monthNow = $month->format("Y-m");
        $monthReadAble = $month->isoFormat("MMMM YYYY");

        $data = (object) [
            // D. Penghasilan Kotor
            "gaji_kotor_potongan" => "3.661.864",
            "bpjs_dibayar_perusahaan" => "398.516",
            "total_penghasilan_kotor" => "4.060.380",
            // E. Pengurangan
            "biaya_jabatan" => "203.019",
            "bpjs_dibayar_karyawan" => "135.781",
            "jumlah_pengurangan" => "338.800",
            // F. Gaji Bersih 12 Bulan
            "gaji_bersih_setahun" => "44.658.964",
            // G. PKP 12 Bulan= (F)-PTKP
            "pkp_setahun" => "44.658.964",
        ];

        // $table = [
        //     (object) [
        //         "tarif" => "5",
        //         "dari_pkp" => "0",
        //         "ke_pkp" => "50",
        //         "progressive_pph21" => "2.232.948",
        //     ],
        //     (object) [
        //         "tarif" => "15",
        //         "dari_pkp" => "50",
        //         "ke_pkp" => "250",
        //         "progressive_pph21" => "-",
        //     ],
        //     (object) [
        //         "tarif" => "25",
        //         "dari_pkp" => "250",
        //         "ke_pkp" => "500",
        //         "progressive_pph21" => "-",
        //     ],
        //     (object) [
        //         "tarif" => "30",
        //         "dari_pkp" => "500",
        //         "ke_pkp" => "1.000",
        //         "progressive_pph21" => "-",
        //     ],
        // ];

        return response()->json([
            "data" => $data,
            // "table" => $table,
        ]);
    }

    function attendance(){
        // return request()->all();
        
        $employee_id = request()->get('employee_id') ?? '';

        $employee = Employee::findOrFail($employee_id);
        $month_filter = request()->get('month_filter') ?? '';

        $end_date = Carbon::parse($month_filter.'-25')->format('Y-m-d');
        $start_date = Carbon::parse($month_filter.'-26')->addMonth(-1)->format('Y-m-d');

        // return [$start_date,$end_date];

        // 
        $attende_fingers = AttendanceHasEmployee::where('employee_id',$employee_id)
        ->whereDate('date', '>=',$start_date)
        ->whereDate('date', '<=',$end_date)
        ->groupBy('date')
        ->orderBy('date','asc')
        ->get();

        // return $sql = Str::replaceArray('?', $attende_fingers->getBindings(), $attende_fingers->toSql());

        foreach ($attende_fingers as $key => $v) {
            $new_at  = AttendancePayrol::firstOrCreate([
                'employee_id'=>$employee_id,
                'date'=>$v->date,
            ]);

            if($new_at->is_koreksi == 0){
                $new_at->update([
                    'hour_start'=>$v->hour_start,
                    'hour_end'=>$v->hour_end,
                    'duration_work'=>$v->duration_work,

                    'hour_rest_start'=>$v->hour_rest_start,
                    'hour_rest_end'=>$v->hour_rest_end,
                    'duration_rest'=>$v->duration_rest,
                    
                    'hour_overtime_start'=>$v->hour_overtime_start,
                    'hour_overtime_end'=>$v->hour_overtime_end,
                    'duration_overtime'=>$v->duration_overtime,
                ]);
            }
        }

        // Str
        // $sql = \str_replace_array('?', $query->getBindings(), $query->toSql());
        // Str

        // for laravel 5.8^
        //  return $sql = Str::replaceArray('?', $query->getBindings(), $query->toSql());
        // \dd($sql);
        
        
        $attendance = AttendancePayrol::where('employee_id',$employee_id)
        ->whereDate('date', '>=',$start_date)
        ->whereDate('date', '<=',$end_date)
        ->get();

        $data = compact('attendance','employee');

        return view("pages.payroll.partials.attendance_ajax", $data);
    }
}
