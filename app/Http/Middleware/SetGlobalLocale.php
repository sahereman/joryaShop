<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetGlobalLocale
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $defaultLocale = 'zh-CN';
        $defaultLocale = 'en';

        if (!$request->session()->has('GlobalLocale'))
        {
            $request->session()->put('GlobalLocale', $defaultLocale);
        }

        $locale = in_array($request->session()->get('GlobalLocale', $defaultLocale), ['zh-CN', 'en']) ?
            $request->session()->get('GlobalLocale', $defaultLocale) : $defaultLocale;

        App::setLocale($locale);

        return $next($request);
    }
}
