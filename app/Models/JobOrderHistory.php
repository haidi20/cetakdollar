<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOrderHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_order_id',
        'project_id',
        'job_id',
        'job_level',
        'job_note',
        'datetime_start',
        'datetime_end',
        'datetime_estimation_end',
        'estimation',
        'time_type',
        'category',
        'status',
        'status_note',
        'note',
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

    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class, "job_order_id", "id");
    }
}
