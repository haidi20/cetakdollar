<?php

namespace App\Exports;

use App\Models\AttendancePayrol;
use App\Models\Payroll;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class PayrollExportPerEmployee implements FromView,WithTitle
{

    protected  $period_payroll= null;
    protected  $employee= null;

    public function __construct($period_payroll,$employee) {
        $this->period_payroll = $period_payroll;
        $this->employee = $employee;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('pages.period_payroll.exports', [
            'period_payroll' => $this->period_payroll,
            'employee' => $this->employee,

            'payroll' => Payroll::where('period_payroll_id',$this->period_payroll->id)->where('employee_id',$this->employee->id)->first(),
            'attendance'=>AttendancePayrol::where('payroll_id',Payroll::where('period_payroll_id',$this->period_payroll->id)->where('employee_id',$this->employee->id)->first()->id)->orderBy('date','asc')->get(),
        ]);
    }

    public function title(): string
    {
        return $this->employee->name ?? 'no-name';
    }
}
