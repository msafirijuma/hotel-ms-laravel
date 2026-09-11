<?php

namespace App\Providers;

use App\Models\HotelSetting;

use Illuminate\Support\ServiceProvider;
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

        View::composer('*', function ($view) {
            $settings = HotelSetting::first(); // au HotelSetting::find(1);
            $view->with('settings', $settings);
        });
    }
}