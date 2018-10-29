<?php

namespace App\Providers;

use App\Models\CountryCode;
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
        View::composer('layouts._footer', function ($view) {
            $country_codes = CountryCode::all();
            $view->with('country_codes', $country_codes);
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
