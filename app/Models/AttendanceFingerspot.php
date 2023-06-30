<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceFingerspot extends Model
{
    use HasFactory;

    protected $fillable = [
        'pin',
        'cloud_id',
        'scan_date',
        'verify',
        'status_scan',
    ];
}
