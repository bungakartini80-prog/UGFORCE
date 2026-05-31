<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Force HTTPS on Vercel (production) to prevent "not secure" form warnings
        if ($this->app->environment('production') || isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL'])) {
            URL::forceScheme('https');
        }
    }
}
