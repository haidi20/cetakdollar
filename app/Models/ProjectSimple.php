<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class ProjectSimple extends Model
{
    use HasFactory, SoftDeletes;

    // untuk kebutuhan di job order
    // menggunakan model project.php sangat berat load datanya
    // ada kemungkinan pengaruh pada relation table

    protected $table = "projects";
}
