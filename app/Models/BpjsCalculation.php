<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class BpjsCalculation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'company_percent', 'employee_percent', 'company_nominal', 'employee_nominal'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = Schema::getColumnListing($this->getTable());
    }

    public function getBaseWages()
    {
        $baseWagesBPJSTK = $this->getBaseWageById(1);
        $baseWagesBPJSKES = $this->getBaseWageById(2);

        return [
            'base_wages_bpjs_nominal_1' => $baseWagesBPJSTK->nominal,
            'base_wages_bpjs_nominal_2' => $baseWagesBPJSKES->nominal,
        ];
    }

    private function getBaseWageById($id)
    {
        return DB::table('base_wages_bpjs')
        ->where('id', $id)
            ->first();

    }
}
