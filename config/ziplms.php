<?php

return [
    'currency' => [
        'default' => env('CURRENCY_DEFAULT', 'USD'),
        'supported' => explode(',', env('CURRENCY_SUPPORTED', 'USD,EUR,GBP')),
    ],

    'plugins' => [
        'renew_password' => [
            'enabled' => env('RENEW_PASSWORD_ENABLED', true),
            'password_expires_in' => env('RENEW_PASSWORD_EXPIRES_IN_DAYS', 30),
            'force_renew_password' => env('FORCE_RENEW_PASSWORD', true),
        ],

        'backgrounds' => [
            'enabled' => env('BACKGROUNDS_ENABLED', true),
            'remember_in_seconds' => env('BACKGROUNDS_REMEMBER_CACHE', 900),

            // Add options for specific providers
            'provider_options' => [
                'directory' => env('BACKGROUNDS_MY_IMAGES_DIRECTORY', 'images/backgrounds'),
            ],
        ],

        'easy_footer' => [
            'enabled' => env('EASY_FOOTER_ENABLED', true),
        ],

        'socialite' => [
            'enabled' => env('SOCIALITE_ENABLED', true),
            'allow_registration' => env('SOCIALITE_ALLOW_REGISTRATION', true),
        ],

        'environment_indicator' => [
            'enabled' => env('ENVIRONMENT_INDICATOR_ENABLED', true),
        ],
    ],
];
