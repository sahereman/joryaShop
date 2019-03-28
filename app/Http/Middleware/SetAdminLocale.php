<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetAdminLocale
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $defaultLocale = 'zh-CN';

        App::setLocale($defaultLocale);

        return $next($request);
    }
}
