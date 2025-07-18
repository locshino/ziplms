<?php

declare(strict_types=1);

return [
    'dashboard' => [
        'title' => 'Trình xem log',
    ],
    'show' => [
        'title' => 'Xem log :log',
    ],
    'navigation' => [
        'group' => 'Log',
        'label' => 'Trình xem log',
        'sort' => 100,
    ],
    'table' => [
        'columns' => [
            'date' => [
                'label' => 'Ngày',
            ],
            'level' => [
                'label' => 'Cấp độ',
            ],
            'message' => [
                'label' => 'Tin nhắn',
            ],
        ],
        'actions' => [
            'view' => [
                'label' => 'Xem',
            ],
            'download' => [
                'label' => 'Tải xuống log :log',
                'bulk' => [
                    'label' => 'Tải xuống các log',
                    'error' => 'Lỗi khi tải xuống log',
                ],
            ],
            'delete' => [
                'label' => 'Xóa log :log',
                'success' => 'Đã xóa log thành công',
                'error' => 'Lỗi khi xóa log',
                'bulk' => [
                    'label' => 'Xóa các log đã chọn',
                ],
            ],
            'close' => [
                'label' => 'Quay lại',
            ],
        ],
        'detail' => [
            'title' => 'Chi tiết',
            'file_path' => 'Đường dẫn tệp',
            'log_entries' => 'Mục nhập',
            'size' => 'Kích thước',
            'created_at' => 'Tạo lúc',
            'updated_at' => 'Cập nhật lúc',
        ],
    ],
    'levels' => [
        'all' => 'Tất cả',
        'emergency' => 'Khẩn cấp',
        'alert' => 'Cảnh báo',
        'critical' => 'Nghiêm trọng',
        'error' => 'Lỗi',
        'warning' => 'Cảnh báo',
        'notice' => 'Thông báo',
        'info' => 'Thông tin',
        'debug' => 'Gỡ lỗi',
    ],
];
