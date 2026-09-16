<?php

namespace App\Providers;

use App\Models\HotelSetting;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require_once app_path('Helpers/LogActivity.php');

        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $settings = HotelSetting::first(); 
            $view->with('settings', $settings);
        });
    }
}