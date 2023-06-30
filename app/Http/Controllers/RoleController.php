<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{

    public function index(Datatables $datatables)
    {
        $columns = [
            'id' => ['title' => 'No.', 'orderable' => false, 'searchable' => false, 'render' => function () {
                return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            }],
            'name' => ['name' => 'name', 'title' => 'Nama'],
            'aksi' => [
                'orderable' => false, 'width' => '110px', 'searchable' => false, 'printable' => false, 'class' => 'text-center', 'width' => '130px', 'exportable' => false
            ],
        ];

        if ($datatables->getRequest()->ajax()) {
            $userRoleId = auth()->user()->role_id;
            $roles = Role::query()
                ->select('roles.id', 'roles.name');

            if ($userRoleId != 1) {
                $roles = $roles->where("id", "!=", 1)
                    ->where("id", "!=", 2);
            }

            return $datatables->eloquent($roles)
                ->filterColumn('name', function (Builder $query, $keyword) {
                    $sql = "roles.name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->addColumn('aksi', function (Role $data) {
                    $button = '';

                    if (auth()->user()->can('detail grup pengguna')) {
                        $button .= '<a href="/setting/role-permission/' . $data->id . '" class="btn btn-sm btn-info me-2"><i class="bi bi-card-checklist"></i></a>';
                    }

                    if (auth()->user()->can('ubah grup pengguna')) {
                        $button .= '<a href="javascript:void(0)" onclick="onEdit(' . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-warning me-2"><i class="bi bi-pen"></i></a>';
                    }

                    if (auth()->user()->can('hapus grup pengguna')) {
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

        $roles = Role::paginate(10);

        $compact = compact('html', 'roles');

        return view("pages.setting.role", $compact);
    }

    // public function index()
    // {
    //     $userRoleId = auth()->user()->role_id;

    //     $roles = new Role;

    //     if ($userRoleId != 1) {
    //         $roles = $roles->where("id", "!=", 1)
    //             ->where("id", "!=", 2);
    //     }

    //     $roles = $roles->orderBy('created_at', 'desc')->get();

    //     return view("pages.setting.role", compact("roles"));
    // }

    public function store(Request $request)
    {
        // return request()->all();

        try {
            DB::beginTransaction();

            // Role::updateOrCreate(
            //     ["id" => $request->id],
            //     [
            //         "name" => $request->name,
            //         "guard_name" => "web",
            //     ]
            // );

            if (request("id")) {
                $role = Role::find(request("id"));

                $message = "diperbaharui";
            } else {
                $role = new Role;

                $message = "ditambahkan";
            }

            $role->name = request("name");
            $role->guard_name = "web";
            $role->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Berhasil Kirim Data'], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);
            return response()->json(['success' => false, 'message' => 'Maaf, gagal kirim data'], 500);
        }
    }

    public function destroy()
    {
        try {
            DB::beginTransaction();

            $role = Role::find(request("id"));
            $role->update([
                'deleted_by' => request("user_id"),
            ]);
            $role->delete();

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
