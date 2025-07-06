<?php

return [
    // General
    'model_label' => 'Lịch học',
    'model_label_plural' => 'Lịch học',

    // Form
    'form' => [
        'section' => [
            'main' => 'Thông tin chính',
            'time_location' => 'Thời gian & Địa điểm',
            'assignment_status' => 'Phân công & Trạng thái',
        ],
        'associated_with' => 'Phân loại',
        'title' => 'Tiêu đề',
        'description' => 'Mô tả',
        'start_time' => 'Thời gian bắt đầu',
        'end_time' => 'Thời gian kết thúc',
        'location_type' => 'Địa điểm',
        'location_details' => 'Chi tiết địa điểm (Phòng, URL,...)',
        'location_details_placeholder' => 'VD: Phòng A1, https://zoom.us/j/...',
        'assigned_teacher' => 'Giáo viên phụ trách',
        'status' => 'Trạng thái',
    ],

    // Table
    'table' => [
        'associated_with' => 'Phân loại',
        'location_type' => 'Địa điểm',
    ],

    // Filters
    'filters' => [
        'location_type' => 'Lọc theo loại địa điểm',
    ],

    'validation' => [
        'end_time_after' => 'Thời gian kết thúc phải là một ngày sau thời gian bắt đầu.',
    ],
];
