<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Virtual Queue Base URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL of the Virtual Queue API. You can change this
    | if you're using a different environment or custom domain.
    |
    */
    'base_url' => env('VIRTUAL_QUEUE_BASE_URL', 'https://app.virtual-queue.com'),

    /*
    |--------------------------------------------------------------------------
    | Additional Options
    |--------------------------------------------------------------------------
    |
    | Additional options that will be passed to the HTTP client.
    | You can configure timeout, headers, and other Guzzle options here.
    |
    */
    'options' => [
        'timeout' => env('VIRTUAL_QUEUE_TIMEOUT', 30),
        'headers' => [
            'Accept' => 'application/json',
        ],
    ],
];