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
        $this->app['url']->forceScheme('https');
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
