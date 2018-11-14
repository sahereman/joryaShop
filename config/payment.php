<?php

return [
    // Alipay 支付
    'alipay' => [
        // 支付宝分配的 APPID
        'app_id' => env('ALI_APP_ID', 'app_id'),

        // 支付宝异步通知地址
        // 'notify_url' => route('payments.alipay.notify'),

        // 支付成功后同步通知地址
        // 'return_url' => route('payments.return'),

        // 阿里公共密钥，验证签名时使用
        'ali_public_key' => env('ALI_PUBLIC_KEY', 'ali_public_key'),

        // 自己的私钥，签名时使用
        'private_key' => env('ALI_PRIVATE_KEY', 'private_key'),

        // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
        'log' => [
            'file' => storage_path('logs/alipay.log'),
            //  'level' => 'debug',
            //  'type' => 'single', // optional, 可选 daily.
            //  'max_file' => 30,
            'level' => 'debug',
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30,
        ],

        // optional，设置此参数，将进入沙箱模式
        // 'mode' => 'dev',
        // 'mode' => 'normal',
        'mode' => 'dev',

        // TODO ... (for production)
        /*
        'log' => [
            'file' => storage_path('logs/alipay.log'),
            'level' => 'info',
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 30,
        ],
        'mode' => 'normal',
        */

        'http' => [ // optional
            'timeout' => 10.0,
            'connect_timeout' => 10.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
    ],

    // Wechat 支付
    'wechat' => [
        // 公众号[MP] AppId
        'app_id' => env('WECHAT_MP_APP_ID', 'app_id'),

        // 公众号[MP] AppSecret
        'app_secret' => env('WECHAT_MP_APP_SECRET', 'app_secret'),

        // 小程序 APPID
        'miniapp_id' => env('WECHAT_MINIAPP_ID', 'miniapp_id'),

        // APP 引用的 appid
        'appid' => env('WECHAT_APPID', 'appid'),

        // 微信支付分配的微信商户号
        'mch_id' => env('WECHAT_MCH_ID', 'mch_id'),

        // 微信支付异步通知地址
        // 'notify_url' => route('payments.wechat.notify'),

        // 微信支付签名秘钥
        'key' => env('WECHAT_KEY', 'key'),

        // 客户端证书路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
        'cert_client' => env('WECHAT_API_CLIENT_CERT_PATH', 'cert_client'),

        // 客户端秘钥路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
        'cert_key' => env('WECHAT_API_CLIENT_KEY_PATH', 'cert_key'),

        // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
        'log' => [
            'file' => storage_path('logs/wechat.log'),
            //  'level' => 'debug',
            //  'type' => 'single', // optional, 可选 daily.
            //  'max_file' => 30,
            'level' => 'debug',
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30,
        ],

        // optional
        // 'dev' 时为沙箱模式
        // 'hk' 时为东南亚节点
        // 'mode' => 'dev',
        // 'mode' => 'dev', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
        // 'mode' => 'normal', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
        'mode' => 'service', // optional, dev/hk;当为 `hk` 时，为香港 gateway。

        // TODO ... (for production)
        /*
        'log' => [
            'file' => storage_path('logs/wechat.log'),
            'level' => 'info',
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 30,
        ],
        'mode' => 'normal',
        */

        'http' => [ // optional
            'timeout' => 10.0,
            'connect_timeout' => 10.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
    ],

    // Paypal 支付
    'paypal' => [
        //
    ],
];
