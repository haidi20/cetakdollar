<?php

namespace App\Http\Controllers;

use App\Models\BaseWagesBpjs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class BaseWagesBpjsController extends Controller
{
    public function index()
    {
        $base_wages_bpjss = BaseWagesBpjs::all();

        return view("pages.setting.base-wages-bpjs", compact("base_wages_bpjss"));
    }

    public function store(Request $request)
    {
        // return request()->all();

        try {
            DB::beginTransaction();

            if (request("id")) {
                $base_wages_bpjs = BaseWagesBpjs::find(request("id"));
                $base_wages_bpjs->updated_by = Auth::user()->id;

                $message = "diperbaharui";
            } else {
                $base_wages_bpjs = new BaseWagesBpjs;
                $base_wages_bpjs->created_by = Auth::user()->id;

                $message = "ditambahkan";
            }

            $base_wages_bpjs->name = request("name");

            // Membersihkan nilai nominal dari format Rupiah
            $nominal = str_replace(["Rp. ", ".", ","], "", request("nominal"));
            $base_wages_bpjs->nominal = $nominal;

            $base_wages_bpjs->save();


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

            $base_wages_bpjs = BaseWagesBpjs::find(request("id"));
            $base_wages_bpjs->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $base_wages_bpjs->delete();

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
