<?php

return [
    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID'),  // Your Facebook App ID.
        'app_secret' => env('FACEBOOK_APP_SECRET'), // Your Facebook App Secret.
        'client_token' => env('FACEBOOK_CLIENT_TOKEN'), // Your Facebook App Client Token.
        'redirect' => env('FACEBOOK_CALLBACK_URL'), // Your Facebook Login Callback Url.
        'graph_version' => env('FACEBOOK_GRAPH_VERSION'), // Your Facebook Graph Api Version
    ],
];
