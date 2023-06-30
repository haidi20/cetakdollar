<?php

namespace App\Http\Controllers;

use App\Models\BpjsCalculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

                $message = "dikirim";
            }

            $bpjs_calculation->name = request("name");
            $bpjs_calculation->company_percent = request("company_percent");
            $bpjs_calculation->employee_percent = request("employee_percent");
            $baseWages = $bpjs_calculation->getBaseWages();

            $calculateNominal = function ($percent, $baseWage) {
                $nominal = floor($baseWage * $percent);
                $last3Digits = substr($nominal, -3);
                $roundedValue = round($last3Digits / 100) * 100;
                $nominal = $nominal - $last3Digits + $roundedValue;

                if (substr($nominal, -3) === "000") {
                    $nominal = rtrim($nominal, "0") . "0";
                } else {
                    $nominal = rtrim($nominal, "0");
                }

                return $nominal;
            };

            $bpjs_calculation->company_nominal = $calculateNominal($bpjs_calculation->company_percent, $baseWages['base_wages_bpjs_nominal_1']);
            $bpjs_calculation->employee_nominal = $calculateNominal($bpjs_calculation->employee_percent, $baseWages['base_wages_bpjs_nominal_2']);


            // Menghilangkan "00" di belakang angka
            // $bpjs_calculation->company_nominal = rtrim($bpjs_calculation->company_nominal, "0");
            // $bpjs_calculation->employee_nominal = rtrim($bpjs_calculation->employee_nominal, "0");

            // // Mengubah format menjadi 125.597
            // $bpjs_calculation->company_nominal = number_format($bpjs_calculation->company_nominal, 0, ',', '.');
            // $bpjs_calculation->employee_nominal = number_format($bpjs_calculation->employee_nominal, 0, ',', '.');

            // $bpjs_calculation->company_nominal = $baseWagesBPJSTK->nominal * $bpjs_calculation->company_percent;
            // $bpjs_calculation->employee_nominal = $baseWagesBPJSKES->nominal * $bpjs_calculation->employee_percent;

            // // Menghapus angka di belakang koma
            // $bpjs_calculation->company_nominal = floor($bpjs_calculation->company_nominal);
            // $bpjs_calculation->employee_nominal = floor($bpjs_calculation->employee_nominal);

            // // Mengambil 3 angka terakhir
            // $company_nominal_last_3_digits = substr($bpjs_calculation->company_nominal, -3);
            // $employee_nominal_last_3_digits = substr($bpjs_calculation->employee_nominal, -3);

            // // Membulatkan 3 angka terakhir
            // $company_nominal_rounded = round($company_nominal_last_3_digits / 100) * 100;
            // $employee_nominal_rounded = round($employee_nominal_last_3_digits / 100) * 100;

            // // Menambahkan angka bulatan ke nilai nominal
            // $bpjs_calculation->company_nominal = $bpjs_calculation->company_nominal - $company_nominal_last_3_digits + $company_nominal_rounded;
            // $bpjs_calculation->employee_nominal = $bpjs_calculation->employee_nominal - $employee_nominal_last_3_digits + $employee_nominal_rounded;

            // // Menghilangkan "00" di belakang angka
            // $bpjs_calculation->company_nominal = rtrim($bpjs_calculation->company_nominal, "0");
            // $bpjs_calculation->employee_nominal = rtrim($bpjs_calculation->employee_nominal, "0");

            // // Format angka dengan kondisi
            // if ($bpjs_calculation->company_nominal >= 1000) {
            //     $bpjs_calculation->company_nominal = number_format((float)$bpjs_calculation->company_nominal, 0, ',', '.');
            // } else {
            //     $bpjs_calculation->company_nominal = number_format((float)$bpjs_calculation->company_nominal, 3, ',', '.');
            //     $bpjs_calculation->company_nominal = rtrim($bpjs_calculation->company_nominal, "0");
            // }

            // if ($bpjs_calculation->employee_nominal >= 1000) {
            //     $bpjs_calculation->employee_nominal = number_format((float)$bpjs_calculation->employee_nominal, 0, ',', '.');
            // } else {
            //     $bpjs_calculation->employee_nominal = number_format((float)$bpjs_calculation->employee_nominal, 3, ',', '.');
            //     $bpjs_calculation->employee_nominal = rtrim($bpjs_calculation->employee_nominal, "0");
            // }

            $bpjs_calculation->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil {$message}",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

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

            return response()->json([
                'success' => false,
                'message' => 'Gagal dihapus',
            ], 500);
        }
    }
}
