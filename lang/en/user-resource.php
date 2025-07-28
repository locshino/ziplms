<?php

return [
    'model_label' => 'User',
    'model_label_plural' => 'Users',

    'form' => [
        'section' => [
            'main' => 'User Information',
            'roles_permissions' => 'Roles, Organizations & Classes',
            'learning_stats' => 'Learning Statistics',
            'status_history' => 'Status & History',
        ],
        'name' => 'Full Name',
        'email' => 'Email Address',
        'password' => 'Password',
        'password_confirmation' => 'Password Confirmation',
        'code' => 'User Code',
        'phone_number' => 'Phone Number',
        'address' => 'Address',
        'profile_picture' => 'Profile Picture',
        'roles' => 'Roles',
        'status' => 'Status',
        'organizations' => 'Organizations',
        'classes_majors' => 'Classes/Majors',
    ],

    'table' => [
        'profile_picture' => 'Avatar',
        'code' => 'Code',
        'name' => 'Name',
        'organizations' => 'Organizations',
        'classes_majors' => 'Class/Major',
        'roles' => 'Roles',
        'status' => 'Status',
        'created_at' => 'Created At',
        'null_text' => 'Null',
    ],

    'infolist' => [
        'registered_courses' => 'Registered Courses',
        'completed_courses' => 'Completed Courses',
        'learning_progress' => 'Course Progress',
        'progress_and_stats_section_title' => 'Statistics & Learning Progress',
        'lecture_completion' => 'Lecture Completion',
        'verified_at' => 'Verified At',
        'joined_at' => 'Joined At',
        'last_updated' => 'Last Updated',
        'not_updated' => 'Not updated',
        'not_verified' => 'Not verified',
    ],

    'filters' => [
        'roles' => 'Roles',
        'organizations' => 'Organizations',
        'classes_majors' => 'Class/Major',
        'status' => 'Status',
    ],

    'actions' => [
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'delete_notification_title' => 'User deleted',
        'delete_notification_body' => 'The user has been deleted from the system.',
        'export_selected' => 'Export Selected',
        'bulk_delete_confirmation' => 'Are you sure you want to delete the selected users?',
        'bulk_delete_success_title' => 'Users deleted',
        'bulk_delete_success_body' => 'The selected users have been deleted from the system.',
        'import_students' => 'Import Students',
        'import_teachers' => 'Import Teachers',
        'import_from_csv' => 'Import from CSV',
    ],

    'pages' => [
        'create_with_role' => 'Create new :role',
        'create_success_title' => 'User created',
        'create_success_body' => 'A new user has been added to the system.',
    ],

    'relation_manager' => [
        'tab_title' => 'Lectures',
        'table' => [
            'title' => 'Lecture Title',
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
