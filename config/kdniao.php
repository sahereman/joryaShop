<?php

return [
    // HTTP 请求的超时时间（秒）
    'timeout' => env('KDNIAO_TIMEOUT', 10.0),
    // 2: HTTP 请求|返回数据类型均为JSON格式
    'data_type' => env('KDNIAO_DATA_TYPE', 2),
    // 开发环境
    'development' => [
        'ebusiness_id' => env('KDNIAO_EBUSINESS_ID_DEVELOPMENT', 'ebusiness_id'),
        'api_key' => env('KDNIAO_API_KEY_DEVELOPMENT', 'api_key'),
        'request_url' => env('KDNIAO_REQUEST_URL_DEVELOPMENT', 'request_url'),
    ],
    // 生产环境
    'production' => [
        'ebusiness_id' => env('KDNIAO_EBUSINESS_ID_PRODUCTION', 'ebusiness_id'),
        'api_key' => env('KDNIAO_API_KEY_PRODUCTION', 'api_key'),
        'request_url' => env('KDNIAO_REQUEST_URL_PRODUCTION', 'request_url'),
    ],
];
