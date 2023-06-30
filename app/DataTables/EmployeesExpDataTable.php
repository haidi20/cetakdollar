<?php

namespace App\DataTables;

use App\Models\Employee;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Carbon;

class EmployeesExpDataTable extends DataTable
{
    protected $dataTableVariable = 'dataTableExp';

    public function dataTable($query)
    {
        $endDate = now()->addMonth()->toDateString(); // Get the end date one month from now

        return datatables()
            ->eloquent($query)
            ->addColumn('aksi', function (Employee $employee) {
                $button = '';

                if (auth()->user()->can('ubah karyawan')) {
                    $button .= '<a href="javascript:void(0)" onclick="onEdit(' . htmlspecialchars(json_encode($employee), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-warning me-2"><i class="bi bi-pen"></i></a>';
                }

                return $button;
            })
            ->editColumn('employee_status', function (Employee $employee) {
                if ($employee->employee_status == 'aktif') {
                    return 'Aktif';
                } elseif ($employee->employee_status == 'meninggal') {
                    return 'Meninggal';
                } else {
                    return 'Keluar';
                }
            })
            ->rawColumns(['aksi'])
            ->filterColumn('position_name', function ($query, $keyword) {
                $sql = "positions.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('location_name', function ($query, $keyword) {
                $sql = "locations.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('company_name', function ($query, $keyword) {
                $sql = "companies.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('employee_status', function ($query, $keyword) {
                $sql = "employees.employee_status like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('contract_end', function ($query, $keyword) {
                $sql = "employees.contract_end like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->whereDate('employees.contract_end', '<=', $endDate);
    }

    public function query(Employee $employee)
    {
        $endDate = now()->addMonth()->toDateString(); // Get the end date one month from now

        return $employee->newQuery()
            ->select('employees.*', 'positions.name as position_name', 'locations.name as location_name', 'companies.name as company_name')
            ->with('company', 'location', 'position')
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->leftJoin('locations', 'employees.location_id', '=', 'locations.id')
            ->leftJoin('companies', 'employees.company_id', '=', 'companies.id')
            ->whereDate('employees.contract_end', '<=', $endDate);
    }

    public function html()
    {
        $columnsArrExPr = [0, 1, 2, 3];
        return $this->builder()
            ->setTableId('employees-exp-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'order' => [[0, 'desc']],
                'responsive' => true,
                'autoWidth' => false,
                'dom' => 'lBfrtip',
                'lengthMenu' => [
                    [10, 25, 50, -1],
                    ['10 Data', '25 Data', '50 Data', 'Semua Data']
                ],
                'buttons' => $this->buttonDatatables($columnsArrExPr),
            ]);
    }

    private function buttonDatatables($columnsArrExPr)
    {
        return [
            ['extend' => 'excel', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export Excel'],
        ];
    }

    protected function getColumns()
    {
        return [
            ['data' => 'id', 'title' => 'No.', 'orderable' => false, 'searchable' => false, 'render' => function () {
                return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            }],
            ['data' => 'nip', 'name' => 'nip', 'title' => 'NIP'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Nama'],
            ['data' => 'position_name', 'name' => 'position_name', 'title' => 'Nama Jabatan'],
            ['data' => 'location_name', 'name' => 'location_name', 'title' => 'Nama Lokasi'],
            ['data' => 'company_name', 'name' => 'company_name', 'title' => 'Nama Perusahaan'],
            ['data' => 'contract_end', 'name' => 'contract_end', 'title' => 'Habis Masa Kontrak'],
            ['data' => 'aksi', 'title' => 'Aksi', 'width' => '110px', 'orderable' => false, 'searchable' => false, 'exportable' => false],
        ];
    }
}
