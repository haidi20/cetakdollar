<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // return auth()->user()->getAllPermissions();

        $vue = true;
        return view("pages.dashboard.index", compact("vue"));
    }
}
