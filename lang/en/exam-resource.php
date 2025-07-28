<?php

return [
    'navigation' => [
        'group' => 'Assessment Management',
        'label' => 'Exam',
        'plural_label' => 'Exams',
    ],
    'form' => [
        'section' => [
            'main_content' => 'Multi-language Content',
            'settings' => 'Settings & Properties',
        ],
        'field' => [
            'title' => 'Title',
            'description' => 'Description / Instructions',
            'course' => 'Belongs to Course',
            'lecture' => 'Belongs to Lecture',
            'status' => 'Status',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'duration' => 'Duration (minutes)',
            'max_attempts' => 'Max Attempts',
            'passing_score' => 'Passing Score (%)',
            'show_results' => 'Show Results After',
            'shuffle_questions' => 'Shuffle Questions?',
            'shuffle_answers' => 'Shuffle Answers?',
        ],
    ],
    'table' => [
        'column' => [
            'title' => 'Title',
            'course' => 'Course',
            'status' => 'Status',
        ],
        'action' => [
            'take_exam' => 'Take Exam',
        ],
    ],
    'notification' => [
        'create_success' => 'The exam has been created successfully.',
        'update_success' => 'The exam has been updated successfully.',
        'delete_success' => 'The exam has been deleted successfully.',
    ],
    'relation_manager' => [
        'questions' => [
            'label' => 'Questions in the exam',
            'column' => [
                'question_content' => 'Question Content',
                'points' => 'Points',
                'order' => 'Order',
            ],
            'action' => [
                'attach' => ['notification_success' => 'Question has been added to the exam.'],
                'edit' => ['notification_success' => 'Question information has been updated.'],
                'detach' => ['notification_success' => 'Question has been removed from the exam.'],
                'delete' => ['notification_success' => 'The question has been permanently deleted.'],
                'detach_bulk' => ['notification_success' => 'Selected questions have been removed from the exam.'],
                'delete_bulk' => ['notification_success' => 'Selected questions have been permanently deleted.'],
            ],
            'form' => [
                'points' => 'Points',
                'order' => 'Order',
            ],
            'validation' => [
                'order_not_negative' => 'The order cannot be a negative number.',
                'order_unique' => 'This order number already exists in this exam.',
            ],
        ],
    ],
];
