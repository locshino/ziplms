<?php

return [
    'label' => 'Chi tiết bài làm',
    'form' => [
        'tabs' => [
            'feedback_vi' => 'Phản hồi (Tiếng Việt)',
            'feedback_en' => 'Feedback (English)',
        ],
        'points_earned' => 'Điểm đạt được',
        'max_points_hint' => 'Điểm tối đa cho câu hỏi này là: :points',
    ],
    'table' => [
        'columns' => [
            'question' => 'Câu hỏi',
            'student_answer' => 'Câu trả lời của HS',
            'result' => 'Kết quả',
            'points_earned' => 'Điểm nhận được',
            'teacher_feedback' => 'Phản hồi GV',
            'correct_answer' => 'Đáp án đúng',

        ],
        'placeholders' => [
            'not_graded' => 'Chưa chấm',
            'no_feedback' => 'Chưa có',
        ],
        'errors' => [
            'unknown_question_type' => 'Không xác định được loại câu hỏi',
        ],
    ],
    'actions' => [
        'grade' => 'Chấm điểm',
        'grade_success_notification' => 'Điểm cho câu trả lời đã được cập nhật.',
        'view_result' => 'Xem/Sửa điểm',
    ],
    'validation' => [
        'points_exceeded' => 'Điểm nhập vào không được lớn hơn điểm tối đa (:max).',
    ],
];
