<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class SalaryAdvance extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'employee_name', 'creator_name', 'loan_amount_readable', 'position_name',
        'monthly_deduction_readable', 'employee_name_and_position',
        // 'remaining_debt_readable',
        'month_loan_complite_readable', 'remaining_debt',
        // 'status_readable', 'status_color',
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

    // public function salaryAdvanceLasts()
    // {
    //     return $this->hasMany(SalaryAdvance::class, "employee_id", "employee_id")
    //         ->where('created_at', '<', $this->created_at);
    // }

    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }

    public function foreman()
    {
        return $this->belongsTo(Employee::class, "foreman_id", "id");
    }

    public function creator()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function approvalAgreement()
    {
        return $this->belongsTo(ApprovalAgreement::class, "id", "model_id")
            ->where("name_model", "App\Models\SalaryAdvance");
    }

    public function getEmployeeNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->name;
        }
    }

    public function getEmployeeNameAndPositionAttribute()
    {
        if ($this->employee) {
            return $this->employee->name_and_position;
        }
    }

    public function getCreatorNameAttribute()
    {
        if ($this->creator) {
            return $this->creator->name;
        }
    }


    public function getLoanAmountReadableAttribute()
    {
        $loanAmount = number_format($this->loan_amount, 0, ',', '.');
        return "Rp {$loanAmount}";
    }

    public function getPositionNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->position_name;
        } else {
            return null;
        }
    }

    public function getMonthlyDeductionReadAbleAttribute()
    {
        $loanAmount = number_format($this->monthly_deduction, 0, ',', '.');
        return "Rp {$loanAmount}";
    }

    public function getMonthLoanCompliteReadableAttribute()
    {
        return Carbon::parse($this->month_loan_complite)->locale('id')->isoFormat("MMMM YYYY");
    }

    public function getRemainingDebtAttribute()
    {
        $monthNow = Carbon::now()->floorMonth();
        $monthEnd = Carbon::parse($this->month_loan_complite)->floorMonth();
        $getDiffMonth = $monthNow->diffInMonths($monthEnd, false) + 1;

        if ($monthNow->format("Y-m-d") <= $monthEnd) {
            // $remainingDebt = $this->loan_mount / $getDiffMonth;
            $remainingDebt = $this->monthly_deduction * $getDiffMonth;
            $remainingDebt =  number_format($remainingDebt, 0, ',', '.');
            // return $remainingDebt;
            return "Rp {$remainingDebt}";
            // return "{$monthNow} {$monthEnd} {$getDiffMonth}";
        } else {
            return "Rp. 0";
        }
    }

    // public function getRemainingDebtReadableAttribute()
    // {
    //     $salaryAdvanceLasts = SalaryAdvance::where("employee_id", $this->employee_id)
    //         ->where("created_at", "<", $this->created_at);
    //     $checkLastData = $salaryAdvanceLasts->count();

    //     if ($checkLastData > 0) {
    //         $remainingDebt = $salaryAdvanceLasts->sum("remaining_debt");
    //         $remainingDebt =  number_format($remainingDebt, 0, ',', '.');
    //         return $remainingDebt;
    //     } else {
    //         return "Rp. 0";
    //     }
    // }

    // public function getStatusReadableAttribute()
    // {
    //     $getStatus = Config::get("library.status.{$this->status}");

    //     return $getStatus["readable"];
    // }

    // public function getStatusColorAttribute()
    // {
    //     $getStatus = Config::get("library.status.{$this->status}");

    //     return $getStatus["color"];
    // }
}
