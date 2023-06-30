<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ["departmen_name"];
    protected $fillable = [
        'name',
        'description',
        'minimum_employee',
        'departmen_id',
        'created_by',
        'updated_by',
        'deleted_by'
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
            $model->created_by = auth()->user()->id;
            $model->updated_by = NULL;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }

    public function departmen()
    {
        return $this->belongsTo(Departmen::class, "departmen_id", "id");
    }

    public function getDepartmenNameAttribute()
    {
        if ($this->departmen) {
            return $this->departmen->name;
        } else {
            return "Data Departmen Masih Kosong";
        }
    }
}
