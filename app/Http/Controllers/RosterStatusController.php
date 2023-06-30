<?php

namespace App\Http\Controllers;

use App\Models\RosterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class RosterStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchData()
    {
        $search = request("search");
        $data = new RosterStatus;

        if ($search != null) {
            $data = $data->where("name", "like", "%{$search}%");
        }

        $data = $data->get();

        return response()->json([
            "data" => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name"    => "required",
            "initial"    => "required",
        ]);

        if ($validator->fails()) {
            $msg = null;
            foreach ($validator->messages()->all() as $msgs) {
                $msg = $msg . '' . $msgs . '<br>';
            }

            return response()->json([
                'success' => false,
                'message' => $msg
            ], 409);
        }

        // check apakah sudah ada inisial di database atau belum
        if (request("id") == null) {
            $checkInitial = RosterStatus::where("initial", request("initial"))->first();

            if ($checkInitial) {
                return response()->json([
                    'success' => false,
                    'message' => "Maaf, inisial status roster sudah ada, silahkan gunakan inisial status yang lain",
                ], 409);
            }
        }

        try {
            DB::beginTransaction();

            if (request("id") != null) {
                $rosterStatus = RosterStatus::find(request("id"));

                $message = 'diubah';
                $statusMethod = "update";
            } else {
                $rosterStatus = new RosterStatus;

                $message = 'ditambahkan';
                $statusMethod = "insert";
            }

            $rosterStatus->name = request("name");
            $rosterStatus->initial = request("initial");
            $rosterStatus->note = request("note");
            $rosterStatus->color = request("color", "#FFFFFF");
            $rosterStatus->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => request()->all(),
                'message' => "Berhasil " . $message,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);

            return response()->json([
                'success' => false,
                'message' => 'Gagal ' . $message,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RosterStatus  $rosterStatus
     * @return \Illuminate\Http\Response
     */
    public function show(RosterStatus $rosterStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RosterStatus  $rosterStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(RosterStatus $rosterStatus)
    {
        try {
            $findData = RosterStatus::find(request('id'));

            return response()->json([
                'success' => false,
                'data' => $findData,
                'message' => "Berhasil, data tersedia",
            ], 201);
        } catch (\Exception $e) {
            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);

            return response()->json([
                'success' => false,
                'message' => 'Gagal dapatkan data',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RosterStatus  $rosterStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RosterStatus $rosterStatus)
    {
        //
    }


    public function destroy()
    {
        try {
            DB::beginTransaction();

            $rosterStatus = RosterStatus::find(request("id"));
            $rosterStatus->update([
                'deleted_by' => request("user_id"),
            ]);
            $rosterStatus->delete();

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
