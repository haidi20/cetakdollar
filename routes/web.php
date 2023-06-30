<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

// Guest-only routes
Route::group(['middleware' => 'guest'], function () {
    // Route::get('/', 'Auth\RegisterController@showRegistrationForm');
    Route::get('/', [LoginController::class, "showLoginForm"]);
});

//
Route::group(['middleware' => 'auth'], function () {
    // Route::get('/', 'Auth\RegisterController@showRegistrationForm');
    Route::prefix("dashboard")->name("dashboard.")->group(function () {
        Route::get('', [DashboardController::class, "index"])->name("index");
    });
});


// Route::get('/home', [HomeController::class, 'index'])->name('home');
