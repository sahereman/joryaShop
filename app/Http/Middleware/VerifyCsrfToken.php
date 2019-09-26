<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // 只有POST|PUT|PATCH|DELETE请求才需要作此忽略设置，GET请求不需要
        'payments/*/alipay/notify',
        'payments/*/wechat/notify',
        'payments/*/paypal/notify',
        'products/*/search_by_sku_attr',
    ];
}
