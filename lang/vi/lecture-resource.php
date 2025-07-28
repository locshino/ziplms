<?php

return [
    'model_label' => 'Bài giảng',
    'model_label_plural' => 'Các bài giảng',

    'navigation' => [
        'group' => 'Nội dung học tập',
    ],

    'form' => [
        'section' => [
            'main' => 'Nội dung bài giảng',
            'meta' => 'Thông tin chung',
        ],
        'title' => 'Tiêu đề bài giảng',
        'description' => 'Mô tả',
        'course' => 'Môn học',
        'duration_estimate' => 'Thời lượng dự kiến',
        'lecture_order' => 'Thứ tự bài giảng',
        'status' => 'Trạng thái',
    ],

    'table' => [
        'order' => '#',
        'title' => 'Tiêu đề bài giảng',
        'course' => 'Môn học',
        'duration_estimate' => 'Thời lượng dự kiến',
        'status' => 'Trạng thái',
        'created_at' => 'Ngày tạo',
    ],

    'infolist' => [
        'section' => [
            'main' => 'Nội dung bài giảng',
            'meta' => 'Thông tin chung',
            'statistics' => 'Thống kê Học viên',
        ],
        'title' => 'Tiêu đề',
        'description' => 'Mô tả',
        'course' => 'Thuộc Môn học',
        'duration_estimate' => 'Thời lượng dự kiến',
        'lecture_order' => 'Thứ tự bài giảng',
        'status' => 'Trạng thái',
        'enrolled_users' => 'Tổng số đăng ký',
        'completed_users' => 'Đã hoàn thành',
    ],

    'filters' => [
        'course' => 'Lọc theo Môn học',
        'status' => 'Lọc theo trạng thái',
    ],

    'actions' => [
        'export_selected' => 'Xuất mục đã chọn',
        'export_excel' => 'Xuất Excel',
        'import_excel' => 'Nhập Excel',
        'view_enrolled_users' => 'Xem danh sách học sinh',
        'modal_close' => 'Đóng',
    ],

    'time' => [
        'hours' => 'giờ',
        'minutes' => 'phút',
    ],

    'relation_manager' => [
        'table' => [
            'name' => 'Tên học sinh',
            'email' => 'Email',
            'status' => 'Trạng thái',
            'completed_at' => 'Hoàn thành lúc',
        ],
        'status' => [
            'not_started' => 'Chưa bắt đầu',
            'in_progress' => 'Đang học',
            'completed' => 'Đã hoàn thành',
        ],
        'actions' => [
            'edit_progress' => 'Sửa tiến độ',
        ],
    ],
];
