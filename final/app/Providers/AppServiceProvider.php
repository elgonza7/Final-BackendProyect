<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
        // Force HTTPS in Codespaces even if local
        if (str_contains(request()->url(), 'github.dev') || str_contains(request()->url(), 'gitpod.io')) {
             URL::forceScheme('https');
        }
    }
}
