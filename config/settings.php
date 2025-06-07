<?php

return [

    /*
     * Each settings class used in your application must be registered, you can
     * put them (manually) here.
     */
    'settings' => [],

    /*
     * The path where the settings classes will be created.
     */
    'setting_class_path' => app_path('Settings'),

    /*
     * In these directories settings migrations will be stored and ran when migrating. A settings
     * migration created via the make:settings-migration command will be stored in the first path or
     * a custom defined path when running the command.
     */
    'migrations_paths' => [
        database_path('settings'),
    ],

    /*
     * When no repository was set for a settings class the following repository
     * will be used for loading and saving settings.
     */
    'default_repository' => env('SETTINGS_DEFAULT_REPOSITORY', 'database'),

    /*
     * Settings will be stored and loaded from these repositories.
     */
    'repositories' => [
        'database' => [
            'type' => Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository::class,
            'model' => env('SETTINGS_DATABASE_MODEL'),
            'table' => env('SETTINGS_DATABASE_TABLE'),
            'connection' => env('SETTINGS_DATABASE_CONNECTION'),
        ],
        'redis' => [
            'type' => Spatie\LaravelSettings\SettingsRepositories\RedisSettingsRepository::class,
            'connection' => env('SETTINGS_REDIS_CONNECTION'),
            'prefix' => env('SETTINGS_REDIS_PREFIX'),
        ],
    ],

    /*
     * The encoder and decoder will determine how settings are stored and
     * retrieved in the database. By default, `json_encode` and `json_decode`
     * are used.
     */
    'encoder' => env('SETTINGS_ENCODER_CLASS'),
    'decoder' => env('SETTINGS_DECODER_CLASS'),

    /*
     * The contents of settings classes can be cached through your application,
     * settings will be stored within a provided Laravel store and can have an
     * additional prefix.
     */
    'cache' => [
        'enabled' => env('SETTINGS_CACHE_ENABLED', true),
        'store' => env('SETTINGS_CACHE_STORE', 'database'), // 'database' or 'redis'
        'prefix' => env('SETTINGS_CACHE_PREFIX', \Illuminate\Support\Str::slug(env('APP_NAME', 'ZipLMS'), '_').'_settings_cache_'),
        'ttl' => env('SETTINGS_CACHE_TTL', 60 * 60 * 24), // 1 day
    ],

    /*
     * These global casts will be automatically used whenever a property within
     * your settings class isn't a default PHP type.
     */
    'global_casts' => [
        DateTimeInterface::class => Spatie\LaravelSettings\SettingsCasts\DateTimeInterfaceCast::class,
        DateTimeZone::class => Spatie\LaravelSettings\SettingsCasts\DateTimeZoneCast::class,
        //        Spatie\DataTransferObject\DataTransferObject::class => Spatie\LaravelSettings\SettingsCasts\DtoCast::class,
        Spatie\LaravelData\Data::class => Spatie\LaravelSettings\SettingsCasts\DataCast::class,
    ],

    /*
     * The package will look for settings in these paths and automatically
     * register them.
     */
    'auto_discover_settings' => [
        app_path('Settings'),
    ],

    /*
     * Automatically discovered settings classes can be cached, so they don't
     * need to be searched each time the application boots up.
     */
    'discovered_settings_cache_path' => base_path('bootstrap/cache'),
];
