<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Roster extends Model
{
    use HasFactory, SoftDeletes;

    // protected $appends = ["roster_status_initial", "roster_status_color"];
    protected $fillable = [
        'employee_id',
        'day_off_one',
        'day_off_two',
        'date_vacation_start',
        'date_vacation_end',
        'month',
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
}
