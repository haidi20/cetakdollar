<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalLevelDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        "user_name_position",
    ];

    protected $fillable = [
        "user_id",
        "approval_level_id",
        "status",
        "level",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    public function user()
    {
        return $this->belongsTo("App\Models\User", "user_id", "id")->withTrashed();
    }

    public function scopeByApprovalLevelId($query, $approval_level_id)
    {
        $query->where("approval_level_id", $approval_level_id);
    }

    public function getUserNamePositionAttribute()
    {
        if ($this->user) {
            return "{$this->user->name} - {$this->user->group_name}";
        }
    }
}
