<?php

return [
    'model_label' => 'Phân công',
    'model_label_plural' => 'Phân công Nhân sự',
    'navigation_group' => 'Quản lý Khóa học',

    'form' => [
        'section' => [
            'details' => 'Thông tin phân công chi tiết',
        ],
        'course' => 'Khóa học',
        'staff' => 'Nhân sự',
        'role' => 'Vai trò trong khóa học',
        'validation' => [
            'unique' => 'Nhân sự này đã được phân công cho khóa học này rồi.',
        ],
    ],

    'table' => [
        'course' => 'Khóa học',
        'staff_name' => 'Họ và Tên',
        'staff_email' => 'Email',
        'role' => 'Vai trò',
        'group' => 'Khóa học',
    ],

    'filters' => [
        'course' => 'Lọc theo khóa học',
        'role' => 'Lọc theo vai trò',
    ],
];