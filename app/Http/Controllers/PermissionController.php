<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index($featureId)
    {
        $permissions = Permission::where("feature_id", $featureId)->get();

        return view("pages.setting.permission", compact("permissions", "featureId"));
    }

    public function store(Request $request)
    {
        // return request()->all();

        try {
            DB::beginTransaction();

            if (request("id")) {
                $permission = Permission::find(request("id"));

                $message = "diperbaharui";
            } else {
                $permission = new Permission;

                $message = "ditambahkan";
            }

            $permission->name = request("name");
            $permission->feature_id = request("feature_id");
            $permission->description = request("description");
            $permission->save();

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

            $permission = Permission::find(request("id"));
            $permission->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $permission->delete();

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
