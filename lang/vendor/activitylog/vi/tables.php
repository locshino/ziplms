<?php

return [
    'columns' => [
        'log_name' => [
            'label' => 'Loại',
        ],
        'event' => [
            'label' => 'Sự kiện',
        ],
        'subject_type' => [
            'label' => 'Đối tượng',
            'soft_deleted' => ' (Đã xóa mềm)',
            'deleted' => ' (Đã xóa)',
        ],
        'causer' => [
            'label' => 'Người dùng',
        ],
        'properties' => [
            'label' => 'Thuộc tính',
        ],
        'created_at' => [
            'label' => 'Ghi nhận lúc',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label' => 'Ghi nhận lúc',
            'created_from' => 'Tạo từ ',
            'created_from_indicator' => 'Tạo từ : :created_from',
            'created_until' => 'Tạo đến ',
            'created_until_indicator' => 'Tạo đến : :created_until',
        ],
        'event' => [
            'label' => 'Sự kiện',
        ],
    ],
];
