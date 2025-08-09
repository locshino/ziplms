<?php

return [
    'columns' => [
        'id' => 'ID',
        'name' => 'Name',
        'guard_name' => 'Guard Name',
        'team' => 'Team',
        'permissions_count' => 'Permissions',
        'is_system' => 'System Role',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ],
    'fields' => [
        'name' => 'Name',
        'guard_name' => 'Guard Name',
    ],
    'helpers' => [
        'name' => 'Role name must be lowercase letters (including Unicode) and underscores only (no spaces or uppercase letters)',
        'guard_name' => 'The guard name defines which authentication guard this role belongs to (e.g., web, api)',
    ],
    'model' => [
        'singular' => 'Role',
        'plural' => 'Roles',
    ],
    'permission_management' => [
        'tab_label' => 'Manage Permissions',
        'create_section' => [
            'title' => 'Create New Permission',
            'description' => 'Create custom permissions for this role',
            'add_button' => 'Add New Permission',
        ],
        'existing_section' => [
            'title' => 'Existing Custom Permissions',
            'description' => 'Manage existing custom permissions',
        ],
        'fields' => [
            'verb' => 'Verb',
            'noun' => 'Noun',
            'context' => 'Context',
            'attribute_value' => 'Attribute Value',
            'permission_name' => 'Generated Permission Name',
            'guard_name' => 'Guard Name',
        ],
        'helpers' => [
            'attribute_value' => 'Required when context is ID or Tag',
            'permission_name' => 'Auto-generated from verb-noun-context pattern',
        ],
        'save_button' => 'Save Permissions',
        'save_success' => 'Permissions saved successfully!',
    ],
];