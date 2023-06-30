<?php

namespace App\Http\Controllers;

use App\Models\ApprovalAgreement;
use App\Models\ApprovalLevel;
use App\Models\ApprovalLevelDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class ApprovalLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $approvalLevels = ApprovalLevel::all();

        return view('pages.setting.approval-level', compact("approvalLevels"));
    }

    // orang yang berwenang untuk approval
    public function selectAuthorizeds()
    {
        $data = User::where(function ($query) {
            $query->orWhere('name', 'LIKE', '%' . request("search") . '%');
        })
            ->limit(10)
            ->get();

        return response()->json(['status' => true, "data" => $data, "message" => "Berhasil dapat data user"], 200);
    }

    // salah satu kebutuhan di: personnel request,
    public function selectApprovalLevel()
    {
        $data = ApprovalLevel::where("name", 'LIKE', '%' . request("search") . '%')
            ->limit(10)
            ->get();

        return response()->json(['status' => true, "data" => $data, "message" => "Berhasil dapat data approval level"], 200);
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
            "levels"  => "required|array|min:1",
            // "levels.*" => "distinct",
            "status"  => "required|array|min:1",
            // "status.*" => "distinct",
            "authorizeds" => "required|array|min:1",
            // "authorizeds.*" => "distinct",
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

        $checkLevels = request("levels");
        $maxLevel = max($checkLevels);
        $minLevel = min($checkLevels);
        $compareLevel = [];

        for ($min = 1; $min <= $maxLevel; $min++) {
            $compareLevel[] = $min;
        }

        $resultCompare = array_diff($compareLevel, $checkLevels);

        if ($resultCompare) {
            return response()->json([
                'success' => true,
                'data' => $resultCompare,
                'message' => "Maaf, level " . implode(', ', $resultCompare) . " tidak ditemukan",
            ], 409);
        }

        if ($minLevel < 0) {
            return response()->json([
                'success' => true,
                'data' => $minLevel,
                'message' => "Maaf, level tidak boleh negatif",
            ], 409);
        }

        try {
            DB::beginTransaction();

            if (request("id")) {
                $approvalLevel = ApprovalLevel::find(request("id"));
                // $approvalLevel->updated_by = Auth::id();

                $message = 'diubah';
            } else {
                $approvalLevel = new ApprovalLevel;
                // $approvalLevel->created_by = Auth::id();
                $message = 'ditambahkan';
            }

            $approvalLevel->name = request("name");
            $approvalLevel->save();

            $approvalLevelDetail = ApprovalLevelDetail::where("approval_level_id", request("id"));
            $approvalLevelDetail->delete();

            $isUpdate = false;
            $checkApprovalAgreement = ApprovalAgreement::where("approval_level_id", request("id"))
                ->orderBy("level_approval", "desc")->first();
            // kalo di level paling tinggi sudah approve, maka jangan di ganti user_idnya
            if ($checkApprovalAgreement) {
                $isUpdate = $checkApprovalAgreement->status_approval == "accept" ? false : true;
            }
            // $approvalAgreement->delete();

            $levels = request("levels");
            $status = request("status");
            $authorizeds = request("authorizeds");

            foreach ($authorizeds as $index => $item) {
                $approvalLevelDetail->create([
                    "approval_level_id" => $approvalLevel->id,
                    "user_id" => $authorizeds[$index],
                    "status" => $status[$index],
                    "level" => $levels[$index],
                ]);

                if ($isUpdate) {
                    $approvalAgreement = ApprovalAgreement::where(["approval_level_id" => request("id"), "level_approval" => $levels[$index]]);
                    $approvalAgreement->update([
                        "user_id" => $authorizeds[$index],
                        "status_approval" => $levels[$index] == 1 ? "review" : "not yet",
                    ]);
                }
            }

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
                'message' => 'Gagal' . $message,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApprovalLevel  $approvalLevel
     * @return \Illuminate\Http\Response
     */
    public function show(ApprovalLevel $approvalLevel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApprovalLevel  $approvalLevel
     * @return \Illuminate\Http\Response
     */
    public function edit(ApprovalLevel $approvalLevel)
    {
        try {
            $findData = ApprovalLevel::with("detail")->where("id", request('id'))->first();

            return response()->json([
                'success' => false,
                'data' => $findData,
                'message' => "Berhasil, data tersedia",
            ], 201);
        } catch (\Exception $e) {
            Log::error($e);

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
     * @param  \App\Models\ApprovalLevel  $approvalLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApprovalLevel $approvalLevel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApprovalLevel  $approvalLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApprovalLevel $approvalLevel)
    {
        try {
            DB::beginTransaction();

            $approvalLevel = ApprovalLevel::find(request("id"));
            $approvalLevel->update([
                'deleted_by' => Auth::id(),
            ]);
            $approvalLevel->delete();

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
