<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        "date_read_able",
    ];

    protected $fillable = [
        "name",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    public function detail()
    {
        return $this->hasMany(ApprovalLevelDetail::class, "approval_level_id", "id");
    }

    public function agreement()
    {
        return $this->hasMany(ApprovalAgreement::class, "approval_level_id", "id")->withTrashed();
    }

    public function getDateReadAbleAttribute()
    {
        return dateReadable($this->created_at);
    }
}
