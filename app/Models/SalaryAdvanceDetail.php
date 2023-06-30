<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class SalaryAdvanceDetail extends Model
{
    use HasFactory, SoftDeletes;


    protected $guarded = [];
 
    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }

    public function foreman()
    {
        return $this->belongsTo(Employee::class, "foreman_id", "id");
    }

    public function creator()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    
}
