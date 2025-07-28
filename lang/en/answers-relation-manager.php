<?php

return [
    'label' => 'Answer Details',
    'form' => [
        'tabs' => [
            'feedback_vi' => 'Feedback (Vietnamese)',
            'feedback_en' => 'Feedback (English)',
        ],
        'points_earned' => 'Points Earned',
        'max_points_hint' => 'The maximum points for this question is: :points',
    ],
    'table' => [
        'columns' => [
            'question' => 'Question',
            'student_answer' => 'Student\'s Answer',
            'result' => 'Result',
            'points_earned' => 'Points Earned',
            'teacher_feedback' => 'Teacher Feedback',
            'correct_answer' => 'Correct Answer',
        ],
        'placeholders' => [
            'not_graded' => 'Not graded',
            'no_feedback' => 'No feedback yet',
        ],
        'errors' => [
            'unknown_question_type' => 'Could not determine question type',
        ],
    ],
    'actions' => [
        'grade' => 'Grade',
        'grade_success_notification' => 'The score for the answer has been updated.',
        'view_result' => 'View/Edit Score',
    ],
    'validation' => [
        'points_exceeded' => 'The entered points cannot be greater than the maximum (:max).',
    ],
];
