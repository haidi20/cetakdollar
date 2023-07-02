<?php

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => ['jwt.verify'], 'namespace' => 'API'], function () {

// });
Route::get('login', [UserController::class, 'authenticate']);

Route::prefix("v1")->name("api.")->middleware("jwt.verify")->group(function () {
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('', [HistoryController::class, "fetchData"])->name('fetchData');
        Route::post('', [HistoryController::class, "store"])->name('store');
    });
});
