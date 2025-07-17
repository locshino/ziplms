<?php

return [
    'default' => [
        'connection' => env('QUEUE_CONNECTION', 'redis'),
        'name' => env('QUEUE_NAME', 'ziplms_default'),
    ],

    'media' => [
        'connection' => env('QUEUE_MEDIA_CONNECTION', 'redis'),
        'name' => env('QUEUE_MEDIA_NAME', 'ziplms_media'),
    ],

    'batch' => [
        'connection' => env('QUEUE_BATCH_CONNECTION', 'redis'),
        'name' => env('QUEUE_BATCH_NAME', 'ziplms_batches'),
    ],

    'exporter' => [
        'connection' => env('QUEUE_EXPORTER_CONNECTION', 'redis'),
        'name' => env('QUEUE_EXPORTER_NAME', 'ziplms_exporters'),
    ],
];
