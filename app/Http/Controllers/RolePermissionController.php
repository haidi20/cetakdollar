<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class RolePermissionController extends Controller
{

    public function index(Datatables $datatables, $roleId)
    {
        $nameGroupUser = Role::find($roleId)->name;

        $names = Config("library.feature_private");
        // $features = Feature::whereNotIn("name", $names)->get();

        $columns = [
            'id' => ['title' => 'No.', 'orderable' => false, 'searchable' => false, 'render' => function () {
                return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            }],
            'name' => ['name' => 'name', 'title' => 'Nama'],
            'description' => ['name' => 'description', 'title' => 'Deskripsi'],
            'aksi' => [
                'orderable' => false, 'width' => '110px', 'searchable' => false, 'printable' => false, 'class' => 'text-center', 'width' => '130px', 'exportable' => false
            ],
        ];

        if ($datatables->getRequest()->ajax()) {
            $features = Feature::query()
                ->select('features.id', 'features.name', 'features.description')
                ->whereNotIn('features.name', $names);

            return $datatables->eloquent($features)
                ->filterColumn('name', function (Builder $query, $keyword) {
                    $sql = "features.name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('description', function (Builder $query, $keyword) {
                    $sql = "features.description  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->addColumn('aksi', function (Feature $data) {
                    $button = '';

                    // if (auth()->user()->can('detail hak akses')) {
                    //     $button .= '<a href="/setting/role-feature/' . $data->id . '" class="btn btn-sm btn-info me-2"><i class="bi bi-card-checklist"></i></a>';
                    // }

                    if (auth()->user()->can('ubah hak akses')) {
                        $button .= '<a href="javascript:void(0)" onclick="onEdit(' . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-warning me-2"><i class="bi bi-pen"></i></a>';
                    }

                    // if (auth()->user()->can('hapus hak akses')) {
                    //     $button .= '<a href="javascript:void(0)" onclick="onDelete(' . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>';
                    // }

                    return $button;
                })
                ->rawColumns(['aksi'])
                ->toJson();
        }

        $columnsArrExPr = [0, 1, 2, 3];
        $html = $datatables->getHtmlBuilder()
            ->columns($columns)
            ->parameters([
                'order' => [[0, 'desc']],
                'responsive' => true,
                'autoWidth' => false,
                'dom' => 'lfrtip',
                'lengthMenu' => [
                    [10, 25, 50, -1],
                    ['10 Data', '25 Data', '50 Data', 'Semua Data']
                ],
                // 'buttons' => $this->buttonDatatables($columnsArrExPr),
            ]);

        // $features = Feature::whereNotIn("name", $names)->get();

        $compact = compact('html', 'nameGroupUser', 'roleId');

        return view("pages.setting.role-permission", $compact);
    }


    // public function index($roleId)
    // {
    //     $nameGroupUser = Role::find($roleId)->name;

    //     $names = Config("library.feature_private");
    //     $features = Feature::whereNotIn("name", $names)->get();

    //     return view("pages.setting.role-permission", compact("features", "nameGroupUser", "roleId"));
    // }

    public function show()
    {
        $featureId = request("feature_id");
        $roleId = request("role_id");
        // masih bermasalah

        $permissionsByFeature = Permission::where("feature_id", $featureId)->get();
        $permissionsByRole = Role::findById($roleId)->permissions;

        return response()->json([
            "permissionsByRole" => $permissionsByRole,
            "permissionsByFeature" => $permissionsByFeature,
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $roleId = request("role_id");
            $permissionsByRoleId = [];

            if ($request->permissions_by_role != null) {
                foreach ($request->permissions_by_role as $index => $permission) {
                    array_push($permissionsByRoleId, $permission["id"]);
                }
            }

            foreach ($request->permissions_by_feature as $index => $permission) {
                $role = Role::findById($roleId);

                if (in_array($permission["id"], $permissionsByRoleId)) {
                    $role->givePermissionTo($permission["name"]);
                } else {
                    $role->revokePermissionTo($permission["name"]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil diperbaharui',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);

            return response()->json(['success' => false, 'message' => 'Maaf, gagal kirim data'], 500);
        }
    }
}
