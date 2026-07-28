<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\URL::forceRootUrl('https://proud-growth-production-878a.up.railway.app');
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
