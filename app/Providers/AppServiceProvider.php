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
        if (app()->environment('production')) {
            request()->server->set('HTTPS', 'on');
            request()->setScheme('https');
            $this->app['url']->forceScheme('https');
        }
    }
}
