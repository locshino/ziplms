<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Location Status Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for the location status state machine.
    | You are free to change them to anything you want to customize
    | your application's UI.
    |
    */

    'planned' => [
        'label' => 'Planned',
        'description' => 'The location is planned for the future and not yet open.',
    ],

    'available' => [
        'label' => 'Available',
        'description' => 'The location is open and available for scheduling.',
    ],

    'under_maintenance' => [
        'label' => 'Under Maintenance',
        'description' => 'The location is temporarily unavailable for maintenance.',
    ],

    'archived' => [
        'label' => 'Archived',
        'description' => 'The location is permanently closed and kept for historical records.',
    ],
];
