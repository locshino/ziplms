<?php

return [
    'active' => [
        'label' => 'Active',
        'description' => 'The object is currently active and ready for use.',
    ],
    'inactive' => [
        'label' => 'Inactive',
        'description' => 'The object has been manually disabled and is temporarily hidden.',
    ],
    'pending' => [
        'label' => 'Pending',
        'description' => 'The schedule has been created but is awaiting confirmation or approval.',
    ],
    'in_progress' => [
        'label' => 'In Progress',
        'description' => 'The event or schedule is currently happening.',
    ],
    'completed' => [
        'label' => 'Completed',
        'description' => 'The event has finished successfully as scheduled.',
    ],
    'cancelled' => [
        'label' => 'Cancelled',
        'description' => 'The event was cancelled before it could take place or be completed.',
    ],
    'postponed' => [
        'label' => 'Postponed',
        'description' => 'The event has been postponed and will be rescheduled for a later time.',
    ],
    'archived' => [
        'label' => 'Archived',
        'description' => 'The object is old and has been moved to archives, not shown in regular lists.',
    ],
    'default' => [
        'label' => 'Undefined',
        'description' => 'The status of the object is not defined.',
    ],
];
