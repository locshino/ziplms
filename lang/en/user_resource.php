<?php

return [
    'columns' => [
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'Email',
        'email_verified_at' => 'Email Verified At',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',
    ],
    'fields' => [
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'email_verified_at' => 'Email Verified At',
        'avatar' => 'Avatar',
        'roles' => 'Roles',
    ],
    'sections' => [
        'basic_info' => 'Basic information',
        'authorization' => 'User authorization',
        'description' => 'Choose a or many vai trò trò chơi cho người dùng.',
        'update' => 'Upload your image.',
    ],
    'model' => [
        'singular' => 'User',
        'plural' => 'Users',
    ],
    'header' => [
        'email' => 'Email',
        'add_course_button' => 'Add to Course',
        'select_course_placeholder' => '-- Select course --',
        'save_button' => 'Save',
    ],
    'tabs' => [
        'course_number' => 'Course Count',
        'badge' => 'Badges',
    ],
    'course_list' => [
        'no_title' => 'No title',
        'no_description' => 'No description',
        'not_enrolled' => 'Not enrolled in any courses',
        'not_enrolled_detail' => 'You have not registered for any courses yet. Discover and register today!',
        'progress_text' => '{progress}%',
    ],
    'badge_list' => [
        'no_badges' => 'No badges yet',
    ],
];