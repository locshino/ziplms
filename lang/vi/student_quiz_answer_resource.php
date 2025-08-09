<?php

return [
    'columns' => [
        'id' => 'ID',
        'quiz_attempt_id' => 'Lần thử',
        'question_title' => 'Câu hỏi',
        'answer_choice_title' => 'Lựa chọn',
        'created_at' => 'Ngày tạo',
        'updated_at' => 'Ngày cập nhật',
    ],
    'fields' => [
        'quiz_attempt_id' => 'Lần làm bài',
        'question_id' => 'Câu hỏi',
        'answer_choice_id' => 'Lựa chọn đáp án',
        'answer_text' => 'Nội dung trả lời',
        'is_correct' => 'Đúng',
    ],
    'model' => [
        'singular' => 'Câu trả lời của học sinh',
        'plural' => 'Câu trả lời của học sinh',
    ],
];
