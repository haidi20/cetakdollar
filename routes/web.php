<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\UserController;
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
    Route::prefix('user')->name("user.")->group(function () {
        Route::get('', [UserController::class, "index"])->name("index");
        Route::post('store', [UserController::class, "store"])->name("store");
        Route::delete('delete', [UserController::class, "destroy"])->name("delete");
    });
    Route::prefix('user-account')->name("userAccount.")->group(function () {
        Route::get('', [UserAccountController::class, "index"])->name("index");
        Route::post('store', [UserAccountController::class, "store"])->name("store");
        Route::delete('delete', [UserAccountController::class, "destroy"])->name("delete");
    });
    Route::prefix('role')->name("role.")->group(function () {
        Route::get('', [RoleController::class, "index"])->name("index");
        Route::post('store', [RoleController::class, "store"])->name("store");
        Route::delete('delete', [RoleController::class, "destroy"])->name("delete");
    });
    Route::prefix('role-permission/{roleId}')->name("rolePermission.")->group(function () {
        Route::get('', [RolePermissionController::class, "index"])->name("index");
        Route::get('show', [RolePermissionController::class, "show"])->name("show");
        Route::post('store', [RolePermissionController::class, "store"])->name("store");
    });
    Route::prefix("permission")->name("permission.")->group(function () {
        Route::get('{featureId}', [PermissionController::class, "index"])->name("index");
        Route::post('store', [PermissionController::class, "store"])->name("store");
        Route::delete('delete', [PermissionController::class, "destroy"])->name("delete");
    });
    Route::prefix('feature')->name("feature.")->group(function () {
        Route::get('', [FeatureController::class, "index"])->name("index");
        Route::post('store', [FeatureController::class, "store"])->name("store");
        Route::delete('delete', [FeatureController::class, "destroy"])->name("delete");
    });
    Route::prefix("log")->name("log.")->group(function () {
        Route::get('', [LogController::class, "index"])->name("index");
    });
});


// Route::get('/home', [HomeController::class, 'index'])->name('home');
