<?php

return [
    // Cache duration in seconds
    'cache_duration' => 21600, // 6 hours

    'cluster' => null,

    // Filament navigation settings
    'navigation' => [
        'group' => 'Settings',
    ],

    // Access control
    'can_access' => [
        'role' => 'admin',
    ],

    // Tenancy configuration
    'tenancy_enabled' => false, // Set to true for multi-tenant apps

    // Your tenant model (only needed if tenancy_enabled is true)
    'tenant_model' => null, // \App\Models\Team::class

    // Table name for storing Translation Overrides
    'table_name' => 'translation_overrides',
    'tenant_id_column' => null, // Ex. tenant_id, only used if tenancy_enabled is true

    // Supported languages
    'supported_languages' => [
        'en' => 'English',
        'vi' => 'Vietnamese',
        // Add more languages as needed
    ],
];
