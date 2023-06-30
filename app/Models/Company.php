<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = Schema::getColumnListing($this->getTable());
    }

    // public function company()
    // {
    //     return $this->belongsTo(Company::class, "company_id", "id");
    // }

    // public function getCompanyDepartmenNameAttribute()
    // {
    //     if ($this->departmen) {
    //         return $this->departmen->name;
    //     } else {
    //         return "Data Departmen Masih Kosong";
    //     }
    // }
}
