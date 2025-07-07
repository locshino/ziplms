<?php

return [
    // General
    'model_label' => 'Schedule',
    'model_label_plural' => 'Schedules',

    // Form
    'form' => [
        'section' => [
            'main' => 'Main Information',
            'time_location' => 'Time & Location',
            'assignment_status' => 'Assignment & Status',
        ],
        'associated_with' => 'Associated With',
        'title' => 'Title',
        'description' => 'Description',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
        'location_type' => 'Location Type',
        'location_details' => 'Location Details (Room, URL, etc.)',
        'location_details_placeholder' => 'e.g., Room A1, https://zoom.us/j/...',
        'assigned_teacher' => 'Assigned Teacher',
        'status' => 'Status',
    ],

    // Table
    'table' => [
        'associated_with' => 'Associated With',
        'location_type' => 'Location Type',
    ],

    // Filters
    'filters' => [
        'location_type' => 'Filter by Location Type',
    ],
];
