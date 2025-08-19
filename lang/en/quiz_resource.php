<?php

return [
    'columns' => [
        'id' => 'ID',
        'course_title' => 'Course',
        'title' => 'Title',
        'max_points' => 'Max Points',
        'max_attempts' => 'Max Attempts',
        'is_single_session' => 'Single Session',
        'time_limit_minutes' => 'Time Limit (Minutes)',
        'start_at' => 'Start At',
        'end_at' => 'End At',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',
    ],
    'fields' => [
        'course_id' => 'Course',
        'title' => 'Title',
        'description' => 'Description',
        'max_points' => 'Max Points',
        'max_attempts' => 'Max Attempts',
        'is_single_session' => 'Single Session',
        'time_limit_minutes' => 'Time Limit (Minutes)',
        'start_at' => 'Start At',
        'end_at' => 'End At',
    ],
    'model' => [
        'singular' => 'Quiz',
        'plural' => 'Quizzes',
    ],
];
