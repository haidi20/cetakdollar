<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class BaseWagesBpjs extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = Schema::getColumnListing($this->getTable());
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::updating(function ($baseWagesBpjs) {
    //         // Mengambil perubahan nilai nominal
    //         $oldNominal = $baseWagesBpjs->getOriginal('nominal');
    //         $newNominal = $baseWagesBpjs->nominal;

    //         // Memperbarui employee_nominal dan company_nominal
    //         $bpjsCalculations = BpjsCalculation::all();
    //         foreach ($bpjsCalculations as $bpjsCalculation) {
    //             $baseWages = $bpjsCalculation->getBaseWages();

    //             $calculateNominal = function ($percent, $baseWage) {
    //                 if ($percent == 0.00) {
    //                     return 0;
    //                 }

    //                 $nominal = floor($baseWage * $percent);
    //                 $last3Digits = substr($nominal, -3);
    //                 $roundedValue = round($last3Digits / 100) * 100;
    //                 $nominal = $nominal - $last3Digits + $roundedValue;

    //                 if (substr($nominal, -3) === "000") {
    //                     $nominal = rtrim($nominal, "0");
    //                 } else {
    //                     $nominal = rtrim($nominal, "0") . "0";
    //                 }

    //                 return $nominal;
    //             };

    //             if ($baseWages['base_wages_bpjs_nominal_1'] == $oldNominal) {
    //                 $bpjsCalculation->company_nominal = $calculateNominal($bpjsCalculation->company_percent, $newNominal);
    //             }

    //             if ($baseWages['base_wages_bpjs_nominal_2'] == $oldNominal) {
    //                 $bpjsCalculation->employee_nominal = $calculateNominal($bpjsCalculation->employee_percent, $newNominal);
    //             }

    //             $bpjsCalculation->save();
    //         }
    //     });
    // }



}
