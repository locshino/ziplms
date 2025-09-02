<?php

return [
    'resource' => [
        'label' => 'Câu hỏi',
        'plural_label' => 'Các câu hỏi',
        'navigation_label' => 'Câu hỏi',
        'navigation_group' => 'Quản lý Quiz',
    ],
    'form' => [
        'fields' => [
            'title' => 'Tiêu đề',
            'description' => 'Mô tả',
            'status' => 'Trạng thái',
        ],
    ],
    'table' => [
        'columns' => [
            'id' => 'ID',
            'title' => 'Tiêu đề',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'deleted_at' => 'Ngày xóa',
        ],
    ],
    'infolist' => [
        'entries' => [
            'id' => 'ID',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'deleted_at' => 'Ngày xóa',
        ],
    ],
];
