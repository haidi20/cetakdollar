<?php

namespace App\Http\Controllers;


use App\Models\Finger;
use App\Models\Employee;
use App\Models\FingerTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class FingerController extends Controller
{

    public function getEmployeeFingers($employeeId)
    {
        $fingers = Finger::with('finger_tool')->where('employee_id', $employeeId)->get();

        return response()->json([
            'success' => true,
            'data' => $fingers
        ]);
    }

    public function deleteEmployeeFingers(Request $request)
    {
        $fingerId = $request->input('fingerId');

        // Temukan data finger berdasarkan ID
        $finger = Finger::find($fingerId);

        if ($finger) {
            // Hapus data finger
            $finger->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data finger berhasil dihapus.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data finger tidak ditemukan.'
            ]);
        }
    }

    public function finger(Request $request)
    {
        $idEmployee = $request->input('id');
        $fingerToolId = $request->input('finger_tool_id');
        $idFinger = $request->input('id_finger');

        // Validasi input
        if (empty($fingerToolId) || empty($idEmployee)) {
            return response()->json(['message' => 'Input tidak lengkap'], 400);
        }

        // Simpan data finger ke dalam database jika employee_id ditemukan
        if ($idEmployee) {
            $finger = new Finger();
            $finger->employee_id = $idEmployee;
            $finger->finger_tool_id = $fingerToolId;
            $finger->id_finger = $idFinger;
            $finger->save();

            return response()->json(['message' => 'Data finger berhasil disimpan'], 200);
        }

        return response()->json(['message' => 'Employee tidak ditemukan'], 404);
    }




    public function index()
    {
        $fingers = Finger::all();

        return response()->json($fingers);
    }

    public function store(Request $request)
    {
        // return request()->all();

        try {
            DB::beginTransaction();

            if (request("id")) {
                $finger = Finger::find(request("id"));
                $finger->updated_by = Auth::user()->id;

                $message = "diperbaharui";
            } else {
                $finger = new Finger;
                $finger->created_by = Auth::user()->id;

                $message = "ditambahkan";
            }

            // $finger->employee_type_id = request("employee_type_id");
            $finger->finger_tool_id = request("finger_tool_id");
            $finger->id_finger = request("id_finger");

            $finger->save();


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

            $finger = Finger::find(request("id"));
            $finger->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $finger->delete();

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
