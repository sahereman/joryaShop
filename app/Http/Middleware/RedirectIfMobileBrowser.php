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
        if ($request->routeIs('root')) {
            if (\Browser::isMobile()) {
                return redirect()->route('mobile.root');
            }
        }

        if ($request->routeIs('product_categories.index')) {
            if (\Browser::isMobile()) {
                return redirect()->route('mobile.product_categories.index');
            }
        }

        if ($request->routeIs('products.show')) {
            if (\Browser::isMobile()) {
                return redirect()->to('mobile' . $request->getRequestUri());
                /*return redirect()->route('mobile.products.show', [
                    'product' => $request->segment(2),
                ]);*/
            }
        }

        if ($request->routeIs('products.search')) {
            if (\Browser::isMobile()) {
                return redirect()->to('mobile' . $request->getRequestUri());
            }
        }

        return $next($request);
    }
}
