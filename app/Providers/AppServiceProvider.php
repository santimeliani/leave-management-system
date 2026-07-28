<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->extend('url', function ($url, $app) {
            $url->forceScheme('https');
            return $url;
        });
    }
}
