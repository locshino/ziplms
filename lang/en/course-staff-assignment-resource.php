<?php

return [
    'model_label' => 'Assignment',
    'model_label_plural' => 'Staff Assignments',
    'navigation_group' => 'Course Management',

    'form' => [
        'section' => [
            'details' => 'Assignment Details',
        ],
        'course' => 'Course',
        'staff' => 'Staff',
        'role' => 'Role in course',
        'validation' => [
            'unique' => 'This staff member is already assigned to this course.',
        ],
    ],

    'table' => [
        'course' => 'Course',
        'staff_name' => 'Full Name',
        'staff_email' => 'Email',
        'role' => 'Role',
        'group' => 'Course',
    ],

    'filters' => [
        'course' => 'Filter by course',
        'role' => 'Filter by role',
    ],
];