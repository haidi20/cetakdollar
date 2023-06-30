<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ["company_name", "barge_name"];
    protected $fillable = [
        'name',
        'company_id',
        'barge_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = Schema::getColumnListing($this->getTable());
    }

    public function company()
    {
        return $this->belongsTo(Company::class, "company_id", "id");
    }

    public function barge()
    {
        return $this->belongsTo(Barge::class, "barge_id", "id");
    }

    public function getCompanyNameAttribute()
    {
        if ($this->company) {
            return $this->company->name;
        } else {
            return "Data Perusahaan Masih Kosong";
        }
    }
    public function getBargeNameAttribute()
    {
        if ($this->barge) {
            return $this->barge->name;
        } else {
            return "Data Kapal Masih Kosong";
        }
    }
}
