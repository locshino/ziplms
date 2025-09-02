<?php

return [
    'resource' => [
        'label' => 'Khóa học',
        'plural_label' => 'Các khóa học',
        'navigation_label' => 'Khóa học',
        'navigation_group' => 'Quản lý khóa học',
    ],
    'form' => [
        'fields' => [
            'course_cover' => 'Ảnh bìa khóa học',
            'title' => 'Tiêu đề',
            'teacher_id' => 'Giáo viên',
            'status' => 'Trạng thái',
            'tags' => 'Phân loại',
            'start_at' => 'Thời gian bắt đầu',
            'end_at' => 'Thời gian kết thúc',
            'course_documents' => 'Tài liệu khóa học',
            'slug' => 'Slug',
            'price' => 'Giá',
            'is_featured' => 'Nổi bật',
            'description' => 'Mô tả',
        ],
    ],
    'table' => [
        'columns' => [
            'id' => 'ID',
            'title' => 'Tiêu đề',
            'slug' => 'Slug',
            'teacher' => [
                'name' => 'Giáo viên',
            ],
            'start_at' => 'Bắt đầu',
            'end_at' => 'Kết thúc',
            'status' => 'Trạng thái',
            'price' => 'Giá',
            'is_featured' => 'Nổi bật',
            'tags' => 'Phân loại',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'deleted_at' => 'Ngày xóa',
        ],
    ],
];
