<?php

return [
    'columns' => [
        'id' => 'ID',
        'teacher_id' => 'ID Giảng viên',
        'teacher_name' => 'Giảng viên',
        'title' => 'Tiêu đề',
        'description' => 'Mô tả',
        'created_at' => 'Ngày tạo',
        'updated_at' => 'Ngày cập nhật',
        'deleted_at' => 'Ngày xóa',
    ],
    'fields' => [
        'title' => 'Tiêu đề',
        'description' => 'Mô tả',
        'teacher_id' => 'Giáo viên',
        'start_date' => 'Ngày bắt đầu',
        'end_date' => 'Ngày kết thúc',
    ],
    'model' => [
        'singular' => 'Khóa học',
        'plural' => 'Khóa học',
    ],
];