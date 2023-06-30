<?php

namespace App\Http\Controllers;

use App\Models\Departmen;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class DepartmenController extends Controller
{

    public function getDepartmens($companyId)
    {
        $departmens = Departmen::with('company')->where('company_id', $companyId)->get();

        return response()->json($departmens);
    }

    public function getLastCode()
    {
        $companyId = request()->input('company_id');

        $lastDepartmen = Departmen::where('company_id', $companyId)
            ->latest('id')
            ->first();

        if ($lastDepartmen) {
            $lastCode = $lastDepartmen->code;
            $newCode = substr($lastCode, 0, 3) . (intval(substr($lastCode, 3)) + 1);
            return response()->json([
                'lastCode' => $newCode,
            ]);
        } else {
            return response()->json([
                'lastCode' => null,
            ]);
        }
    }

    public function index(Datatables $datatables)
    {
        $columns = [
            'id' => ['title' => 'No.', 'orderable' => false, 'searchable' => false, 'render' => function () {
                return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            }],
            'company_name' => ['name' => 'company_name', 'title' => 'Nama Perusahaan'],
            'code' => ['name' => 'code', 'title' => 'Kode'],
            'name' => ['name' => 'name', 'title' => 'Nama'],
            'description' => ['name' => 'description', 'title' => 'Keterangan'],
            'aksi' => [
                'orderable' => false, 'width' => '110px', 'searchable' => false, 'printable' => false, 'class' => 'text-center', 'width' => '130px', 'exportable' => false
            ],
        ];

        if ($datatables->getRequest()->ajax()) {
            $departmen = Departmen::query()
                ->select('departmens.id', 'departmens.company_id', 'departmens.code', 'departmens.name', 'departmens.description', 'companies.name as company_name')
                ->with('company')
                ->leftJoin('companies', 'departmens.company_id', '=', 'companies.id');

            return $datatables->eloquent($departmen)
                ->filterColumn('company_name', function (Builder $query, $keyword) {
                    $sql = "companies.name like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('code', function (Builder $query, $keyword) {
                    $sql = "departmens.code  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('name', function (Builder $query, $keyword) {
                    $sql = "departmens.name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('description', function (Builder $query, $keyword) {
                    $sql = "departmens.description like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->addColumn('aksi', function (Departmen $data) {
                    $departmen = $data->load('company');
                    $button = '';

                    if (auth()->user()->can('ubah departemen')) {
                        $button .= '<a href="javascript:void(0)" onclick="onEdit(' . htmlspecialchars(json_encode($departmen), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-warning me-2"><i class="bi bi-pen"></i></a>';
                    }

                    if (auth()->user()->can('hapus departemen')) {
                        $button .= '<a href="javascript:void(0)" onclick="onDelete(' . htmlspecialchars(json_encode($departmen), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>';
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

        $companies = Company::all();
        $departmens = Departmen::paginate(10);

        $compact = compact('html', 'departmens', 'companies');

        return view("pages.master.departmen.index", $compact);
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


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            if (request("id")) {
                $departmen = Departmen::find(request("id"));
                $departmen->updated_by = Auth::user()->id;

                // Jika sedang edit, tidak perlu mengubah nilai code
                $departmen->code = $departmen->code;

                $message = "diperbaharui";
            } else {
                $departmen = new Departmen;
                $departmen->created_by = Auth::user()->id;

                $company = Company::find(request("company_id"));

                if ($company) {
                    if ($company->id == 1) {
                        $codePrefix = "PT-";
                    } elseif ($company->id == 2) {
                        $codePrefix = "CV-";
                    }
                }

                $lastDepartmen = Departmen::where('company_id', request("company_id"))->latest('id')->first();

                if ($lastDepartmen) {
                    $lastCode = $lastDepartmen->code;
                    // Mendapatkan nomor dari code terakhir
                    $lastCodeNumber = intval(substr($lastCode, -1));
                    // Increment nomor
                    $nextCodeNumber = $lastCodeNumber + 1;
                    // Membentuk code baru dengan nomor yang diincrement
                    $nextCode = substr($lastCode, 0, -1) . $nextCodeNumber;
                    $departmen->code = $nextCode;
                } else {
                    // Jika tabel kosong, set code awal
                    $departmen->code = $codePrefix . '1';
                }

                $message = "ditambahkan";
            }

            $departmen->name = request("name");
            $departmen->description = request("description");
            $departmen->company_id = request("company_id");
            $departmen->save();

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

    public function destroy()
    {
        try {
            DB::beginTransaction();

            $departmen = Departmen::find(request("id"));
            $departmen->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $departmen->delete();

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
