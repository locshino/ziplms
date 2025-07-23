<?php

return [

    'title' => 'Ghi danh Người dùng vào Đơn vị cấu trúc',
    'form_title' => [
        'user' => 'Người dùng',
    ],
    'labels' => [
        'class_major_id' => 'Đơn vị cấu trúc',
        'role_id' => 'Vai trò',
        'start_date' => 'Ngày bắt đầu',
        'end_date' => 'Ngày kết thúc',
    ],
    'columns' => [
        'id' => 'ID',
        'user' => [
            'name' => 'Người dùng',
            'role_names_string' => 'Vai trò',
        ],
        'classMajor' => [
            'name' => 'Đơn vị cấu trúc',
        ],
        'start_date' => 'Ngày bắt đầu',
        'end_date' => 'Ngày kết thúc',
        'created_at' => 'Tạo lúc',
        'updated_at' => 'Cập nhật lúc',
    ],

    'filters' => [
        'class_major_id' => 'Lọc theo đơn vị cấu trúc',
    ],

    'actions' => [
        'view' => 'Xem',
        'delete' => [
            'title' => 'Xóa',
            'error_no_user' => 'Bản ghi không có người dùng liên kết.',
            'error_self_delete' => 'Bạn không thể xóa bản ghi của chính mình.',
        ],
    ],

    'notifications' => [
        'error' => 'Lỗi',
        'delete_denied' => 'Không thể xóa',
    ],

    'export' => [
        'title' => 'Xuất Excel',
    ],

];
