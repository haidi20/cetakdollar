<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class UserAccountController extends Controller
{

    public function index(Datatables $datatables)
    {
        $columns = [
            'id' => ['title' => 'No.', 'orderable' => false, 'searchable' => false, 'render' => function () {
                return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            }],
            'user_namee' => ['name' => 'user_namee', 'title' => 'Nama User'],
            'account_number' => ['name' => 'account_number', 'title' => 'Nomor Akun'],
            'server_trade' => ['name' => 'server_trade', 'title' => 'Server Trade'],
            'vps_location' => ['name' => 'vps_location', 'title' => 'VPS lokasi'],
            'key_date_expired' => ['name' => 'key_date_expired', 'title' => 'Tanggal Expired'],
            'aksi' => [
                'orderable' => false, 'width' => '110px', 'searchable' => false, 'printable' => false, 'class' => 'text-center', 'width' => '130px', 'exportable' => false
            ],
        ];

        if ($datatables->getRequest()->ajax()) {
            $userAccounts = UserAccount::query()
                ->select(
                    'user_accounts.id',
                    'user_accounts.user_id',
                    'user_accounts.account_number',
                    'users.name as user_namee',
                    'user_accounts.server_trade',
                    'user_accounts.password_trading',
                    'user_accounts.password_investor',
                    'user_accounts.vps_location',
                    'user_accounts.key_expired',
                    // 'user_accounts.key_date_expired',
                )
                ->with("user")
                ->leftJoin('users', 'user_accounts.user_id', '=', 'users.id');

            return $datatables->eloquent($userAccounts)
                ->filterColumn('user_namee', function (Builder $query, $keyword) {
                    $sql = "users.name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('account_number', function (Builder $query, $keyword) {
                    $sql = "user_accounts.account_number like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('server_trade', function (Builder $query, $keyword) {
                    $sql = "user_accounts.server_trade like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('vps_location', function (Builder $query, $keyword) {
                    $sql = "user_accounts.vps_location like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->addColumn('key_date_expired', function (UserAccount $data) {
                    return Carbon::parse($data->key_date_expired)->locale('id')->isoFormat("dddd, D MMMM YYYY");
                })
                ->addColumn('aksi', function (UserAccount $data) {
                    $button = '';

                    if (auth()->user()->can('ubah pengguna')) {
                        $button .= '<a href="javascript:void(0)" onclick="onEdit(' . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . ')" class="btn btn-sm btn-warning me-2"><i class="bi bi-pen"></i></a>';
                    }

                    if (auth()->user()->can('hapus pengguna')) {
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

        $users = User::all();

        $compact = compact('html', 'users');

        return view("pages.setting.user-account", $compact);
    }

    public function store(Request $request)
    {
        // return request()->all();
        $keyExpired = request("key_expired", 30);

        try {
            DB::beginTransaction();

            if (request("id")) {
                $userAccount = UserAccount::find(request("id"));

                $message = "diperbaharui";
            } else {
                $userAccount = new UserAccount;

                $message = "ditambahkan";
            }

            $keyDateExpired = Carbon::now()->addDays($keyExpired)->format("Y-m-d");

            if (request("id")) {
                if (request("key_expired") == $userAccount->key_expired) {
                    $keyDateExpired = $userAccount->key_date_expired;
                }
            }

            $userAccount->user_id = request("user_id");
            $userAccount->account_number = request("account_number");
            $userAccount->server_trade = request("server_trade");
            $userAccount->password_trading = request("password_trading");
            $userAccount->password_investor = request("password_investor");
            $userAccount->vps_location = request("vps_location");
            $userAccount->key_generate = bin2hex(random_bytes(25));
            $userAccount->key_expired = $keyExpired;
            $userAccount->key_date_expired = $keyDateExpired;
            $userAccount->save();

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

            $userAccount = UserAccount::find(request("id"));
            $userAccount->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $userAccount->delete();

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
