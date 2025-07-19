<?php

return [
    // Navigation and Model Labels
    'navigation' => [
        'group' => 'Quản lý Đánh giá',
        'label' => 'Bài kiểm tra',
        'plural_label' => 'Danh sách Bài kiểm tra',
    ],

    // Form Fields and Sections
    'form' => [
        'section' => [
            'main_content' => 'Nội dung đa ngôn ngữ',
            'settings' => 'Cài đặt & Thuộc tính',
        ],
        'tab' => [
            'vietnamese' => 'Tiếng Việt',
            'english' => 'Tiếng Anh',
        ],
        'field' => [
            'title' => 'Tiêu đề',
            'description' => 'Mô tả / Hướng dẫn',
            'course' => 'Thuộc khóa học',
            'lecture' => 'Thuộc bài giảng',
            'start_time' => 'Thời gian bắt đầu',
            'end_time' => 'Thời gian kết thúc',
            'duration' => 'Thời gian làm bài (phút)',
            'max_attempts' => 'Số lần làm bài tối đa',
            'passing_score' => 'Điểm đạt (%)',
            'show_results' => 'Hiển thị kết quả',
            'shuffle_questions' => 'Xáo trộn câu hỏi?',
            'shuffle_answers' => 'Xáo trộn đáp án?',
        ],
    ],

    // Table Columns and Actions
    'table' => [
        'column' => [
            'title' => 'Tiêu đề',
            'course' => 'Khóa học',
            'status' => 'Trạng thái',
        ],
        'action' => [
            'take_exam' => 'Làm bài',
        ],
    ],

    'relation_manager' => [
        'questions' => [
            'label' => 'Câu hỏi trong bài thi',
            'column' => [
                'question_content' => 'Nội dung câu hỏi',
                'points' => 'Điểm',
                'order' => 'Thứ tự',
            ],
            'action' => [
                'attach' => [
                    'notification_success' => 'Câu hỏi đã được thêm vào bài thi.',
                ],
                'edit' => [
                    'notification_success' => 'Thông tin câu hỏi đã được cập nhật.',
                ],
                'detach' => [
                    'notification_success' => 'Câu hỏi đã được xóa khỏi bài thi.',
                ],
                'detach_bulk' => [
                    'notification_success' => 'Các câu hỏi đã chọn đã được xóa khỏi bài thi.',
                ],
            ],
            'form' => [
                'points' => 'Điểm',
                'order' => 'Thứ tự',
            ],
            'validation' => [
                'order_not_negative' => 'Thứ tự không được là số âm.',
                'order_unique' => 'Thứ tự này đã tồn tại trong bài thi.',
            ],
        ],
    ],
];
