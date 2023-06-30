<?php

namespace App\Http\Controllers;

use App\Models\BpjsCalculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class BpjsCalculationController extends Controller
{
    public function index()
    {
        $bpjs_calculations = BpjsCalculation::all();

        return view("pages.setting.bpjs-calculation", compact("bpjs_calculations"));
    }

    public function getBaseWages(Request $request)
    {
        $bpjsCalculation = new BpjsCalculation();
        $baseWages = $bpjsCalculation->getBaseWages();

        return response()->json(['baseWages' => $baseWages]);
    }

    public function store(Request $request)
    {
        // return request()->all();

        try {
            DB::beginTransaction();

            if (request("id")) {
                $bpjs_calculation = BpjsCalculation::find(request("id"));
                $bpjs_calculation->updated_by = Auth::user()->id;

                $message = "diperbaharui";
            } else {
                $bpjs_calculation = new BpjsCalculation;
                $bpjs_calculation->created_by = Auth::user()->id;

                $message = "ditambahkan";
            }

            $bpjs_calculation->name = request("name");
            $bpjs_calculation->company_percent = request("company_percent");
            $bpjs_calculation->employee_percent = request("employee_percent");
            $baseWages = $bpjs_calculation->getBaseWages();

            $calculateNominal = function ($percent, $baseWage) {
                if ($percent == 0.00) {
                    return 0;
                }

                $nominal = floor($baseWage * $percent);
                $last3Digits = substr($nominal, -3);
                $roundedValue = round($last3Digits / 100) * 100;
                $nominal = $nominal - $last3Digits + $roundedValue;

                if (substr($nominal, -3) === "000") {
                    $nominal = rtrim($nominal, "0") . "0";
                } else {
                    $nominal = rtrim($nominal, "0");
                    // $nominal = rtrim($nominal, "0") . "0";
                }

                return $nominal;
            };

            $bpjs_calculation->company_nominal = $calculateNominal($bpjs_calculation->company_percent, $baseWages['base_wages_bpjs_nominal_1']);
            $bpjs_calculation->employee_nominal = $calculateNominal($bpjs_calculation->employee_percent, $baseWages['base_wages_bpjs_nominal_2']);


            $bpjs_calculation->save();

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

            $bpjs_calculation = BpjsCalculation::find(request("id"));
            $bpjs_calculation->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $bpjs_calculation->delete();

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
