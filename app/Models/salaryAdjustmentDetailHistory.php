<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class salaryAdjustmentDetailHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        "employee_name", "position_name",
    ];
    protected $fillable = [
        'salary_adjustment_detail_id',
        'salary_adjustment_id',
        'employee_id',
        'amount',
        'type_time',
        'month_start',
        'month_end',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = request("user_id");
            $model->updated_by = NULL;
        });

        static::updating(function ($model) {
            $model->updated_by = request("user_id");
        });
    }

    public function SalaryAdjustment()
    {
        return $this->belongsTo(salaryAdjustment::class, "salary_adjustment_id", "id");
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }

    public function getEmployeeNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->name;
        }
    }

    public function getPositionNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->position_name;
        }
    }
}
