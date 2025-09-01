<?php

return [
    'resource' => [
        'label' => 'Quiz',
        'plural_label' => 'Quizzes',
        'navigation_label' => 'Quizzes',
        'navigation_group' => 'Course Management',
    ],
    'form' => [
        'fields' => [
            'title' => 'Title',
            'max_attempts' => 'Max Attempts',
            'time_limit_minutes' => 'Time Limit (minutes)',
            'status' => 'Status',
            'is_single_session' => 'Single Session',
            'tags' => 'Tags',
            'description' => 'Description',
        ],
    ],
    'table' => [
        'columns' => [
            'id' => 'ID',
            'title' => 'Title',
            'max_attempts' => 'Max Attempts',
            'is_single_session' => 'Single Session',
            'time_limit_minutes' => 'Time Limit',
            'status' => 'Status',
            'tags' => 'Tags',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
    ],
    'infolist' => [
        'entries' => [
            'id' => 'ID',
            'title' => 'Title',
            'max_attempts' => 'Max Attempts',
            'is_single_session' => 'Single Session',
            'time_limit_minutes' => 'Time Limit',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
    ],
];
