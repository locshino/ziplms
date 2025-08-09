<?php

return [
    'columns' => [
        'id' => 'ID',
        'name' => 'Tên',
        'guard_name' => 'Tên Guard',
        'team' => 'Nhóm',
        'permissions_count' => 'Quyền',
        'is_system' => 'Vai trò hệ thống',
        'created_at' => 'Ngày tạo',
        'updated_at' => 'Ngày cập nhật',
    ],
    'fields' => [
        'name' => 'Tên',
        'guard_name' => 'Tên bảo vệ',
    ],
    'helpers' => [
        'name' => 'Tên vai trò chỉ được sử dụng chữ thường (bao gồm Unicode) và dấu gạch dưới (không có khoảng trắng hoặc chữ hoa)',
        'guard_name' => 'Tên guard xác định vai trò này thuộc về guard xác thực nào (ví dụ: web, api)',
    ],
    'model' => [
        'singular' => 'Vai trò',
        'plural' => 'Vai trò',
    ],
    'permission_management' => [
        'tab_label' => 'Quản lý Quyền',
        'create_section' => [
            'title' => 'Tạo Quyền Mới',
            'description' => 'Tạo quyền tùy chỉnh bằng cách chọn kết hợp động từ, danh từ và ngữ cảnh.',
            'add_button' => 'Thêm Quyền Mới',
        ],
        'existing_section' => [
            'title' => 'Quyền Tùy Chỉnh Hiện Có',
            'description' => 'Chọn từ các quyền tùy chỉnh hiện có để gán cho vai trò này.',
        ],
        'fields' => [
            'verb' => 'Động từ',
            'noun' => 'Danh từ',
            'context' => 'Ngữ cảnh',
            'attribute_value' => 'Giá trị Thuộc tính',
            'permission_name' => 'Tên Quyền',
            'guard_name' => 'Tên Guard',
        ],
        'helpers' => [
            'attribute_value' => 'Bắt buộc cho ngữ cảnh ID và Tag (ví dụ: ID người dùng, tên tag)',
            'permission_name' => 'Tự động tạo dựa trên lựa chọn của bạn',
        ],
        'save_button' => 'Lưu Quyền',
        'save_success' => 'Lưu quyền thành công!',
    ],
];
