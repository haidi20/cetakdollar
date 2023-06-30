<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class salaryAdjustment extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        "type_time_readable", "type_adjustment_name", "amount_readable",
    ];
    protected $fillable = [
        'name',
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

    public function salaryAdjustmentDetails()
    {
        return $this->hasMany(salaryAdjustmentDetail::class, "salary_adjustment_id", "id")
            ->whereHas('salaryAdjustment', function ($query) {
                $query->where('employee_base', 'choose_employee');
            });
    }

    public function getAmountReadableAttribute()
    {
        $amount = number_format($this->amount, 0, ',', '.');
        return "{$amount}";
    }

    public function getTypeTimeReadableAttribute()
    {
        $typeTime = Config::get("library.type_times.{$this->type_time}");

        return $typeTime;
    }

    public function getTypeAdjustmentNameAttribute()
    {
        $typeAdjustment = Config::get("library.type_adjustments.{$this->type_adjustment}");

        return $typeAdjustment;
    }
}
