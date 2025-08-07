<?php

return [
    'columns' => [
        'id' => 'ID',
        'quiz_title' => 'Quiz',
        'title' => 'Title',
        'question_text' => 'Question Text',
        'points' => 'Points',
        'is_multiple_response' => 'Multiple Response',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',
    ],
    'fields' => [
        'quiz_id' => 'Quiz',
        'title' => 'Title',
        'question_text' => 'Question Text',
        'points' => 'Points',
        'is_multiple_response' => 'Multiple Response',
    ],
    'model' => [
        'singular' => 'Question',
        'plural' => 'Questions',
    ],
];