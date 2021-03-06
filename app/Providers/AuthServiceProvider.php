<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Cart' => 'App\Policies\CartPolicy',
        'App\Models\Order' => 'App\Policies\OrderPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\UserAddress' => 'App\Policies\UserAddressPolicy',
        'App\Models\UserFavourite' => 'App\Policies\UserFavouritePolicy',
        'App\Models\UserHistory' => 'App\Policies\UserHistoryPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
