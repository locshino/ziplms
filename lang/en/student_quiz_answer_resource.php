<?php

return [
    'columns' => [
        'id' => 'ID',
        'quiz_attempt_id' => 'Quiz Attempt',
        'question_title' => 'Question',
        'answer_choice_title' => 'Answer Choice',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ],
    'fields' => [
        'quiz_attempt_id' => 'Quiz Attempt',
        'question_id' => 'Question',
        'answer_choice_id' => 'Answer Choice',
        'answer_text' => 'Answer Text',
        'is_correct' => 'Is Correct',
    ],
    'model' => [
        'singular' => 'Student Quiz Answer',
        'plural' => 'Student Quiz Answers',
    ],
];
