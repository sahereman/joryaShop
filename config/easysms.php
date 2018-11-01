<?php

return [

    // HTTP 请求的超时时间（秒）
    'timeout' => env('ALIYUN_SMS_TIMEOUT', 5.0),

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
            'access_key_id' => env('ALIYUN_SMS_ACCESS_KEY_ID', 'LTAI9tLIc5tEbKl0'),
            'access_key_secret' => env('ALIYUN_SMS_ACCESS_KEY_SECRET', '2kmg2xFde4krB99yzFroJmgYqjVVTd'),
            'sign_name' => env('ALIYUN_SMS_SIGN_NAME', '卓雅美业'),
        ],
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        // ...
    ],

    // 使用短信模板 'SMS_*********'
    'domestic_template' => env('ALIYUN_SMS_DOMESTIC_TEMPLATE', 'SMS_149095008'),
    'international_template' => env('ALIYUN_SMS_INTERNATIONAL_TEMPLATE', 'SMS_149100005'),

    // errorlog
    'file' => '/tmp/easy-sms.log',
];
