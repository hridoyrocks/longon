<?php
return [
    'default' => env('BROADCAST_DRIVER', 'pusher'),

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY', '8ccf5d4c4bf78fcec3c9'),
            'secret' => env('PUSHER_APP_SECRET', 'your-secret'),
            'app_id' => env('PUSHER_APP_ID', '1234567'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
                'useTLS' => true,
                'encrypted' => true,
                'host' => env('PUSHER_HOST') ?: 'api-'.env('PUSHER_APP_CLUSTER', 'ap2').'.pusher.com',
                'port' => env('PUSHER_PORT', 443),
                'scheme' => env('PUSHER_SCHEME', 'https'),
            ],
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],
    ],
];