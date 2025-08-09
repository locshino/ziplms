<?php

return [
    'columns' => [
        'id' => 'ID',
        'quiz_title' => 'Quiz',
        'student_name' => 'Student',
        'attempt_number' => 'Attempt Number',
        'status' => 'Status',
        'score' => 'Score',
        'started_at' => 'Started At',
        'completed_at' => 'Completed At',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
],
    'fields' => [
        'quiz_id' => 'Quiz',
        'user_id' => 'User',
        'score' => 'Score',
        'started_at' => 'Started At',
        'completed_at' => 'Completed At',
        'student_id' => 'Student',
        'attempt_number' => 'Attempt Number',
        'status' => 'Status',
],
    'model' => [
        'singular' => 'Quiz Attempt',
        'plural' => 'Quiz Attempts',
    ],
];
