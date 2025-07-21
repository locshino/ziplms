<?php

return [
    // Navigation and Model Labels
    'navigation' => [
        'group' => 'Quản lý Đánh giá',
        'label' => 'Câu hỏi',
        'plural_label' => 'Danh Sách Câu hỏi',
    ],

    // Form Fields and Sections
    'form' => [
        'section' => [
            'question_details' => 'Chi tiết câu hỏi',
            'attributes' => 'Thuộc tính',
        ],
        'field' => [
            'question_text' => 'Nội dung câu hỏi',
            'explanation' => 'Giải thích đáp án (nếu có)',
            'question_type' => 'Loại câu hỏi',
            'classification_tags' => 'Thẻ phân loại',
            'organization' => 'Tổ chức',
        ],
    ],

    // Table Columns
    'table' => [
        'column' => [
            'question_text' => 'Nội dung câu hỏi',
            'question_type' => 'Loại câu hỏi',
            'organization_type' => 'Loại hình Tổ chức',
            'organization' => 'Tổ chức',
            'updated_at' => 'Ngày cập nhật',
        ],
        'filter' => [
            'question_type' => 'Lọc theo Loại câu hỏi',
            'organization_type' => 'Lọc theo Loại hình Tổ chức',
        ],

    ],

    // Notifications
    'notification' => [
        'update_success' => 'Câu hỏi đã được cập nhật thành công.',
        'delete_success' => 'Câu hỏi đã được xóa thành công.',
    ],
];
