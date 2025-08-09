<?php

return [
    'columns' => [
        'id' => 'ID',
        'name' => 'Name',
        'guard_name' => 'Guard Name',
        'is_system' => 'System Permission',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',
    ],
    'fields' => [
        'name' => 'Name',
        'guard_name' => 'Guard Name',
        'is_system' => 'System Permission',
    ],
    'model' => [
        'singular' => 'Permission',
        'plural' => 'Permissions',
    ],
];
