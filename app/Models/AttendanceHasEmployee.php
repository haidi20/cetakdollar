<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceHasEmployee extends Model
{
    use HasFactory;

    // protected $table = 'attendance_has_employees';

    protected $appends = [
        "duration_work_readable",  "duration_rest_readable",  "duration_overtime_readable",
    ];

    protected $guarded = [];

    public function getDurationWorkReadableAttribute()
    {
        $result = null;

        if ($this->hour_start != null && $this->hour_end != null) {
            $result = dateTimeDuration($this->hour_start, $this->hour_end, true);
        }

        return $result;
    }

    public function getDurationRestReadableAttribute()
    {
        $result = null;

        if ($this->hour_rest_start != null && $this->hour_rest_end != null) {
            $result = dateTimeDuration($this->hour_rest_start, $this->hour_rest_end, true);
        }

        return $result;
    }

    public function getDurationOvertimeReadableAttribute()
    {
        $result = null;

        if ($this->hour_overtime_start != null && $this->hour_overtime_end != null) {
            $result = dateTimeDuration($this->hour_overtime_start, $this->hour_overtime_end, true);
        }

        return $result;
    }
}
