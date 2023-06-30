<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobStatusHasParent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'parent_model',
        'job_order_id',
        'employee_id',
        'status',
        'datetime_start',
        'datetime_end',
        'note_start',
        'note_end',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        "job_name", "employee_name", "position_name",
        "datetime_start_readable", "datetime_end_readable",
        "duration", "duration_readable",
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
        return $this->belongsTo(JobOrder::class, "job_order_id", "id");
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

    public function getJobNameAttribute()
    {
        if ($this->jobOrder) {
            return $this->jobOrder->job_name;
        }
    }

    public function getDatetimeStartReadableAttribute()
    {
        return Carbon::parse($this->datetime_start)->locale('id')->isoFormat("dddd, D MMMM YYYY HH:mm");
    }

    public function getDatetimeEndReadableAttribute()
    {
        return Carbon::parse($this->datetime_end)->locale('id')->isoFormat("dddd, D MMMM YYYY HH:mm");
    }

    public function getDurationAttribute()
    {
        $datetimeStart = Carbon::parse($this->datetime_start);
        $datetimeEnd = Carbon::parse($this->datetime_end);

        // return $datetimeStart->diffAsCarbonInterval($datetimeEnd)->format("%H:%I");
        return $datetimeStart->diffInMinutes($datetimeEnd);
    }

    public function getDurationReadableAttribute()
    {
        $datetimeStart = Carbon::parse($this->datetime_start);
        $datetimeEnd = Carbon::parse($this->datetime_end);

        $duration = $datetimeStart->diffAsCarbonInterval($datetimeEnd);

        $hours = $duration->hours;
        $minutes = $duration->minutes;

        $durationString = '';

        if ($hours > 0) {
            $durationString .= $hours . ' jam ';
        }

        if ($minutes > 0) {
            $durationString .= $minutes . ' menit';
        }

        return $durationString;
    }
}
