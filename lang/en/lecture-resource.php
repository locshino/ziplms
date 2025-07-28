<?php

return [
    'model_label' => 'Lecture',
    'model_label_plural' => 'Lectures',

    'navigation' => [
        'group' => 'Learning Content',
    ],

    'form' => [
        'section' => [
            'main' => 'Lecture Content',
            'meta' => 'General Information',
        ],
        'title' => 'Lecture Title',
        'description' => 'Description',
        'course' => 'Course',
        'duration_estimate' => 'Estimated Duration',
        'lecture_order' => 'Lecture Order',
        'status' => 'Status',
    ],

    'table' => [
        'order' => '#',
        'title' => 'Lecture Title',
        'course' => 'Course',
        'duration_estimate' => 'Estimated Duration',
        'status' => 'Status',
        'created_at' => 'Created At',
    ],

    'infolist' => [
        'section' => [
            'main' => 'Lecture Content',
            'meta' => 'General Information',
            'statistics' => 'Student Statistics',
        ],
        'title' => 'Title',
        'description' => 'Description',
        'course' => 'Belongs to Course',
        'duration_estimate' => 'Estimated Duration',
        'lecture_order' => 'Lecture Order',
        'status' => 'Status',
        'enrolled_users' => 'Total Enrollments',
        'completed_users' => 'Completed',
    ],

    'filters' => [
        'course' => 'Filter by Course',
        'status' => 'Filter by Status',
    ],

    'actions' => [
        'export_selected' => 'Export Selected',
        'export_excel' => 'Export Excel',
        'import_excel' => 'Import Excel',
        'view_enrolled_users' => 'View Enrolled Students',
        'modal_close' => 'Close',
    ],

    'time' => [
        'hours' => 'hours',
        'minutes' => 'minutes',
    ],

    'relation_manager' => [
        'table' => [
            'name' => 'Student Name',
            'email' => 'Email',
            'status' => 'Status',
            'completed_at' => 'Completed At',
        ],
        'status' => [
            'not_started' => 'Not Started',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
        ],
        'actions' => [
            'edit_progress' => 'Edit Progress',
        ],
    ],
];
