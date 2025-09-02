<?php

return [
    'resource' => [
        'label' => 'Badge Condition',
        'plural_label' => 'Badge Conditions',
        'navigation_label' => 'Badge Conditions',
        'navigation_group' => 'Gamification',
    ],
    'form' => [
        'fields' => [
            'title' => 'Title',
            'description' => 'Description',
            'condition_type' => 'Condition Type',
            'condition_data' => 'Condition Data',
            'status' => 'Status',
        ],
    ],
    'table' => [
        'columns' => [
            'id' => 'ID',
            'title' => 'Title',
            'condition_type' => 'Condition Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
    ],
    'infolist' => [
        'entries' => [
            'id' => 'ID',
            'title' => 'Title',
            'condition_type' => 'Condition Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
    ],
];
