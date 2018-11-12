<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\CountryCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Using Closure based composers...
        View::composer([
            'layouts._header',
        ], function ($view) {
            $cart_count = false;
            if (Auth::check()) {
                $cart_count = Cart::where('user_id', Auth::id())->count();
            }
            $view->with('cart_count', $cart_count);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
