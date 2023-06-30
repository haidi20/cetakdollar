<?php

namespace App\Http\Controllers;

use App\Models\ApprovalAgreement;
use App\Models\ApprovalLevel;
use App\Models\ApprovalLevelDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class ApprovalAgreementController extends Controller
{
    public function approve()
    {
        try {
            $request["approval_level_id"] =  request("approval_level_id");
            $request["user_id"] =  request("user_id");
            $request["model_id"] =  request("model_id");
            $request["name_model"] =  request("name_model");
            $request["status_approval"] =  request("status_approval");

            $storeApprovalAgreement = $this->storeApprovalAgreement(
                request("approval_level_id"),
                request("model_id"),
                request("user_id"),
                request("name_model"),
                request("status_approval"),
                null,
                null,
            );

            return response()->json([
                'success' => true,
                'data' => $storeApprovalAgreement,
                'message' => "Berhasil, data melakukan proses persetujuan",
            ], 201);
        } catch (\Exception $e) {
            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return response()->json([
                'success' => false,
                'message' => 'Gagal, proses persetujuan',
            ], 500);
        }
    }

    public function history()
    {
        $data = ApprovalAgreement::byApprovalLevelId(request("approval_level_id"))
            ->byModel(request("model_id"), request("name_model"))
            ->where("status_approval", "!=", "not yet")
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => "Berhasil, dapat data history approval agreement",
        ], 201);
    }

    public function allHistory()
    {
        $data = ApprovalAgreement::byApprovalLevelId(request("approval_level_id"))
            ->byModel(request("model_id"), request("name_model"))
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => "Berhasil, dapat data history approval agreement",
        ], 201);
    }

    /**

     *Filter the Eloquent model records based on the approval criteria.
     *@param \Illuminate\Database\Eloquent\Model $model The Eloquent model instance to filter.
     *@param int $userId The user ID for filtering.
     *@param string $nameModel The name of the model for filtering.
     *@param string|null $dateStart The start date for filtering. Format: "Y-m-d".
     *@param string|null $dateEnd The end date for filtering. Format: "Y-m-d".
     *@param bool $isByUser Indicates if the filtering is based on user.
     *@return \Illuminate\Database\Eloquent\Builder The updated query builder instance.
     */
    public function whereByApproval($model, $userId, $nameModel, $dateStart, $dateEnd, $isByUser)
    {

        return $model->where(function ($query) use ($userId, $nameModel, $isByUser, $dateStart, $dateEnd) {
            if ($isByUser) {
                $query->where('created_by', $userId)
                    ->orWhereIn('id', function ($query) use ($userId, $nameModel, $dateStart, $dateEnd) {
                        $query->select('model_id')
                            ->from('approval_agreements')
                            ->where('name_model', $nameModel)
                            ->where('user_id', $userId)
                            // ->whereBetween(DB::raw("date_format(`created_at`, '%Y-%m-%d')"), [$dateStart, $dateEnd]);
                            // ->whereDate("created_at", ">=", Carbon::parse($dateStart)->format("Y-m-d"))
                            // ->whereDate("created_at", "<=", Carbon::parse($dateEnd)->format("Y-m-d"));
                            ->where('status_approval', '!=', 'not yet');
                    });
            } else {
            }
        });
    }


    /**

     *Map the approval agreement data to the specified Eloquent model.
     *@param \Illuminate\Database\Eloquent\Model $model The Eloquent model instance to map the data to.
     *@param string $nameModel The name of the model to map the approval agreement data.
     *@param int $userId .
     *@param bool $isByUser Indicates if the mapping is based on user.
     *@return \Illuminate\Database\Eloquent\Model The updated Eloquent model instance.
     */
    public function mapApprovalAgreement($model, $nameModel, $userId, $isByUser = true)
    {
        // if (request("user_id") == null) {
        //     $userId = auth()->user()->id;
        // } else {
        //     $userId = request("user_id");
        // }

        $model->map(function ($query) use ($nameModel, $userId, $isByUser) {
            //CATATAN JANGAN DI HAPUS!!
            //sedang di persetujuan siapa
            //keterangan di status approval :
            //jika login pemohon :
            //  status approval = sedang di review oleh siapa
            //jika login yang meng'approve:
            //  status approval -> logic : tampil jika approval level berdasarkan user pembuat.
            //                              - jika di accept maka akan muncul label terima hijau
            //                              - jika di reject maka akan muncul label tolak merah
            //                              - jika review maka akan muncul label biru
            //                     keterangan : menunggu persetujuan / terima / tolak dengan warna biru jika belum di approve.
            //

            $approvalAgreementQuery = ApprovalAgreement::byApprovalLevelId($query->approval_level_id)
                ->byModel($query["id"], $nameModel);

            $approvalAgreementUsers = $approvalAgreementQuery->pluck("user_id")->toArray();
            // array_push($approvalAgreementUsers, $approvalAgreementQuery->first()->created_by);

            $approvalAgreement = $approvalAgreementQuery
                ->where(function ($subQuery) use ($query, $userId, $isByUser) {

                    // berdasarkan user yang selain pembuat data.
                    if ($isByUser) {
                        if ($query->created_by != $userId) {
                            $subQuery->where(function ($queryUser) use ($userId) {
                                $queryUser->where("user_id", $userId);
                            });
                        }
                    }

                    $subQuery->where("status_approval", "!=", "not yet");
                })
                ->orderBy("level_approval", "desc")
                ->orderBy("created_at", "desc")
                ->first();

            $approvalAgreementFirst = ApprovalAgreement::byApprovalLevelId($query->approval_level_id)
                ->byModel($query["id"], $nameModel)
                ->where("level_approval", 1)
                ->first();

            $statusApprovalLibrary = Config::get("library.status");

            $query->approval_user_id = $approvalAgreement ? $approvalAgreementUsers : null;
            $query->approval_status = $approvalAgreement ? $approvalAgreement->status_approval : null;
            $query->approval_agreement_level = $approvalAgreement ? $approvalAgreement->level_approval : null;
            $query->approval_agreement_note = $approvalAgreement ? $approvalAgreement->note : null;
            $query->approval_agreement_user_id = $approvalAgreement ? $approvalAgreement->user_id : null;
            $query->approval_status_readable = $approvalAgreement ? $statusApprovalLibrary[$approvalAgreement->status_approval]["short_readable"] : null;
            $query->approval_status_first = $approvalAgreementFirst ? $approvalAgreementFirst->status_approval : null;
            $query->approval_label = $approvalAgreement ? $approvalAgreement->label_status_approval : null;
            $query->approval_color = $approvalAgreement ? $statusApprovalLibrary[$approvalAgreement->status_approval]["color"] : null;
            $query->approval_description = $approvalAgreement ? $approvalAgreement->description_status_approval : null;
        });

        return $model;
    }

    /**

     *Store an approval agreement.
     *@param int $approvalLevelId The ID of the approval level.
     *@param int $modelId The ID of the model.
     *@param int $userId The ID of the user.
     *@param string $nameModel The name of the model.
     *@param string $statusApproval The status of the approval.
     *@param int|null $userBehalfId The ID of the user on behalf.
     *@param string|null $note for per user.
     *@return void
     */
    public function storeApprovalAgreement(
        $approvalLevelId,
        $modelId,
        $userId,
        $nameModel,
        $statusApproval,
        $userBehalfId = null,
        $note = null
    ) {
        $checkDataApprovalAgreement = ApprovalAgreement::byApprovalLevelId($approvalLevelId)
            ->byModel($modelId, $nameModel)
            ->count();

        // langkah pertama kali atau jika data di approval agreement kosong.
        if ($checkDataApprovalAgreement == 0) {

            $approvalLevelDetail = ApprovalLevelDetail::byApprovalLevelId($approvalLevelId)
                ->orderBy("level", "asc")
                ->get();

            foreach ($approvalLevelDetail as $index => $item) {
                ApprovalAgreement::create([
                    "note" => $note,
                    "approval_level_id" => $item->approval_level_id,
                    "user_id" => $item->user_id,
                    "model_id" => $modelId,
                    "name_model" => $nameModel,
                    "status_approval" => $index == 0 ? "review" : "not yet",
                    "level_approval" => $item->level,
                ]);
            }
        } else {
            if ($statusApproval != "accept_onbehalf") {
                // $statusApproval = "accept";

                // update status approval current level
                ApprovalAgreement::byApprovalLevelId($approvalLevelId)
                    ->byModel($modelId, $nameModel)
                    ->where('user_id', $userId)
                    ->update([
                        "status_approval" => $statusApproval,
                        "note" => $note,
                    ]);
            }

            $nextLevelApproval = $this->approvalNextLevel(
                $approvalLevelId,
                $modelId,
                $nameModel,
                $userId,
                1
            )->nextLevelApproval;
            $checkNextLevelApproval = $this->approvalNextLevel(
                $approvalLevelId,
                $modelId,
                $nameModel,
                $userId,
                1
            )->checkNextLevelApproval;
            $nextLevelTwiceApproval = $this->approvalNextLevel(
                $approvalLevelId,
                $modelId,
                $nameModel,
                $userId,
                2
            )->nextLevelApproval;
            $currentLevelTwiceApproval = $this->approvalNextLevel(
                $approvalLevelId,
                $modelId,
                $nameModel,
                $userId,
                2
            )->currentLevelApproval;
            $checkNextLevelTwiceApproval = $this->approvalNextLevel(
                $approvalLevelId,
                $modelId,
                $nameModel,
                $userId,
                2
            )->checkNextLevelApproval;

            // status approval di next level akan menjadi 'review'
            if ($statusApproval == "accept") {

                // check apakah masih ada next level atau tidak
                if ($checkNextLevelApproval != null) {
                    ApprovalAgreement::byApprovalLevelId($approvalLevelId)
                        ->byModel($modelId, $nameModel)
                        ->updateOrCreate([
                            "level_approval" => (int) $nextLevelApproval,
                        ], [
                            "note" => $note,
                            "status_approval" => "review",
                        ]);
                }
            } else if ($statusApproval == "reject") {
                // check apakah masih ada next level atau tidak
                if ($checkNextLevelApproval != null) {
                    // update status approval on all next level can be 'not yet'
                    ApprovalAgreement::byApprovalLevelId($approvalLevelId)
                        ->byModel($modelId, $nameModel)
                        ->where("level_approval", ">=", $nextLevelApproval)
                        ->update([
                            "status_approval" => "not yet",
                            "note" => $note,
                        ]);
                }
                // untuk atas nama approval
            } else if ($statusApproval == "accept_onbehalf") {
                // check apakah masih ada next level atau tidak
                if ($checkNextLevelApproval != null) {
                    // update status approval on next level can be 'accept'
                    ApprovalAgreement::byApprovalLevelId($approvalLevelId)
                        ->byModel($modelId, $nameModel)
                        ->where("level_approval", $nextLevelApproval)
                        ->update([
                            "status_approval" => "accept",
                            "user_behalf_id" => $userBehalfId,
                            "note" => $note,
                        ]);

                    if ($checkNextLevelTwiceApproval != null) {
                        ApprovalAgreement::byApprovalLevelId($approvalLevelId)
                            ->byModel($modelId, $nameModel)
                            ->updateOrCreate([
                                "level_approval" => $nextLevelTwiceApproval,
                            ], [
                                "note" => $note,
                                "status_approval" => "review",
                            ]);
                    }
                }
            }
        }

        return "Berhasil, update approval agreement";
    }

    private function approvalNextLevel($approvalLevelId, $modelId, $nameModel, $userId, $addLevel)
    {
        $getNextLevelApproval = ApprovalAgreement::byApprovalLevelId($approvalLevelId)
            ->byModel($modelId, $nameModel)
            ->where("user_id", $userId)
            ->first();

        $nextLevelApproval = 1;
        $currentLevelApproval = 0;

        if ($getNextLevelApproval) {
            $nextLevelApproval = $getNextLevelApproval->level_approval + $addLevel;
            $currentLevelApproval = $getNextLevelApproval->level_approval;
        }

        $checkNextLevelApproval = ApprovalLevelDetail::byApprovalLevelId($approvalLevelId)
            ->where("level", $nextLevelApproval)
            ->first();

        return (object) compact("nextLevelApproval", "currentLevelApproval", "checkNextLevelApproval");
    }
}
