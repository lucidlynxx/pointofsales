<?php

namespace App\Providers;

use Carbon\Carbon;
use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

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
        config(['app.locale' => 'en']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/filament.css');
            Filament::registerNavigationGroups([
                'Shop',
                'Transaction',
                'Role and Permission',
                'Account',
            ]);
        });
    }
}
