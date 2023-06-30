<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class JobOrderSimple extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "job_orders";
    protected $fillable = [];
    // status_clone untuk bisa membandingkan status setelah perubahan dan yang di database
    protected $appends = [
        "project_name", "creator_name",
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

    public function project()
    {
        return $this->belongsTo(ProjectSimple::class, "project_id", "id");
    }

    public function creator()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function getProjectNameAttribute()
    {
        if ($this->project) {
            return $this->project->name;
        }
    }

    public function getCreatorNameAttribute()
    {
        if ($this->creator) {
            return $this->creator->name;
        }
    }
}
