<?php

namespace App\Http\Controllers;

use App\Models\FingerTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Permission;

class FingerToolController extends Controller
{
    public function index(Datatables $datatables)
    {
        $columns = [
            'id' => ['title' => 'No.', 'orderable' => false, 'searchable' => false, 'render' => function () {
                return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            }],
            'name' => ['name' => 'name', 'title' => 'Nama'],
            'serial_number' => ['name' => 'serial_number', 'title' => 'Serial Number'],
            'aksi' => [
                'orderable' => false, 'width' => '110px', 'searchable' => false, 'printable' => false, 'class' => 'text-center', 'width' => '130px', 'exportable' => false
            ],
        ];

        if ($datatables->getRequest()->ajax()) {
            $finger_tool = FingerTool::query()
                ->select('finger_tools.id', 'finger_tools.name', 'finger_tools.serial_number',);

            return $datatables->eloquent($finger_tool)
                ->filterColumn('name', function (Builder $query, $keyword) {
                    $sql = "finger_tools.name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('serial_number', function (Builder $query, $keyword) {
                    $sql = "finger_tools.serial_number like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->addColumn('aksi', function (FingerTool $data) {
                    $button = '';

                    if (auth()->user()->can('ubah alat finger')) {
                        $button .= '<a href="javascript:void(0)" onclick="onEdit(' . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-warning me-2"><i class="bi bi-pen"></i></a>';
                    }

                    if (auth()->user()->can('hapus alat finger')) {
                        $button .= '<a href="javascript:void(0)" onclick="onDelete(' . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>';
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


        $finger_tools = FingerTool::all();

        $compact = compact('html', 'finger_tools');

        return view("pages.master.finger-tool.index", $compact);
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
        // return request()->all();

        try {
            DB::beginTransaction();

            if (request("id")) {
                $finger_tool = FingerTool::find(request("id"));
                $finger_tool->updated_by = Auth::user()->id;

                $message = "diperbaharui";
            } else {
                $finger_tool = new FingerTool;
                $finger_tool->created_by = Auth::user()->id;

                $message = "ditambahkan";
            }

            $finger_tool->name = request("name");
            $finger_tool->serial_number = request("serial_number");
            $finger_tool->save();

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

            $finger_tool = FingerTool::find(request("id"));
            $finger_tool->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $finger_tool->delete();

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
