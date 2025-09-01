<?php

return [
    'resource' => [
        'label' => 'Answer Choice',
        'plural_label' => 'Answer Choices',
        'navigation_label' => 'Answer Choices',
        'navigation_group' => 'Quiz Management',
    ],
    'pages' => [
        'list' => ['title' => 'Answer Choices'],
        'create' => ['title' => 'Create Answer Choice'],
        'edit' => ['title' => 'Edit Answer Choice'],
    ],
    'form' => [
        'fields' => [
            'question_id' => 'Question',
            'is_multi_choice' => 'Multiple Choice',
            'answer_choices' => 'Answer Choices',
            'title' => 'Title',
            'description' => 'Description',
            'is_correct' => 'Is Correct',
        ],
    ],
    'table' => [
        'columns' => [
            'id' => 'ID',
            'question' => [
                'title' => 'Question',
            ],
            'title' => 'Answer',
            'is_correct' => 'Correct',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
    ],
];
