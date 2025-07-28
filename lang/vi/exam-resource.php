<?php

return [
    'navigation' => [
        'group' => 'Quản lý Đánh giá',
        'label' => 'Bài kiểm tra',
        'plural_label' => 'Danh sách Bài kiểm tra',
    ],
    'form' => [
        'section' => [
            'main_content' => 'Nội dung đa ngôn ngữ',
            'settings' => 'Cài đặt & Thuộc tính',
        ],
        'field' => [
            'title' => 'Tiêu đề',
            'description' => 'Mô tả / Hướng dẫn',
            'course' => 'Thuộc khóa học',
            'lecture' => 'Thuộc bài giảng',
            'status' => 'Trạng thái',
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
    'table' => [
        'column' => [
            'title' => 'Tiêu đề',
            'course' => 'Khóa học',
            'status' => 'Trạng thái',
        ],
        'action' => [
            'take_exam' => 'Làm bài',
            'delete' => 'Xóa',
        ],
    ],
    'notification' => [
        'create_success' => 'Bài thi đã được tạo thành công.',
        'update_success' => 'Bài thi đã được cập nhật thành công.',
        'delete_success' => 'Bài thi đã được xóa thành công.',
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
                    'label' => 'Thêm câu hỏi',
                ],
                'edit' => ['notification_success' => 'Thông tin câu hỏi đã được cập nhật.'],
                'detach' => ['notification_success' => 'Câu hỏi đã được gỡ khỏi bài thi.'],
                'delete' => ['notification_success' => 'Câu hỏi đã được xóa vĩnh viễn.'],
                'detach_bulk' => ['notification_success' => 'Các câu hỏi đã chọn đã được gỡ khỏi bài thi.'],
                'delete_bulk' => ['notification_success' => 'Các câu hỏi đã chọn đã được xóa vĩnh viễn.'],
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
