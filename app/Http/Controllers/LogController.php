<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;

class LogController extends Controller
{
    public function index(Datatables $datatables)
    {
        $columns = [
            'id' => ['title' => 'No.', 'orderable' => false, 'searchable' => false, 'width' => '10px', 'render' => function () {
                return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            }],
            'user_name' => ['name' => 'user_name', 'title' => 'Pengguna', 'width' => '50px',],
            'location' => ['name' => 'location', 'title' => 'fungsi', 'width' => '150px',],
            'message' => ['name' => 'message', 'title' => 'Pesan'],
            'datetime_readable' => ['name' => 'datetime_readable', 'title' => 'Waktu', 'width' => '200px',],
        ];

        if ($datatables->getRequest()->ajax()) {
            $logs = Log::query()
                ->select('logs.id', 'logs.user_id', 'logs.message', 'logs.created_at', 'logs.location')
                ->with('user')
                ->leftJoin('users', 'logs.user_id', '=', 'users.id')
                ->orderBy("created_at", "desc");

            return $datatables->eloquent($logs)
                ->addColumn('user_name', function (Log $data) {
                    if ($data->user_id != null) {
                        return User::find($data->user_id)->name;
                    } else {
                        return null;
                    }
                })
                ->addColumn('datetime_readable', function (Log $data) {
                    return Carbon::parse($data->created_at)->locale('id')->isoFormat("dddd, D MMMM YYYY HH:mm");
                })
                ->filterColumn('message', function (Builder $query, $keyword) {
                    $sql = "logs.message  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('location', function (Builder $query, $keyword) {
                    $sql = "logs.location  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('user_name', function (Builder $query, $keyword) {
                    $sql = "users.name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
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


        $logs = Log::orderBy("created_at", "desc")->paginate(10);

        $compact = compact('html', 'logs');

        return view("pages.setting.log", $compact);
    }

    public function store($message, $location)
    {
        // return request()->all();

        if (request("user_id") != null) {
            $userId = request("user_id");
        } else {
            $userId = null;
            $user = Auth::user();

            if ($user) {
                $userId = $user->id;
            }
        }

        try {
            DB::beginTransaction();

            $log = new Log;

            $log->user_id = $userId;
            $log->message = limitString($message, 400);
            $log->location = $location;
            $log->save();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();

            // Log::error($e);

            return false;
        }
    }
}
