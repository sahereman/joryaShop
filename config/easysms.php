<?php

return [

    // HTTP 请求的超时时间（秒）
    'timeout' => env('ALIYUN_SMS_TIMEOUT', 10.0),

    // 默认发送配置
    'default' => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'aliyun',
        ],
    ],

    // 可用的网关配置
    'gateways' => [
        'aliyun' => [
            'access_key_id' => env('ALIYUN_SMS_ACCESS_KEY_ID', 'access_key_id'),
            'access_key_secret' => env('ALIYUN_SMS_ACCESS_KEY_SECRET', 'access_key_secret'),
            'sign_name' => env('ALIYUN_SMS_SIGN_NAME', 'sign_name'),
        ],
        'errorlog' => [
            // 'file' => '/tmp/easy-sms.log',
            'file' => storage_path('logs/easy_sms.log'),
        ],
        // ...
    ],

    // 使用短信模板 'SMS_*********'
    'domestic_template' => env('ALIYUN_SMS_DOMESTIC_TEMPLATE', 'domestic_template'),
    'international_template' => env('ALIYUN_SMS_INTERNATIONAL_TEMPLATE', 'international_template'),

    // errorlog
    // 'file' => '/tmp/easy-sms.log',
    'file' => storage_path('logs/easy_sms.log'),
];
