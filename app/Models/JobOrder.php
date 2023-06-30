<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class JobOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [];
    // status_clone untuk bisa membandingkan status setelah perubahan dan yang di database
    protected $appends = [
        "status_color", "status_readable", 'status_clone',
        "project_name", "job_name", "job_code", "hour_start", "category_name",
        "creator_name", "creator_group_name",
        "employee_total", "employee_active_total", "assessment_count", "assessment_total",
        "datetime_estimation_end_readable", "datetime_end_readable", "datetime_start_readable",
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

    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }

    public function project()
    {
        return $this->belongsTo(ProjectSimple::class, "project_id", "id");
    }

    public function job()
    {
        return $this->belongsTo(Job::class, "job_id", "id");
    }

    public function creator()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function jobOrderHasEmployees()
    {
        return $this->hasMany(JobOrderHasEmployee::class, "job_order_id", "id");
    }

    public function jobStatusHasParent()
    {
        return $this->hasMany(JobStatusHasParent::class, "job_order_id", "id");
    }

    public function jobOrderAssessments()
    {
        return $this->hasMany(JobOrderAssessment::class, "job_order_id", "id");
    }

    public function getStatusCloneAttribute()
    {
        return $this->status;
    }

    public function getCreatorNameAttribute()
    {
        if ($this->creator) {
            return $this->creator->name;
        }
    }

    public function getCreatorGroupNameAttribute()
    {
        if ($this->creator) {
            return $this->creator->group_name;
        }
    }

    public function getHourStartAttribute()
    {
        return Carbon::parse($this->datetime_start)->format("H:i");
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
        if ($this->project) {
            return $this->project->name;
        }
    }

    public function getJobNameAttribute()
    {
        if ($this->job) {
            return $this->job->name;
        } else if ($this->job_another_name != null) {
            return $this->job_another_name;
        }
    }

    public function getJobCodeAttribute()
    {
        if ($this->job) {
            return $this->job->code;
        }
    }

    // datetime_estimation_end_readable
    public function getDatetimeEstimationEndReadableAttribute()
    {
        $formatHour = null;

        if ($this->time_type != "days") {
            $formatHour = "HH:mm";
        }

        return Carbon::parse($this->datetime_estimation_end)->locale('id')->isoFormat("dddd, D MMMM YYYY {$formatHour}");
    }

    public function getDatetimeEndReadableAttribute()
    {
        return Carbon::parse($this->datetime_end)->locale('id')->isoFormat("dddd, D MMMM YYYY HH:mm");
    }

    public function getDatetimeStartReadableAttribute()
    {
        return Carbon::parse($this->datetime_start)->locale('id')->isoFormat("dddd, D MMMM YYYY HH:mm");
    }

    public function getEmployeeTotalAttribute()
    {
        return $this->jobOrderHasEmployees->count();
    }

    public function getEmployeeActiveTotalAttribute()
    {
        return $this->jobOrderHasEmployees
            ->whereIn('status', ['active', 'finish', 'overtime', 'correction'])
            ->count();
    }

    public function getAssessmentCountAttribute()
    {
        return  $this->jobOrderAssessments->count();
    }

    public function getAssessmentTotalAttribute()
    {
        $count =  $this->jobOrderAssessments->count();

        return $count <= 2 ? 2 : $count;
    }

    public function getCategoryNameAttribute()
    {
        $categoryName = Config::get("library.category.{$this->category}");

        return $categoryName;
    }
}
