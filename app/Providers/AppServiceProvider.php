<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (app()->environment('production')) {
            $this->app['url']->forceScheme('https');
        }
    }

    public function boot(): void
    {
        //
    }
}
