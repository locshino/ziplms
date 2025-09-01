<?php

return [
    'resource' => [
        'label' => 'Submission',
        'plural_label' => 'Submissions',
        'navigation_label' => 'Submissions',
        'navigation_group' => 'Assignment Management',
    ],
    'form' => [
        'fields' => [
            'assignment_id' => 'Assignment',
            'student_id' => 'Student',
            'content' => 'Content',
            'status' => 'Status',
            'submitted_at' => 'Submitted At',
            'graded_by' => 'Graded By',
            'points' => 'Points',
            'feedback' => 'Feedback',
            'graded_at' => 'Graded At',
        ],
    ],
    'table' => [
        'columns' => [
            'id' => 'ID',
            'assignment' => [
                'title' => 'Assignment',
            ],
            'student' => [
                'name' => 'Student',
            ],
            'status' => 'Status',
            'submitted_at' => 'Submitted At',
            'grader' => [
                'name' => 'Graded By',
            ],
            'points' => 'Points',
            'graded_at' => 'Graded At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
    ],
    'infolist' => [
        'entries' => [
            'id' => 'ID',
            'assignment' => [
                'title' => 'Assignment',
            ],
            'student' => [
                'name' => 'Student',
            ],
            'status' => 'Status',
            'submitted_at' => 'Submitted At',
            'graded_by' => 'Graded By',
            'points' => 'Points',
            'graded_at' => 'Graded At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
    ],
];
