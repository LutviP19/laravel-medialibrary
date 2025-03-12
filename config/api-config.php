<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted Hosts
    |--------------------------------------------------------------------------
    |
    | This value for trusted hosts to access the system.
    |
    */
    'trusted_hosts' => [
        'laravel.test',
    ],

    /*
    |--------------------------------------------------------------------------
    | Trusted IPs
    |--------------------------------------------------------------------------
    |
    | This value for trusted IPs to access the system.
    |
    */
    'trusted_ips' => [
        '127.0.0.1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default meta
    |--------------------------------------------------------------------------
    |
    | This value is the default meta value for the api.
    |
    */

    'meta' => [
        'app' => env('APP_NAME', 'Laravel'),
        'version' => env('APP_VERSION', '1.0.0'),
        env('APP_META_CUSTOM_KEY', 'key') => env('APP_META_CUSTOM_VALUE', 'value'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default header
    |--------------------------------------------------------------------------
    |
    | This value is the default header value for the api.
    |
    */
    
    'header' => [
        // Default custom header
        'header_custom_api' => [
            'key' => env('APP_HEADER_CUSTOM_KEY', 'X-Value'),
            'value' => env('APP_HEADER_CUSTOM_VALUE', 'custom')
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default connection and queue name
    |--------------------------------------------------------------------------
    |
    | This value is the default connection and queue name.
    |
    */

    // Default events and listener connection and queue name
    'event_default' => [
        'connection' => 'redis', //'redis' | rabbitmq
        'queue' => env('REDIS_QUEUE', 'default'), //env('REDIS_QUEUE', 'default') | env('RABBITMQ_QUEUE', 'laravel-queue')
    ],

];