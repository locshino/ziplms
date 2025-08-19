<?php

return [
    'app_name' => env('APP_NAME', 'Laravel'),

    'github' => [
        'repository' => env('FOOTER_GITHUB_REPOSITORY', null),
        'token' => env('FOOTER_GITHUB_TOKEN', null),
        'cache_ttl' => env('FOOTER_GITHUB_CACHE_TTL', 3600),
    ],
];
