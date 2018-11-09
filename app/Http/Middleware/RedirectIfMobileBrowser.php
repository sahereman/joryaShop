<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfMobileBrowser
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($request->routeIs('root'))
        {
            if (\Browser::isMobile())
            {
                return redirect()->route('mobile.root');
            }
        }


        return $next($request);
    }
}
