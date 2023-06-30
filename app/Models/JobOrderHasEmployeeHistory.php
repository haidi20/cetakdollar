<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class JobOrderHasEmployeeHistory extends Model
{
    use HasFactory;

    protected $appends = [
        "employee_name", "position_name",
        "status_color", "status_readable",
    ];

    protected $fillable = [
        'employee_id',
        'job_order_id',
        'status',
        'datetime_start',
        'datetime_end',
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

    public function getStatusColorAttribute()
    {
        $statusApprovalLibrary = Config::get("library.status.{$this->status}");

        return $statusApprovalLibrary["color"];
    }

    public function getStatusReadableAttribute()
    {
        $statusApprovalLibrary = Config::get("library.status.{$this->status}");

        return $statusApprovalLibrary["short_readable"];
    }
}
