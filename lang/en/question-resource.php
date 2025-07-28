<?php

return [
    // ... other sections
    'navigation' => [
        'group' => 'Assessment Management',
        'label' => 'Question',
        'plural_label' => 'Questions',
    ],
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
    // FIXED: Added 'create_success' key
    'notification' => [
        'create_success' => 'Question created successfully.',
        'update_success' => 'Question updated successfully.',
        'delete_success' => 'Question deleted successfully.',
    ],
];
