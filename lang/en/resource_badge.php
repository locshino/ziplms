<?php

return [
    'resource' => [
        'label' => 'Badge',
        'plural_label' => 'Badges',
        'navigation_label' => 'Badges',
        'navigation_group' => 'Gamification',
    ],
    'form' => [
        'fields' => [
            'title' => 'Title',
            'slug' => 'Slug',
            'description' => 'Description',
            'status' => 'Status',
        ],
    ],
    'table' => [
        'columns' => [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
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
            'slug' => 'Slug',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
    ],
];
