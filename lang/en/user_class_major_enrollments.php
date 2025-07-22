<?php

return [

    'form_title' => [
        'user' => 'User',
    ],
    'labels' => [
        'class_major_id' => 'Class Major Unit',
        'role_id' => 'Role',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
    ],

    'columns' => [
        'id' => 'ID',
        'user' => [
            'name' => 'User',
            'role_names_string' => 'Role',
        ],
        'classMajor' => [
            'name' => 'Structure Unit',
        ],
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ],

    'filters' => [
        'class_major_id' => 'Filter by Structure Unit',
    ],

    'actions' => [
        'view' => 'View',
        'delete' => [
            'title' => 'Delete',
            'error_no_user' => 'The record has no associated user.',
            'error_self_delete' => 'You cannot delete your own record.',
        ],
    ],

    'notifications' => [
        'error' => 'Error',
        'delete_denied' => 'Delete Denied',
    ],

    'export' => [
        'title' => 'Export to Excel',
    ],

];
