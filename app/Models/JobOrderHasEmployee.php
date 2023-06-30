<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOrderHasEmployee extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        "employee_name", "position_name",
        "project_name", "creator_name", "status_data",
        "status_color", "status_readable", 'status_clone',
        "is_add_information",
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

    public function jobOrder()
    {
        return $this->belongsTo(JobOrderSimple::class, "job_order_id", "id");
    }

    // untuk membedakan data dari server dan web
    public function getStatusDataAttribute()
    {
        return "old";
    }

    public function getStatusCloneAttribute()
    {
        return $this->status;
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

    public function getProjectNameAttribute()
    {
        if ($this->jobOrder) {
            return $this->jobOrder->project_name;
        }
    }

    public function getCreatorNameAttribute()
    {
        if ($this->jobOrder) {
            return $this->jobOrder->creator_name;
        }
    }

    // data dari server tidak perlu tampilkan informasi lebih lanjut
    public function getIsAddInformationAttribute()
    {
        return false;
    }
}
