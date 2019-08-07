<?php

return [
    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID', '151515151515151'),  // Your Facebook App ID.
        'app_secret' => env('FACEBOOK_APP_SECRET', '32323232323232323232323232323232'), // Your Facebook App Secret.
        'client_token' => env('FACEBOOK_CLIENT_TOKEN', '32323232323232323232323232323232'), // Your Facebook App Client Token.
        'redirect' => env('FACEBOOK_CALLBACK_URL', 'https://lyricalhair.com/socialites/callback/facebook'), // Your Facebook Login Callback Url.
        'graph_version' => env('FACEBOOK_GRAPH_VERSION', 'v4.0'), // Your Facebook Graph Api Version
    ],
];
