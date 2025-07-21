<?php

return [
    // Navigation and Model Labels
    'navigation' => [
        'group' => 'Assessment Management',
        'label' => 'Exam',
        'plural_label' => 'Exams',
    ],

    // Form Fields and Sections
    'form' => [
        'section' => [
            'main_content' => 'Multi-language Content',
            'settings' => 'Settings & Properties',
        ],
        'tab' => [
            'vietnamese' => 'Vietnamese',
            'english' => 'English',
        ],
        'field' => [
            'title' => 'Title',
            'description' => 'Description / Instructions',
            'course' => 'Belongs to Course',
            'lecture' => 'Belongs to Lecture',
            'status' => 'Status', // <-- ĐÃ THÊM
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

    // Table Columns and Actions
    'table' => [
        'column' => [
            'title' => 'Title',
            'course' => 'Course',
            'status' => 'Status',
        ],
        'action' => [
            'take_exam' => 'Take Exam',
            'change_status' => 'Change Status',
            'change_status_success_title' => 'Update Successful',
            'change_status_success_body' => 'The exam\'s status has been changed successfully.',
        ],
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
                'attach' => [
                    'notification_success' => 'Question has been added to the exam.',
                ],
                'edit' => [
                    'notification_success' => 'Question information has been updated.',
                ],
                'detach' => [
                    'notification_success' => 'Question has been removed from the exam.',
                ],
                'detach_bulk' => [
                    'notification_success' => 'Selected questions have been removed from the exam.',
                ],
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
