<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preset;
use App\Models\User;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PresetController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            if (request("id")) {
                $preset = Preset::find(request("id"));

                $message = "diperbaharui";
            } else {
                $preset = new Preset;

                $message = "ditambahkan";
            }

            $token = $request->bearerToken();
            $user = User::where("remember_token", $token)->first();

            $preset->user_id = $user->id;
            $preset->code = request('code');
            $preset->preset = request('preset');
            $preset->date_start = request('date_start');
            $preset->date_end = request('date_end');
            $preset->save();

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
}
