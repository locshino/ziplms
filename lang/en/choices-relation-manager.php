<?php

return [
    'labels' => [
        'singular' => 'Answer Choice',
        'plural' => 'Answer Choices',
    ],
    'form' => [
        'tabs' => [
            'vi' => 'Content (Vietnamese)',
            'en' => 'Content (English)',
        ],
        'fields' => [
            'is_correct' => 'Is Correct Answer?',
            'choice_order' => 'Order',
        ],
        'validation' => [
            'unique_order' => 'This order already exists for another choice.',
        ],
    ],
    'table' => [
        'columns' => [
            'choice_text' => 'Content',
            'is_correct' => 'Correct Answer',
            'choice_order' => 'Order',
        ],
        'actions' => [
            'create' => 'Add Choice',
        ],
    ],
    'notifications' => [
        'created' => 'Choice created successfully.',
        'updated' => 'Choice updated successfully.',
        'deleted' => [
            'title' => 'Choice deleted',
            'body' => 'The answer choice has been deleted successfully.',
        ],
    ],
];
