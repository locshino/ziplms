<?php

return [
    'resource' => [
        'label' => 'User',
        'plural_label' => 'Users',
        'navigation_label' => 'Users',
        'navigation_group' => 'User Management',
    ],

    'pages' => [
        'list' => [
            'title' => 'Users',
        ],
        'create' => [
            'title' => 'Create User',
        ],
        'edit' => [
            'title' => 'Edit User',
        ],
        'view' => [
            'title' => 'View User',
        ],
    ],

    'form' => [
        'fields' => [
            'name' => 'Name',
            'email' => 'Email Address',
            'email_verified_at' => 'Email Verified At',
            'password' => 'Password',
            'password_confirmation' => 'Confirm Password',
            'avatar' => 'Avatar',
            'status' => 'Status',
            'roles' => 'Roles',
        ],
        'sections' => [
            'personal_information' => 'Personal Information',
            'account_settings' => 'Account Settings',
            'security' => 'Security',
        ],
        'placeholders' => [
            'name' => 'Enter full name',
            'email' => 'Enter email address',
            'password' => 'Enter password',
            'password_confirmation' => 'Confirm your password',
        ],
        'help_text' => [
            'avatar' => 'Upload a profile picture (JPG, PNG, WebP formats only)',
            'roles' => 'Select user roles (only visible to super administrators)',
            'status' => 'Set the current status of the user account',
        ],
    ],

    'table' => [
        'columns' => [
            'id' => 'ID',
            'avatar' => 'Avatar',
            'name' => 'Name',
            'email' => 'Email',
            'email_verified_at' => 'Email Verified',
            'status' => 'Status',
            'roles' => 'Roles',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
        'filters' => [
            'status' => 'Filter by Status',
            'roles' => 'Filter by Role',
            'email_verified' => 'Email Verification Status',
        ],
        'actions' => [
            'view' => 'View',
            'edit' => 'Edit',
            'delete' => 'Delete',
        ],
    ],

    'infolist' => [
        'sections' => [
            'account_information' => 'Account Information',
            'timestamps' => 'Timestamps',
        ],
        'entries' => [
            'avatar' => 'Avatar',
            'name' => 'Name',
            'email' => 'Email Address',
            'status' => 'Status',
            'roles' => 'Roles',
            'user_id' => 'User ID',
            'email_verified' => 'Email Verified',
            'created' => 'Created',
            'last_updated' => 'Last Updated',
            'deleted' => 'Deleted',
        ],
    ],

    'notifications' => [
        'created' => 'User created successfully',
        'updated' => 'User updated successfully',
        'deleted' => 'User deleted successfully',
        'restored' => 'User restored successfully',
    ],

    'messages' => [
        'no_avatar' => 'No avatar uploaded',
        'email_not_verified' => 'Email not verified',
        'email_verified' => 'Email verified',
        'no_roles_assigned' => 'No roles assigned',
        'not_deleted' => 'Not deleted',
    ],
];
