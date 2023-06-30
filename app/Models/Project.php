<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [];
    protected $appends = [
        "location_name", "job_order_total", "date_end_readable",
        "job_order_finish_total",
    ];

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

    public function barge()
    {
        return $this->belongsTo(Barge::class, "barge_id", "id");
    }

    // public function company()
    // {
    //     return $this->belongsTo(Company::class, "company_id", "id");
    // }

    public function location()
    {
        return $this->belongsTo(Location::class, "location_id", "id");
    }

    public function job()
    {
        return $this->belongsTo(Job::class, "job_id", "id");
    }

    public function foreman()
    {
        return $this->belongsTo(Employee::class, "foreman_id", "id");
    }

    public function jobOrders()
    {
        return $this->hasMany(JobOrder::class, "project_id", "id");
    }

    public function contractors()
    {
        return $this->hasMany(ContractorHasParent::class, "parent_id", "id")
            ->where("parent_model", "App\Models\Project")->orderBy("created_at", "desc");
    }

    public function ordinarySeamans()
    {
        return $this->hasMany(OrdinarySeamanHasParent::class, "parent_id", "id")
            ->where("parent_model", "App\Models\Project")->orderBy("created_at", "desc");
    }

    public function scopeJobOrderFinish($query)
    {
        return $query->whereHas("jobOrders", function ($query) {
            $query->where("status", "finish");
        });
    }

    public function getLocationNameAttribute()
    {
        if ($this->location) {
            return $this->location->name;
        } else {
            return null;
        }
    }

    public function getJobOrderFinishTotalAttribute()
    {
        if ($this->jobOrders) {
            return $this->jobOrders->where("status", "finish")->count();
        }
    }

    public function getJobOrderTotalAttribute()
    {
        if ($this->jobOrders) {
            return count($this->jobOrders);
        }
    }

    public function getDateEndReadableAttribute()
    {
        if ($this->date_end != null) {
            return dateReadable($this->date_end);
        } else {
            return null;
        }
    }
}
