<?php

return [
    'labels' => [
        'singular' => 'Lựa chọn trả lời',
        'plural' => 'Các lựa chọn trả lời',
    ],
    'form' => [
        'tabs' => [
            'vi' => 'Nội dung (Tiếng Việt)',
            'en' => 'Nội dung (Tiếng Anh)',
        ],
        'fields' => [
            'is_correct' => 'Là đáp án đúng?',
            'choice_order' => 'Thứ tự',
        ],
        'validation' => [
            'unique_order' => 'Thứ tự này đã tồn tại cho một lựa chọn khác.',
        ],
    ],
    'table' => [
        'columns' => [
            'choice_text' => 'Nội dung',
            'is_correct' => 'Đáp án đúng',
            'choice_order' => 'Thứ tự',
        ],
        'actions' => [
            'create' => 'Thêm lựa chọn',
        ],
    ],
    'notifications' => [
        'created' => 'Đã thêm lựa chọn thành công.',
        'updated' => 'Đã cập nhật lựa chọn thành công.',
        'deleted' => [
            'title' => 'Đã xóa lựa chọn',
            'body' => 'Lựa chọn trả lời đã được xóa thành công.',
        ],
    ],
];
