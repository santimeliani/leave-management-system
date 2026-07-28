<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttpsScheme
{
    public function handle(Request $request, Closure $next)
    {
        $request->server->set('HTTPS', 'on');
        $request->setScheme('https');

        return $next($request);
    }
}
