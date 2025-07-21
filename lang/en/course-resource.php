<?php

return [
    'model_label' => 'Course',
    'model_label_plural' => 'Courses',

    'form' => [
        'section' => [
            'general' => 'General Information',
            'config' => 'Status & Configuration',
            'time' => 'Time',
        ],
        'name' => 'Course Name',
        'description' => 'Detailed Description',
        'tags' => 'Tags',
        'image' => 'Cover Image',
        'code' => 'Course Code',
        'status' => 'Status',
        'parent_id' => 'Parent Course',
        'organization_id' => 'Organization',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
    ],

    'table' => [
        'image' => 'Image',
        'name' => 'Course Name',
        'code' => 'Code',
        'status' => 'Status',
        'tags' => 'Tags',
        'organization' => 'Organization',
        'updated_at' => 'Updated At',
    ],

    'filters' => [
        'status' => 'Status',
        'organization' => 'Organization',
        'tags' => 'Tags',
        'created_at' => 'Created At',
        'created_from' => 'Created From',
        'created_until' => 'Created Until',
    ],

    'actions' => [
        'export' => 'Export Excel',
        'import' => 'Import Excel',
    ],

    'notifications' => [
        'delete_success_title' => 'Course deleted successfully',
        'delete_success_body' => 'The course has been removed from the system.',
    ],
];
