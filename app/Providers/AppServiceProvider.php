<?php

namespace App\Providers;

use App\Models\Config;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Observers\ConfigObserver;
use App\Observers\OrderObserver;
use App\Observers\OrderItemObserver;
use App\Observers\ProductsObserver;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        Config::observe(ConfigObserver::class);

        Product::observe(ProductsObserver::class);

        Order::observe(OrderObserver::class);
        OrderItem::observe(OrderItemObserver::class);

        // Carbon 中文化配置
        Carbon::setLocale('zh');
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        //
    }
}
