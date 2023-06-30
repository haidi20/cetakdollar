<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Vacation extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'employee_name', 'creator_name', 'date_start_readable', 'date_end_readable',
        'duration_readable', 'position_name',
    ];

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = Schema::getColumnListing($this->getTable());
    }

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

    public function creator()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function getEmployeeNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->name;
        }
    }

    public function getCreatorNameAttribute()
    {
        if ($this->creator) {
            return $this->creator->name;
        }
    }

    public function getDateStartReadableAttribute()
    {
        return Carbon::parse($this->date_start)->isoFormat("dddd, D MMMM YYYY");
    }

    public function getDateEndReadableAttribute()
    {
        return Carbon::parse($this->date_end)->isoFormat("dddd, D MMMM YYYY");
    }

    public function getDurationReadableAttribute()
    {
        $dateStart = Carbon::parse($this->date_start);
        $dateEnd = Carbon::parse($this->date_end);
        return $dateStart->diffInDays($dateEnd, false) . " Hari";
    }

    public function getPositionNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->position_name;
        } else {
            return null;
        }
    }
}
