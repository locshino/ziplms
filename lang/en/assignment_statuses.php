<?php

return [
    'draft' => [
        'label' => 'Draft',
        'description' => 'The assignment is being created and is not yet public.',
        'color' => 'gray',
        'icon' => 'heroicon-o-pencil-square',
    ],
    'published' => [
        'label' => 'Published',
        'description' => 'The assignment has been published to students.',
        'color' => 'success',
        'icon' => 'heroicon-o-eye',
    ],
    'closed' => [
        'label' => 'Closed',
        'description' => 'The assignment has ended and submissions are no longer accepted.',
        'color' => 'danger',
        'icon' => 'heroicon-o-lock-closed',
    ],
    'cancelled' => [
        'label' => 'Cancelled',
        'description' => 'The assignment has been cancelled.',
    ],
];
