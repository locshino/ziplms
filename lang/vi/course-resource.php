<?php

return [
    'model_label' => 'Môn học',
    'model_label_plural' => 'Các Môn học',

    'form' => [
        'section' => [
            'general' => 'Thông tin chung',
            'config' => 'Trạng thái & Cấu hình',
            'time' => 'Thời gian',
        ],
        'name' => 'Tên môn học',
        'description' => 'Mô tả chi tiết',
        'tags' => 'Phân loại',
        'image' => 'Ảnh đại diện',
        'code' => 'Mã môn học',
        'status' => 'Trạng thái',
        'parent_id' => 'Thuộc môn học cha',
        'organization_id' => 'Tổ chức',
        'start_date' => 'Ngày bắt đầu',
        'end_date' => 'Ngày kết thúc',
    ],

    'table' => [
        'image' => 'Ảnh',
        'name' => 'Tên môn học',
        'code' => 'Mã',
        'status' => 'Trạng thái',
        'tags' => 'Phân loại',
        'organization' => 'Tổ chức',
        'updated_at' => 'Cập nhật lúc',
    ],

    'filters' => [
        'status' => 'Trạng thái',
        'organization' => 'Tổ chức',
        'tags' => 'Phân loại',
        'created_at' => 'Ngày tạo',
        'created_from' => 'Tạo từ ngày',
        'created_until' => 'Đến ngày',
    ],

    'actions' => [
        'export' => 'Xuất Excel',
        'import' => 'Nhập Excel',
    ],

    'notifications' => [
        'delete_success_title' => 'Xóa môn học thành công',
        'delete_success_body' => 'Môn học đã được xóa khỏi hệ thống.',
    ],
];
