<?php

namespace App\Providers;

use App\Models\Config;
use App\Models\CountryCode;
use App\Models\ExchangeRate;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\RefundReason;
use App\Models\ShipmentCompany;
use App\Models\User;
use App\Observers\ConfigObserver;
use App\Observers\CountryCodeObserver;
use App\Observers\ExchangeRateObserver;
use App\Observers\MenuObserver;
use App\Observers\OrderObserver;
use App\Observers\OrderItemObserver;
use App\Observers\ProductObserver;
use App\Observers\RefundReasonObserver;
use App\Observers\ShipmentCompanyObserver;
use App\Observers\UserObserver;
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
        CountryCode::observe(CountryCodeObserver::class);
        ExchangeRate::observe(ExchangeRateObserver::class);
        Menu::observe(MenuObserver::class);
        Order::observe(OrderObserver::class);
        OrderItem::observe(OrderItemObserver::class);
        Product::observe(ProductObserver::class);
        RefundReason::observe(RefundReasonObserver::class);
        ShipmentCompany::observe(ShipmentCompanyObserver::class);
        User::observe(UserObserver::class);

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
