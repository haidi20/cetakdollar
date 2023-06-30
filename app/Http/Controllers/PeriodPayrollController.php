<?php

namespace App\Http\Controllers;

use App\Exports\PayrollExport;
use App\Models\Attendance;
use App\Models\AttendanceHasEmployee;
use App\Models\AttendancePayrol;
use App\Models\BaseWagesBpjs;
use App\Models\BpjsCalculation;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PeriodPayroll;
use App\Models\salaryAdjustmentDetail;
use App\Models\SalaryAdvanceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Carbon\CarbonPeriod;
// use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class PeriodPayrollController extends Controller
{
    public $period_payrol_month_year;
    public function __construct($period_payrol_month_year = null)
    {
        $this->$period_payrol_month_year = $period_payrol_month_year;
    }
    public function index(Datatables $datatables)
    {
        // return "a";
        $columns = [

            // name
            // number_of_workdays




            'id' => ['title' => 'No.', 'orderable' => false, 'searchable' => false, 'render' => function () {
                return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            }],
            'name_period' => ['name' => 'name', 'title' => 'Periode'],
            'date_start' => ['name' => 'date_start', 'title' => 'Tanggal Awal Kerja'],
            'date_end' => ['name' => 'date_end', 'title' => 'Tanggal Akhir Kerja'],
            'aksi' => [
                'orderable' => false, 'width' => '110px', 'searchable' => false, 'printable' => false, 'class' => 'text-center', 'width' => '130px', 'exportable' => false
            ],
        ];

        if ($datatables->getRequest()->ajax()) {
            $period_payroll = PeriodPayroll::query()
                ->select('period_payrolls.last_excel','period_payrolls.period', 'period_payrolls.id', 'period_payrolls.name', 'period_payrolls.date_start', 'period_payrolls.date_end', 'period_payrolls.number_of_workdays');

            return $datatables->eloquent($period_payroll)
                ->filterColumn('name', function (Builder $query, $keyword) {
                    $sql = "period_payrolls.name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->addColumn('name_period', function (PeriodPayroll $data) {
                    return Carbon::parse($data->period)->format('F Y');
                })
                // ->filterColumn('description', function (Builder $query, $keyword) {
                //     $sql = "period_payrolls.description like ?";
                //     $query->whereRaw($sql, ["%{$keyword}%"]);
                // })
                ->addColumn('aksi', function (PeriodPayroll $data) {
                    $button = '';

                    if (auth()->user()->can('download payroll')) {
                        $button .= '<a href="javascript:void(0)" data-download="'.url()->current()."/export?a=".$data->last_excel.'" class="btn-download btn btn-sm btn-warning me-2"><i class="bi bi-download"></i></a>';
                    }

                   

                    return $button;
                })
                ->rawColumns(['aksi'])
                ->toJson();
        }

        $columnsArrExPr = [0, 1, 2, 3];
        $html = $datatables->getHtmlBuilder()
            ->columns($columns)
            ->parameters([
                'order' => [[1, 'desc']],
                'responsive' => true,
                'autoWidth' => false,
                'dom' => 'lfrtip',
                'lengthMenu' => [
                    [10, 25, 50, -1],
                    ['10 Data', '25 Data', '50 Data', 'Semua Data']
                ],
                // 'buttons' => $this->buttonDatatables($columnsArrExPr),
            ]);


        $period_payrolls = PeriodPayroll::all();

        $compact = compact('html', 'period_payrolls');

        return view("pages.period_payroll.index", $compact);
    }

    private function buttonDatatables($columnsArrExPr)
    {
        return [
            ['extend' => 'csv', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export CSV'],
            ['extend' => 'pdf', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export PDF'],
            ['extend' => 'excel', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export Excel'],
            ['extend' => 'print', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Print'],
        ];
    }

    public function fetchData()
    {
        $period_payrolls = PeriodPayroll::orderBy("period", "asc")->get();

        return response()->json([
            "period_payrolls" => $period_payrolls,
        ]);
    }


    public function store(Request $request)
    {

        try {
            DB::beginTransaction();

            $n = PeriodPayroll::where('period', request("period") . "-01")->count();

            if ($n > 0) {
                $period_payroll = PeriodPayroll::where('period', request("period") . "-01")->first();
                $message = "diperbaharui";
            } else {
                if (request("id")) {
                    $period_payroll = PeriodPayroll::find(request("id"));
                    $period_payroll->updated_by = Auth::user()->id ?? null;

                    $message = "diperbaharui";
                } else {
                    $period_payroll = new PeriodPayroll;
                    $period_payroll->created_by = Auth::user()->id ?? null;

                    $message = "ditambahkan";
                }
            }




            $period_payroll->period = request("period") . "-01";
            $period_payroll->date_start = request("date_start");
            $period_payroll->date_end = request("date_end");
            $period_payroll->save();


            // AttendancePayrol::whereDate('date','>=',$period_payroll->date_start)
            // ->whereDate('date','<=',$period_payroll->date_end)
            // ->get();
            // AttendancePayrol::create();

            // if ($message == 'ditambahkan') {
            //     DB::select("
            //     INSERT INTO attendance(pin, cloud_id,employee_id,date,hour_start,hour_end,duration_work,hour_rest_start,hour_rest_end,duration_rest,hour_overtime_start,hour_overtime_end,duration_overtime,hour_overtime_job_order_start,hour_overtime_job_order_end,duration_overtime_job_order,is_weekend,is_vacation,is_payroll_use,payroll_id)
            //     SELECT pin, cloud_id,employee_id,date,hour_start,hour_end,duration_work,hour_rest_start,hour_rest_end,duration_rest,hour_overtime_start,hour_overtime_end,duration_overtime,hour_overtime_job_order_start,hour_overtime_job_order_end,duration_overtime_job_order,is_weekend,is_vacation,is_payroll_use,payroll_id
            //     FROM attendance_has_employees where date(date) >= '" . $period_payroll->date_start . "' AND date(date) <= '" . $period_payroll->date_end . "'
            //     ");
            // }











            $employees = Employee::orderBy('name', 'asc')->get();

            $bpjs_jht = BpjsCalculation::where('code', 'jht')->first();
            $bpjs_jkk = BpjsCalculation::where('code', 'jkk')->first();
            $bpjs_jkm = BpjsCalculation::where('code', 'jkm')->first();
            $bpjs_jp = BpjsCalculation::where('code', 'jp')->first();
            $bpjs_kes = BpjsCalculation::where('code', 'kes')->first();




            $bpjs_dasar_updah_bpjs_tk = BaseWagesBpjs::where('code', 'jk')->first()->nominal ?? 0;
            $dasar_updah_bpjs_kes = BaseWagesBpjs::where('code', 'kes')->first()->nominal ?? 0;


            $tanggal_tambahan_lain =  Carbon::parse(request("period") . "-30");

            $period = CarbonPeriod::create($period_payroll->date_start, $period_payroll->date_end);

            foreach ($employees as $key => $employee) {
                $employee_id = $employee->id;
                $start_date = $period_payroll->date_start;
                $end_date =  $period_payroll->date_end;
                // AttendanceHasEmployee
                $attende_fingers = AttendanceHasEmployee::where('employee_id', $employee_id)
                    ->whereDate('date', '>=', $start_date)
                    ->whereDate('date', '<=', $end_date)
                    ->groupBy('date')
                    ->orderBy('date', 'asc')
                    ->get();

                // return $sql = Str::replaceArray('?', $attende_fingers->getBindings(), $attende_fingers->toSql());

                foreach ($attende_fingers as $key => $v) {
                    $new_at  = AttendancePayrol::firstOrCreate([
                        'employee_id' => $employee_id,
                        'date' => $v->date,
                    ]);

                    if ($new_at->is_koreksi == 0) {
                        $new_at->update([
                            'hour_start' => $v->hour_start,
                            'hour_end' => $v->hour_end,
                            'duration_work' => $v->duration_work,

                            'hour_rest_start' => $v->hour_rest_start,
                            'hour_rest_end' => $v->hour_rest_end,
                            'duration_rest' => $v->duration_rest,

                            'hour_overtime_start' => $v->hour_overtime_start,
                            'hour_overtime_end' => $v->hour_overtime_end,
                            'duration_overtime' => $v->duration_overtime,
                        ]);
                    }
                }



                $data_absens = AttendancePayrol::where('employee_id', $employee->id)
                    ->whereDate('date', '>=', $period_payroll->date_start)
                    ->whereDate('date', '<=', $period_payroll->date_start)
                    ->get();

                $jumlah_jam_lembur_tmp = 0;
                $jumlah_hari_kerja_tmp = 0;
                $jumlah_hari_tidak_masuk_tmp = 0;


                $jumlah_hutang  = 0;

                // return [$period_payroll->date_start, $period_payroll->date_end];

                $jumlah_hutang =  SalaryAdvanceDetail::whereDate('date_start', '<=', $period_payroll->date_end)
                    ->whereDate('date_end', '>=', $period_payroll->date_end)
                    ->sum('amount');

                foreach ($period as $key => $p) {
                    $new_old_d = $data_absens->where('date', $p->format('Y-m-d'))->first();

                    //  if( $p->format('Y-m-d')=='2023-06-19'){
                    //     return $new_old_d;
                    //  }
                    $kali_1 = 0.00;
                    $kali_2 = 0.00;
                    $kali_3 = 0.00;
                    $kali_4 = 0.00;

                    if (isset($new_old_d->id)) {
                        $jumlah_hari_kerja_tmp += 1;
                        if (($new_old_d->duration_overtime != null) && ($new_old_d->duration_overtime > 0)) {

                            $hour_lembur_x = $new_old_d->duration_overtime % 60;
                            $hour_lembur_y =  \floor($new_old_d->duration_overtime / 60);



                            for ($i = 1; $i <= $hour_lembur_y; $i++) {
                                if ($i == 1) {
                                    $jumlah_jam_lembur_tmp += 1.5;
                                    $kali_1 += 1.5;
                                }

                                if ($i > 1) {
                                    $jumlah_jam_lembur_tmp += 2.00;
                                    $kali_2 += 2.00;
                                }
                            }

                            if (($hour_lembur_x > 29) && ($hour_lembur_x < 45) && ($jumlah_jam_lembur_tmp == 0)) {
                                $jumlah_jam_lembur_tmp += 1.5 * 0.5;
                                $kali_1 += 1.5 * 0.5;
                            }

                            if (($hour_lembur_x >= 45) && ($jumlah_jam_lembur_tmp == 0)) {
                                $jumlah_jam_lembur_tmp += 1.5;
                                $kali_1 += 1.5;
                            }


                            if (($hour_lembur_x > 29) && ($hour_lembur_x < 45) && ($jumlah_jam_lembur_tmp > 0)) {
                                $jumlah_jam_lembur_tmp += 2 * 0.5;
                                $kali_2 += 2 * 0.5;
                            }

                            if (($hour_lembur_x >= 45) && ($jumlah_jam_lembur_tmp > 0)) {
                                $jumlah_jam_lembur_tmp += 2.00;
                                $kali_2 += 2.00;
                            }
                        }

                        AttendancePayrol::where('id', $new_old_d->id)->update([
                            'lembur_kali_satu_lima' => $kali_1,
                            'lembur_kali_dua' => $kali_2,
                            'lembur_kali_tiga' => $kali_3,
                            'lembur_kali_empat' => $kali_4,
                        ]);
                    } else {
                        $jumlah_hari_tidak_masuk_tmp += 1;
                        // AttendancePayrol::create([
                        //     ''
                        // ]);
                    }
                }

                $total_tambahan_dari_sa = 0;
                $sa_percents =  salaryAdjustmentDetail::whereMonth('month_start', $tanggal_tambahan_lain->format('m'))
                    ->whereYear('month_start', $tanggal_tambahan_lain->format('Y'))
                    // ->where('type_amount','nominal')
                    ->where('type_time', 'base_time')
                    ->where('employee_id', $employee->id)
                    ->get();

                foreach ($sa_percents as $key => $v) {

                    if ($v->type_amount == 'nominal') {
                        $total_tambahan_dari_sa += $v->amount;
                    } else {
                        $total_tambahan_dari_sa += ($v->amount / 100) * $employee->basic_salary;
                    }
                }



                // $sa_percents =  salaryAdjustmentDetail::whereMonth('month_start',$tanggal_tambahan_lain->format('m'))
                // ->whereYear('month_start',$tanggal_tambahan_lain->format('Y'))
                // ->where('type_amount','percent')
                // ->where('type_time','base_time')
                // ->where('employee_id',$employee->id)
                // ->get();

                // foreach ($sa_percents as $key => $v) {
                //     $total_tambahan_dari_sa += ($v->amount/100) * $employee->basic_salary;
                // }

                $sa_percents =  salaryAdjustmentDetail::whereDate('month_start', '>=', $tanggal_tambahan_lain)
                    ->whereDate('month_end', '<=', $tanggal_tambahan_lain)
                    // ->where('type_amount','percent')
                    ->where('type_time', 'base_time')
                    ->where('employee_id', $employee->id)
                    ->get();

                foreach ($sa_percents as $key => $v) {

                    if ($v->type_amount == 'nominal') {
                        $total_tambahan_dari_sa += $v->amount;
                    } else {
                        $total_tambahan_dari_sa += ($v->amount / 100) * $employee->basic_salary;
                    }
                }


                $sa_percents =  salaryAdjustmentDetail::whereNull('month_start')
                    ->whereNull('month_end')
                    ->where('type_time', 'forever')
                    ->where('employee_id', $employee->id)
                    ->get();

                foreach ($sa_percents as $key => $v) {

                    if ($v->type_amount == 'nominal') {
                        $total_tambahan_dari_sa += $v->amount;
                    } else {
                        $total_tambahan_dari_sa += ($v->amount / 100) * $employee->basic_salary;
                    }
                }




                $jumlah_hari_kerja  = $jumlah_hari_kerja_tmp;
                $pendapatan_tambahan_lain_lain = $total_tambahan_dari_sa;

                // $jumlah_jam_rate_lembur = 109.0; //contoh
                // $pendapatan_tambahan_lain_lain = 2645923; //contoh
                // $jumlah_hari_kerja = 20; //contoh


                $pendapatan_uang_makan = $jumlah_hari_kerja * $employee->meal_allowance_per_attend;
                $pendapatan_lembur = $jumlah_jam_lembur_tmp * $employee->overtime_rate_per_hour;

                $jumlah_pendapatan = $employee->basic_salary + $employee->allowance + $pendapatan_uang_makan + $pendapatan_lembur + $pendapatan_tambahan_lain_lain;



                $jht_perusahaan_persen = 0;
                $jht_karyawan_persen = 0;
                $jht_perusahaan_rupiah = 0;
                $jht_karyawan_rupiah = 0;

                if ($employee->bpjs_jht == 'Y') {
                    $jht_perusahaan_persen  = $bpjs_jht->company_percent ?? 0;
                    $jht_karyawan_persen    = $bpjs_jht->employee_percent ?? 0;
                    $jht_perusahaan_rupiah  = $bpjs_jht->company_nominal ?? 0;
                    $jht_karyawan_rupiah    = $bpjs_jht->employee_nominal ?? 0;
                }

                $jkk_perusahaan_persen = 0;
                $jkk_karyawan_persen = 0;
                $jkk_perusahaan_rupiah = 0;
                $jkk_karyawan_rupiah = 0;



                if ($employee->bpjs_jkk == 'Y') {
                    $jkk_perusahaan_persen  = $bpjs_jkk->company_percent ?? 0;
                    $jkk_karyawan_persen    = $bpjs_jkk->employee_percent ?? 0;
                    $jkk_perusahaan_rupiah  = $bpjs_jkk->company_nominal ?? 0;
                    $jkk_karyawan_rupiah    = $bpjs_jkk->employee_nominal ?? 0;
                }

                $jkm_perusahaan_persen = 0;
                $jkm_karyawan_persen = 0;
                $jkm_perusahaan_rupiah = 0;
                $jkm_karyawan_rupiah = 0;




                if ($employee->bpjs_jkm == 'Y') {
                    $jkm_perusahaan_persen  = $bpjs_jkm->company_percent ?? 0;
                    $jkm_karyawan_persen    = $bpjs_jkm->employee_percent ?? 0;
                    $jkm_perusahaan_rupiah  = $bpjs_jkm->company_nominal ?? 0;
                    $jkm_karyawan_rupiah    = $bpjs_jkm->employee_nominal ?? 0;
                }


                $jp_perusahaan_persen = 0;
                $jp_karyawan_persen = 0;
                $jp_perusahaan_rupiah = 0;
                $jp_karyawan_rupiah = 0;




                if ($employee->bpjs_jp == 'Y') {
                    $jp_perusahaan_persen  = $bpjs_jp->company_percent ?? 0;
                    $jp_karyawan_persen    = $bpjs_jp->employee_percent ?? 0;
                    $jp_perusahaan_rupiah  = $bpjs_jp->company_nominal ?? 0;
                    $jp_karyawan_rupiah    = $bpjs_jp->employee_nominal ?? 0;
                }

                $bpjs_perusahaan_persen = 0;
                $bpjs_karyawan_persen = 0;
                $bpjs_perusahaan_rupiah = 0;
                $bpjs_karyawan_rupiah = 0;

                if ($employee->bpjs_kes == 'Y') {
                    $kes_perusahaan_persen  = $bpjs_kes->company_percent ?? 0;
                    $kes_karyawan_persen    = $bpjs_kes->employee_percent ?? 0;
                    $kes_perusahaan_rupiah  = $bpjs_kes->company_nominal ?? 0;
                    $kes_karyawan_rupiah    = $bpjs_kes->employee_nominal ?? 0;
                }


                $total_bpjs_perusahaan_persen = $jht_perusahaan_persen + $jkk_perusahaan_persen + $jkm_perusahaan_persen + $jp_perusahaan_persen + $kes_perusahaan_persen;
                $total_bpjs_karyawan_persen = $jht_karyawan_persen + $jkk_karyawan_persen + $jkm_karyawan_persen + $jp_karyawan_persen + $kes_karyawan_persen;
                $total_bpjs_perusahaan_rupiah = $jht_perusahaan_rupiah + $jkk_perusahaan_rupiah + $jkm_perusahaan_rupiah + $jp_perusahaan_rupiah + $kes_perusahaan_rupiah;
                $total_bpjs_karyawan_rupiah = $jht_karyawan_rupiah + $jkk_karyawan_rupiah + $jkm_karyawan_rupiah + $jp_karyawan_rupiah + $kes_karyawan_rupiah;



                $ptkp = 0;

                if ($employee->ptkp == 'TK/0') {
                    $ptkp = 54000000;
                }

                if ($employee->ptkp == 'TK/1') {
                    $ptkp = 58500000;
                }

                if ($employee->ptkp == 'TK/2') {
                    $ptkp = 63000000;
                }

                if ($employee->ptkp == 'TK/3') {
                    $ptkp = 67500000;
                }

                if ($employee->ptkp == 'K/0') {
                    $ptkp = 58500000;
                }

                if ($employee->ptkp == 'K/1') {
                    $ptkp = 63000000;
                }

                if ($employee->ptkp == 'K/2') {
                    $ptkp = 67500000;
                }

                if ($employee->ptkp == 'K/3') {
                    $ptkp = 72000000;
                }

                if ($employee->ptkp == 'K/I/0') {
                    $ptkp = 112500000;
                }

                if ($employee->ptkp == 'K/I/1') {
                    $ptkp = 117000000;
                }

                if ($employee->ptkp == 'K/I/2') {
                    $ptkp = 121500000;
                }

                if ($employee->ptkp == 'K/I/3') {
                    $ptkp = 126000000;
                }


                $pemotongan_bpjs_dibayar_karyawan  = $total_bpjs_karyawan_rupiah;

                $pemotongan_potongan_lain_lain = 0;


                // SalaryAdvanceDetail



                $pajak_gaji_kotor_kurang_potongan = $jumlah_pendapatan - $pemotongan_potongan_lain_lain;
                $pajak_bpjs_dibayar_perusahaan = $total_bpjs_perusahaan_rupiah;
                $pajak_total_penghasilan_kotor = $pajak_gaji_kotor_kurang_potongan + $pajak_bpjs_dibayar_perusahaan;


                $pajak_biaya_jabatan = min(500000, (0.05 * $pajak_total_penghasilan_kotor)) * 12;
                $pajak_bpjs_dibayar_karyawan = $total_bpjs_karyawan_rupiah;
                $pajak_total_pengurang = $pajak_biaya_jabatan + $pajak_bpjs_dibayar_karyawan;
                $pajak_gaji_bersih_setahun = (($pajak_total_penghasilan_kotor * 12)  - $pajak_total_pengurang);
                $pkp_setahun = $pajak_gaji_bersih_setahun - $ptkp;


                //menghitung pkp 5%
                $pkp_lima_persen  = \max(0, $pkp_setahun > 60000000 ? ((60000000 - 0) * 0.05) : (($pkp_setahun - 0) * 0.05));
                $pkp_lima_belas_persen  = \max(0, $pkp_setahun > 250000000 ? ((250000000 - 60000000) * 0.15) : (($pkp_setahun - 60000000) * 0.15));
                $pkp_dua_puluh_lima_persen  = \max(0, $pkp_setahun > 500000000 ? ((500000000 - 250000000) * 0.25) : (($pkp_setahun - 250000000) * 0.25));
                $pkp_tiga_puluh_persen  = \max(0, $pkp_setahun > 1000000000 ? ((1000000000 - 500000000) * 0.30) : (($pkp_setahun - 500000000) * 0.30));

                $pajak_pph_dua_satu_setahun = $pkp_lima_persen + $pkp_lima_belas_persen + $pkp_dua_puluh_lima_persen + $pkp_tiga_puluh_persen;

                $pemotongan_pph_dua_satu = $pajak_pph_dua_satu_setahun / 12;
                $jumlah_pemotongan = $pemotongan_bpjs_dibayar_karyawan + $pemotongan_pph_dua_satu + $pemotongan_potongan_lain_lain;
                $gaji_bersih = $jumlah_pendapatan - $jumlah_pemotongan;

                $new_payroll = Payroll::firstOrCreate([
                    'employee_id' => $employee->id,
                    'period_payroll_id' => $period_payroll->id,
                ]);


                $new_payroll->update([
                    'pendapatan_gaji_dasar' => $employee->basic_salary,
                    'pendapatan_tunjangan_tetap' => $employee->allowance,
                    'pendapatan_uang_makan' => $pendapatan_uang_makan,
                    'pendapatan_lembur' => $pendapatan_lembur,
                    'pendapatan_tambahan_lain_lain' => $pendapatan_tambahan_lain_lain,
                    'jumlah_pendapatan' => $jumlah_pendapatan,
                    'pajak_pph_dua_satu_setahun' => $pajak_pph_dua_satu_setahun,


                    // 'pemotongan_bpjs_dibayar_karyawan' => 0,
                    // 'pemotongan_pph_dua_satu' => 0,
                    // 'pemotongan_potongan_lain_lain' => 0,
                    // 'jumlah_pemotongan' => 0,

                    'gaji_bersih' => $gaji_bersih - $jumlah_hutang,
                    'bulan' => $period_payroll->period,
                    'posisi' => "",
                    'gaji_dasar' => $employee->basic_salary,
                    'tunjangan_tetap' => $employee->allowance,


                    'rate_lembur' => $employee->overtime_rate_per_hour,
                    'jumlah_jam_rate_lembur' => $jumlah_jam_lembur_tmp,

                    'tunjangan_makan' => $employee->meal_allowance_per_attend,
                    'jumlah_hari_tunjangan_makan' => $jumlah_hari_kerja,



                    'tunjangan_transport' => $employee->transport_allowance_per_attend,
                    'jumlah_hari_tunjangan_transport' => $jumlah_hari_kerja,



                    'tunjangan_kehadiran' => $employee->attend_allowance_per_attend,
                    'jumlah_hari_tunjangan_kehadiran' => $jumlah_hari_kerja,


                    'ptkp_karyawan' => $ptkp,
                    'jumlah_cuti_ijin_per_bulan' => 0,
                    'sisa_cuti_tahun' => 0,

                    'dasar_updah_bpjs_tk' => $bpjs_dasar_updah_bpjs_tk,
                    'dasar_updah_bpjs_kes' => $dasar_updah_bpjs_kes,



                    'jht_perusahaan_persen' => $jht_perusahaan_persen,
                    'jht_karyawan_persen' => $jht_karyawan_persen,
                    'jht_perusahaan_rupiah' => $jht_perusahaan_rupiah,
                    'jht_karyawan_rupiah' => $jht_karyawan_rupiah,

                    'jkk_perusahaan_persen' => $jkk_perusahaan_persen,
                    'jkk_karyawan_persen' => $jkk_karyawan_persen,
                    'jkk_perusahaan_rupiah' => $jkk_perusahaan_rupiah,
                    'jkk_karyawan_rupiah' => $jkk_karyawan_rupiah,

                    'jkm_perusahaan_persen' => $jkm_perusahaan_persen,
                    'jkm_karyawan_persen' => $jkm_karyawan_persen,
                    'jkm_perusahaan_rupiah' => $jkm_perusahaan_rupiah,
                    'jkm_karyawan_rupiah' => $jkm_karyawan_rupiah,

                    'jp_perusahaan_persen' => $jp_perusahaan_persen,
                    'jp_karyawan_persen' => $jp_karyawan_persen,
                    'jp_perusahaan_rupiah' => $jp_perusahaan_rupiah,
                    'jp_karyawan_rupiah' => $jp_karyawan_rupiah,

                    'bpjs_perusahaan_persen' => $bpjs_perusahaan_persen,
                    'bpjs_karyawan_persen' => $bpjs_karyawan_persen,
                    'bpjs_perusahaan_rupiah' => $bpjs_perusahaan_rupiah,
                    'bpjs_karyawan_rupiah' => $bpjs_karyawan_rupiah,

                    'total_bpjs_perusahaan_persen' => $total_bpjs_perusahaan_persen,
                    'total_bpjs_karyawan_persen' => $total_bpjs_karyawan_persen,
                    'total_bpjs_perusahaan_rupiah' => $total_bpjs_perusahaan_rupiah,
                    'total_bpjs_karyawan_rupiah' => $total_bpjs_karyawan_rupiah,


                    'jumlah_pemotongan' => $jumlah_pemotongan + $jumlah_hutang,

                    'pemotongan_bpjs_dibayar_karyawan' => $pemotongan_bpjs_dibayar_karyawan,
                    'pemotongan_pph_dua_satu' => $pemotongan_pph_dua_satu,
                    'pemotongan_potongan_lain_lain' => $pemotongan_potongan_lain_lain + $jumlah_hutang,


                    'pajak_gaji_kotor_kurang_potongan' => $pajak_gaji_kotor_kurang_potongan,
                    'pajak_bpjs_dibayar_perusahaan' => $pajak_bpjs_dibayar_perusahaan,
                    'pajak_total_penghasilan_kotor' => $pajak_total_penghasilan_kotor,
                    'pajak_biaya_jabatan' => $pajak_biaya_jabatan,
                    'pajak_bpjs_dibayar_karyawan' => $pajak_bpjs_dibayar_karyawan,
                    'pajak_total_pengurang' => $pajak_total_pengurang,
                    'pajak_gaji_bersih_setahun' => $pajak_gaji_bersih_setahun,
                    'pkp_setahun' => $pkp_setahun,

                    'pkp_lima_persen' => $pkp_lima_persen,
                    'pkp_lima_belas_persen' => $pkp_lima_belas_persen,
                    'pkp_dua_puluh_lima_persen' => $pkp_dua_puluh_lima_persen,
                    'pkp_tiga_puluh_persen' => $pkp_tiga_puluh_persen,
                ]);

                AttendancePayrol::whereDate('date', '>=', $period_payroll->date_start)
                    ->whereDate('date', '<=', $period_payroll->date_end)
                    ->where('employee_id', $employee->id)
                    ->where(function ($query) {
                        $query->where('hour_start', '!=', NULL)->orWhere('hour_end', '!=', NULL);
                    })
                    ->update([
                        'payroll_id' => $new_payroll->id
                    ]);
            }


            $unik_name_excel = 'Periode_'.$period_payroll->period.'_'.Str::uuid().'.xlsx';

            $period_payroll->update([
                'last_excel'=>$unik_name_excel
            ]);



            DB::commit();

            Excel::store(new PayrollExport($period_payroll, $employees), $unik_name_excel, 'local');


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
                'message' => "Gagal {$message} {$e->getMessage()}",
            ], 500);
        }
    }

    public function destroy()
    {
        try {
            DB::beginTransaction();

            $period_payroll = PeriodPayroll::find(request("id"));
            $period_payroll->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $period_payroll->delete();

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

    function export()
    {
        $path = storage_path('app\\'.request()->get('a'));
        return response()->download($path);
    }
}
