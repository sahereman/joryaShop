<?php

return [
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),  // Your Facebook App ID.
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'), // Your Facebook App Secret.
        'redirect' => env('FACEBOOK_CALLBACK_URL'), // Your Facebook Callback Url.
    ],
    /*'github' => [
        'client_id'     => 'your-app-id',
        'client_secret' => 'your-app-secret',
        'redirect'      => 'http://localhost/socialite/callback.php',
    ],*/
];
