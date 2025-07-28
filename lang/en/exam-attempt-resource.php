<?php

return [
    // Navigation and Model Labels
    'navigation' => [
        'group' => 'Assessment Management',
        'label' => 'Attempt',
        'plural_label' => 'Attempts',
    ],
    'notification' => [
        'update_success' => 'The attempt has been updated successfully.',
        'delete_success' => 'The attempt has been deleted successfully.',
    ],
    // Infolist
    'infolist' => [
        'section' => [
            'general_info' => 'General Information',
            'results' => 'Results',
            'timestamps' => 'Timestamps',
        ],
        'field' => [
            'exam_title' => 'Exam',
            'student_name' => 'Student',
            'score' => 'Score',
            'status' => 'Status',
            'time_spent' => 'Time Spent',
            'started_at' => 'Started At',
            'completed_at' => 'Completed At',
        ],
    ],

    // Table
    'table' => [
        'column' => [
            'exam_title' => 'Exam',
            'student_name' => 'Student',
            'score' => 'Score',
            'status' => 'Status',
            'submission_date' => 'Submission Date',
        ],
        'action' => [
            'view_details' => 'View Details',
            'grade' => 'Grade',
            'delete' => 'Delete',
        ],
    ],
];
