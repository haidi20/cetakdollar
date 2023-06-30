<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(Datatables $datatables)
    {
        $columns = [
            'id' => ['title' => 'No.', 'orderable' => false, 'searchable' => false, 'render' => function () {
                return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            }],
            'name' => ['name' => 'name', 'title' => 'Nama'],
            'role_name' => ['name' => 'role_name', 'title' => 'Grup Pengguna'],
            'email' => ['name' => 'email', 'title' => 'Email'],
            'location_name' => ['name' => 'location_name', 'title' => 'Lokasi'],
            'aksi' => [
                'orderable' => false, 'width' => '110px', 'searchable' => false, 'printable' => false, 'class' => 'text-center', 'width' => '130px', 'exportable' => false
            ],
        ];

        if ($datatables->getRequest()->ajax()) {
            $userRoleId = auth()->user()->role_id;
            $users = User::query()
                ->select('users.id', 'users.location_id', 'users.name', 'users.role_id', 'users.email', 'roles.name as role_name')
                ->with('role')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id');

            if ($userRoleId != 1) {
                $users = $users->where("users.id", "!=", 1);
            }

            return $datatables->eloquent($users)
                ->filterColumn('name', function (Builder $query, $keyword) {
                    $sql = "users.name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('role_name', function (Builder $query, $keyword) {
                    $sql = "roles.name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('email', function (Builder $query, $keyword) {
                    $sql = "users.email  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->addColumn('location_name', function (User $data) {
                    if ($data->location_id != null) {
                        return Location::find($data->location_id)->name;
                    } else {
                        return null;
                    }
                })
                ->addColumn('aksi', function (User $data) {
                    $role = $data->load('roles');
                    $button = '';

                    if (auth()->user()->can('ubah pengguna')) {
                        $button .= '<a href="javascript:void(0)" onclick="onEdit(' . htmlspecialchars(json_encode($role), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-warning me-2"><i class="bi bi-pen"></i></a>';
                    }

                    if (auth()->user()->can('hapus pengguna')) {
                        $button .= '<a href="javascript:void(0)" onclick="onDelete(' . htmlspecialchars(json_encode($role), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>';
                    }

                    return $button;
                })
                ->rawColumns(['location_name', 'aksi'])
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

        $roles = new Role;

        if (auth()->user()->role_id != 1) {
            $roles = $roles->where("id", "!=", 1);
        }

        $roles = $roles->get();

        $users = User::paginate(10);
        $locations = Location::all();

        $compact = compact('html', 'roles', 'users', 'locations');

        return view("pages.setting.user", $compact);
    }

    // public function index()
    // {
    // $userRoleId = auth()->user()->role_id;

    // $roles = new Role;
    // $users = new User;

    // if ($userRoleId != 1) {
    //     $roles = $roles->where("id", "!=", 1);
    //     $users = $users->where("id", "!=", 1);
    // }

    //     $roles = $roles->get();
    //     $users = $users->get();

    //     return view("pages.setting.user", compact("users", "roles"));
    // }

    public function fetchPermission()
    {
        $user = User::find(request("user_id"));
        $permissions = $user->permission;

        return response()->json([
            'success' => true,
            'permissions' => $permissions,
        ], 200);
    }

    public function store(Request $request)
    {
        // return request()->all();

        try {
            DB::beginTransaction();

            if (request("id")) {
                $user = User::find(request("id"));

                $message = "diperbaharui";
            } else {
                $user = new User;

                $message = "ditambahkan";
            }

            if (request("password") != null) {
                $user->password = bcrypt(request("password"));
            }

            $user->name = request("name");
            $user->location_id = request("location_id");
            $user->email = request("email");
            $user->role_id = request("role_id");
            $user->save();

            $role = Role::find(request("role_id"));

            $user->assignRole($role->name);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [],
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

            $user = User::find(request("id"));
            $user->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $user->delete();

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

    private function index_backup()
    {
        // Define the data
        $usersData = [
            [
                'name' => 'John Doe',
                'role_id' => 2,
                'email' => 'john@example.com',
                'password' => bcrypt('secret'),
            ],
            [
                'name' => 'Jane Doe',
                'role_id' => 2,
                'email' => 'jane@example.com',
                'password' => bcrypt('secret'),
            ],
        ];

        // Create a collection of data
        $users = new Collection();

        foreach ($usersData as $data) {
            $user = new User();
            $user->fill($data);
            $users->add($user);
        }

        // return $user;
    }
}
