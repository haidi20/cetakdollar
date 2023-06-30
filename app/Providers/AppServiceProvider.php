<?php

namespace App\Providers;

use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Blade;




class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Kuala_Lumpur');
        setlocale(LC_ALL, 'IND');

        // Define the isActive function
        Route::macro('isActive', function ($routeName) {
            return Str::startsWith(Route::currentRouteName(), $routeName) ? 'active' : '';
        });
    }
}
