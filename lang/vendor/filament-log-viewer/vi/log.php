<?php

declare(strict_types=1);

return [
  'dashboard' => [
    'title' => 'Trình xem nhật ký',
  ],
  'show' => [
    'title' => 'Xem nhật ký :log',
  ],
  'navigation' => [
    'group' => 'Nhật ký',
    'label' => 'Trình xem nhật ký',
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
        'label' => 'Thông báo',
      ],
    ],
    'actions' => [
      'view' => [
        'label' => 'Xem',
      ],
      'download' => [
        'label' => 'Tải xuống nhật ký :log',
        'bulk' => [
          'label' => 'Tải xuống nhật ký',
          'error' => 'Lỗi khi tải xuống nhật ký',
        ],
      ],
      'delete' => [
        'label' => 'Xóa nhật ký :log',
        'success' => 'Đã xóa nhật ký thành công',
        'error' => 'Lỗi khi xóa nhật ký',
        'bulk' => [
          'label' => 'Xóa các nhật ký đã chọn',
        ],
      ],
      'close' => [
        'label' => 'Quay lại',
      ],
    ],
    'detail' => [
      'title' => 'Chi tiết',
      'file_path' => 'Đường dẫn tệp',
      'log_entries' => 'Mục',
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
