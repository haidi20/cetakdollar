<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function fetchData(Request $request)
    {
        $token = $request->bearerToken();
        $user = User::where("remember_token", $token)->first();
        $histories = History::where("user_id", $user->id);

        return response()->json([
            "status" => true,
            "data" => $histories,
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            if (request("id")) {
                $history = History::find(request("id"));

                $message = "diperbaharui";
            } else {
                $history = new History;

                $message = "ditambahkan";
            }

            $token = $request->bearerToken();
            $user = User::where("remember_token", $token)->first();

            $history->user_id = $user->id;
            $history->order = request('order');
            $history->time = request('time');
            $history->type = request('type');
            $history->symbol = request('symbol');
            $history->price = request('price');
            $history->stop_lost = request('stop_lost');
            $history->take_profit = request('take_profit');
            $history->time_second = request('time_second');
            $history->price_second = request('price_second');
            $history->swap = request('swap');
            $history->profit = request('profit');
            $history->save();

            DB::commit();

            return response()->json([
                'status' => true,
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
                'status' => false,
                'message' => "Gagal {$message}",
            ], 500);
        }
    }
}
