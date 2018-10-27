<?php

return [
    'default' => env('QUEUE_DRIVER', 'sync'),

    'connections' => [
        'sync' => [
            'driver' => 'sync',
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'default',
            'retry_after' => 90,
            'block_for' => null,
        ],
    ],

    'failed' => [
        'database' => env('DB_CONNECTION'),
        'table' => 'failed_jobs',
    ],
];
