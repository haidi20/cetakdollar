<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class ApprovalAgreement extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        "user_create_approval",
        "status_approval_read_able",
        "description_status_approval",
        "label_status_approval",
        "employee_name",
        "user_name",
        "position_name",
        "date_read_able",
    ];

    protected $fillable = [
        "approval_level_id",
        "user_id",
        "model_id",
        "name_model",
        "status_approval",
        "level_approval",
    ];

    public function otherModel()
    {
        return $this->belongsTo($this->name_model, "model_id", "id");
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, "user_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(UserSimple::class, "user_id", "id");
    }

    public function userBehalf()
    {
        return $this->belongsTo(UserSimple::class, "user_behalf_id", "id");
    }

    public function getEmployeeNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->name_and_position;
        }
    }

    public function scopeByApprovalLevelId($query, $approval_level_id)
    {
        return $query->where("approval_level_id", $approval_level_id);
    }

    public function scopeByModel($query, $modelId, $nameModel)
    {
        return $query->where(["model_id" => $modelId, "name_model" => $nameModel]);
    }

    public function getStatusApprovalReadAbleAttribute()
    {
        $statusApprovalLibrary = Config::get("library.status.{$this->status_approval}");

        return $statusApprovalLibrary["readable"];
    }

    public function getUserCreateApprovalAttribute()
    {
        if ($this->otherModel) {
            return $this->otherModel->user_id;
        }
    }

    // nama user yang approval
    public function getUserNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
    }

    // departemen user yang approval
    public function getPositionNameAttribute()
    {
        if ($this->user) {
            return $this->user->position_name;
        }
    }

    public function getLabelStatusApprovalAttribute()
    {
        $statusApprovalLibrary = Config::get("library.status.{$this->status_approval}");

        return view("pages.setting.renders.badge", ["type" => $statusApprovalLibrary["color"], "message" => $this->status_approval_read_able])->render();
    }

    public function getDescriptionStatusApprovalAttribute()
    {
        $sentanceBehalf = "";

        if (request("user_id") == null) {
            $userId = auth()->user()->id;
        } else {
            $userId = request("user_id");
        }

        if ($this->user_id == $userId) {
            $by = "Anda";
        } else {

            if ($this->user) {
                $by = "{$this->user->name} - {$this->user->group_name}";
            } else {
                $by = " - ";
            }
        }

        if ($this->userBehalf) {
            $nameUserBehalf = $this->userBehalf->name;
            $sentanceBehalf = "dan di wakilkan oleh {$nameUserBehalf}";
            $newLine = "<br>";

            return "{$this->status_approval_read_able} {$newLine}" . $sentanceBehalf;
        } else {
            $newLine = "";

            return "{$this->status_approval_read_able} oleh {$by} {$newLine}";
        }

        // return "{$this->status_approval_read_able} oleh {$by} {$newLine}" . $sentanceBehalf;
    }

    public function getDateReadAbleAttribute()
    {
        return Carbon::parse($this->created_at)->locale('id')->isoFormat("dddd, D MMMM YYYY");
    }
}
