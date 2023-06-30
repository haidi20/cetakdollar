<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PayrollExport implements WithMultipleSheets
{
    protected  $period_payroll= null;
    protected  $employees= null;

    public function __construct($period_payroll,$employees) {
        $this->period_payroll = $period_payroll;
        $this->employees = $employees;
    }
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->employees as $key => $employee) {
            $sheets[] = new PayrollExportPerEmployee($this->period_payroll,$employee);
        }

    

        return $sheets;
    }

    
}
