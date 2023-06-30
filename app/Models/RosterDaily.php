<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class RosterDaily extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ["roster_status_initial", "roster_status_color"];
    protected $fillable = [
        'employee_id',
        'position_id',
        'date',
        'roster_status_id',
        'created_by',
        'updated_by',
        'deleted_by'
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

    public function rosterStatus()
    {
        return $this->belongsTo(RosterStatus::class, "roster_status_id", "id");
    }

    public function getRosterStatusInitialAttribute()
    {
        if ($this->rosterStatus) {
            return $this->rosterStatus->initial;
        }
    }

    public function getRosterStatusColorAttribute()
    {
        if ($this->rosterStatus) {
            return $this->rosterStatus->color;
        }
    }
}
