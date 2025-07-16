<?php

return [
    'pending' => [
        'label' => 'Pending',
        'description' => 'The task is waiting to be processed.',
    ],
    'in_progress' => [
        'label' => 'In Progress',
        'description' => 'The task is currently being executed.',
    ],
    'done' => [
        'label' => 'Done',
        'description' => 'The task completed successfully.',
    ],
    'done_with_errors' => [
        'label' => 'Done with Errors',
        'description' => 'The task completed, but with some non-critical errors.',
    ],
    'failed' => [
        'label' => 'Failed',
        'description' => 'The task failed and could not be completed.',
    ],
    'canceled' => [
        'label' => 'Canceled',
        'description' => 'The task was canceled by the user or the system.',
    ],
    'retrying' => [
        'label' => 'Retrying',
        'description' => 'A failed task is being retried.',
    ],
];
