<?php

return [
    'action' => [
        'label' => 'Nhật ký',
        'heading' => 'Nhật ký',
    ],
    'form' => [
        'fields' => [
            'comment' => [
                'label' => 'Bình luận',
                'helper_text' => 'Thêm bình luận vào nhật ký để ghi lại các thay đổi. Các bình luận này không nhằm giao tiếp với người dùng khác. Bạn có thể sử dụng Markdown trong bình luận.',
            ],
        ],
        'buttons' => [
            'save' => 'Lưu',
        ],
    ],
    'events' => [
        'created' => [
            'label' => 'Đã tạo',
        ],
        'updated' => [
            'label' => 'Đã cập nhật',
        ],
        'deleted' => [
            'label' => 'Đã xóa',
        ],
    ],
    'attributes_table' => [
        'columns' => [
            'attribute' => 'Thuộc tính',
            'value' => 'Giá trị',
            'old_value' => 'Giá trị cũ',
            'new_value' => 'Giá trị mới',
        ],
        'values' => [
            'null' => 'Chưa đặt',
            'empty' => 'Trống',
            'unknown' => 'Không xác định',
            'yes' => 'Có',
            'no' => 'Không',
        ],
    ],
];
