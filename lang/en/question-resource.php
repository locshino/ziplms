<?php

return [
    // Navigation and Model Labels
    'navigation' => [
        'group' => 'Assessment Management',
        'label' => 'Question',
        'plural_label' => 'Questions',
    ],

    // Form Fields and Sections
    'form' => [
        'section' => [
            'question_details' => 'Question Details',
            'attributes' => 'Attributes',
        ],
        'field' => [
            'question_text' => 'Question Content',
            'explanation' => 'Answer Explanation',
            'question_type' => 'Question Type',
            'classification_tags' => 'Classification Tags',
            'organization' => 'Organization',
        ],
    ],

    // Table Columns
    'table' => [
        'column' => [
            'question_text' => 'Question Content',
            'question_type' => 'Question Type',
            'organization_type' => 'Organization Type',
            'organization' => 'Organization',
            'updated_at' => 'Last Updated',
        ],
        'filter' => [
            'question_type' => 'Filter by Question Type',
            'organization_type' => 'Filter by Organization Type',
        ],
    ],

    // Notifications
    'notification' => [
        'update_success' => 'Question updated successfully.',
        'delete_success' => 'Question deleted successfully.',
    ],
];
