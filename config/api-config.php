<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Features
    |--------------------------------------------------------------------------
    |
    | ENCRYPTION_KEY
    | custom_key : key for global custom encryption (shared)
    | meta_key : key for custom meta encryption (dynamic)
    | value for KEY : 16 alphanumeric string
    |
    | api_secure
    | This values for tunning on/off security to access the system.
    | value (boolean): true | false
    |
    */
    'custom_key' => env('CUSTOM_ENCRYPTION_KEY', 'mycustomkey12345'), // Global key for custom encryption (shared)
    'meta_key' => env('META_ENCRYPTION_KEY', 'valuecustom12345'), // Default dynamic meta key for custom encryption (dynamic)

    'api_secure' => [
        'header' => env('API_SECURE_HEADER', false), // true: implemented of header key must exists
        'meta' => env('API_SECURE_META', false), // true: implemented of meta key should exists on response
        'encryption' => env('API_SECURE_ENCRYPTION', false), // true: implemented encrypt|decrypt string on sesitive data  
        // 'host' => true, // will implemented on next session
    ],

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
    'custom_meta' => [
        env('APP_META_CUSTOM_KEY', 'key') => env('APP_META_CUSTOM_VALUE', 'value'),
    ],
    'meta' => [
        'app' => env('APP_NAME', 'Laravel'),
        'version' => env('APP_VERSION', '1.0.0'),
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