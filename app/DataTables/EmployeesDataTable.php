<?php

namespace App\DataTables;

use App\Models\Employee;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;

class EmployeesDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('aksi', function (Employee $employee) {
                $button = '';

                if (auth()->user()->can('ubah karyawan')) {
                    $button .= '<a href="javascript:void(0)" onclick="onEdit(' . htmlspecialchars(json_encode($employee), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-warning me-2"><i class="bi bi-pen"></i></a>';
                }

                if (auth()->user()->can('hapus karyawan')) {
                    $button .= '<a href="javascript:void(0)" onclick="onDelete(' . htmlspecialchars(json_encode($employee), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>';
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
            });
            // ->filterColumn('finger_employee', function ($query, $keyword) {
            //     $sql = "fingers.id_finger like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })
            // ->filterColumn('finger_tool', function ($query, $keyword) {
            //     $sql = "fingers.finger_tool_id like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // });
    }

    public function query(Employee $employee)
    {
        return $employee->newQuery()
            ->select('employees.*', 'positions.name as position_name', 'locations.name as location_name', 'companies.name as company_name','fingers.id_finger as finger_employee', 'fingers.finger_tool_id as finger_tool')
            ->with('company', 'location', 'position', 'finger') // Include the 'fingers' relationship
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->leftJoin('locations', 'employees.location_id', '=', 'locations.id')
            ->leftJoin('companies', 'employees.company_id', '=', 'companies.id')
            ->leftJoin('fingers', 'fingers.employee_id', '=', 'employees.id');
    }


    public function html()
    {
        $columnsArrExPr = [0, 1, 2, 3];
        return $this->builder()
            ->setTableId('employees-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'order' => [[0, 'asc']],
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
            // ['extend' => 'csv', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export CSV'],
            // ['extend' => 'pdf', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export PDF'],
            ['extend' => 'excel', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export Excel'],
            // ['extend' => 'print', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Print'],
        ];
    }

    protected function getColumns()
    {
        return [
            ['data' => 'id', 'title' => 'No.', 'orderable' => false, 'searchable' => false, 'render' => function () {
                return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            }],
            // ['data' => 'id', 'name' => 'id', 'title' => 'No.', 'orderable' => true, 'order' => [0, 'asc'],],
            ['data' => 'nip', 'name' => 'nip', 'title' => 'NIP','orderable' => false, 'searchable' => false, 'exportable' => false],
            ['data' => 'name', 'name' => 'name', 'title' => 'Nama'],
            ['data' => 'position_name', 'name' => 'position_name', 'title' => 'Nama Jabatan'],
            ['data' => 'location_name', 'name' => 'location_name', 'title' => 'Nama Lokasi'],
            ['data' => 'company_name', 'name' => 'company_name', 'title' => 'Nama Perusahaan'],
            ['data' => 'employee_status', 'name' => 'employee_status', 'title' => 'Status'],
            // ['data' => 'finger_employee', 'name' => 'finger_employee', 'title' => 'Finger'],
            // ['data' => 'finger_tool', 'name' => 'finger_tool', 'title' => 'Finger'],
            ['data' => 'aksi', 'title' => 'Aksi', 'width' => '110px', 'orderable' => false, 'searchable' => false, 'exportable' => false],
        ];
    }
}
