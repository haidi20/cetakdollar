<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobOrderCategoryController extends Controller
{
    public function index()
    {
        $job_category = [
            (object)[
                "id" => 1,
                "nama_job_category" => "Reguler",
                "keterangan" => "Job Reguler",
            ]
        ];

        return view("pages.master.job.index", compact("job_category"));
    }
}
