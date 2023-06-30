<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = Schema::getColumnListing($this->getTable());
    }

    // public function getAutoNumberOptions()
    // {
    //     return [
    //         'job_code' => [
    //             'format' => function () {
    //                 return 'MRD?';
    //             },
    //             'length' => 5 // Jumlah digit yang akan digunakan sebagai nomor urut
    //         ],
    //     ];
    // }
}
