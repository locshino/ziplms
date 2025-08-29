<?php

return [
    'resource' => [
        'label' => 'Người dùng',
        'plural_label' => 'Người dùng',
        'navigation_label' => 'Người dùng',
        'navigation_group' => 'Quản lý người dùng',
    ],

    'pages' => [
        'list' => [
            'title' => 'Danh sách người dùng',
        ],
        'create' => [
            'title' => 'Tạo người dùng',
        ],
        'edit' => [
            'title' => 'Chỉnh sửa người dùng',
        ],
        'view' => [
            'title' => 'Xem thông tin người dùng',
        ],
    ],

    'form' => [
        'fields' => [
            'name' => 'Họ và tên',
            'email' => 'Địa chỉ email',
            'email_verified_at' => 'Email được xác thực lúc',
            'password' => 'Mật khẩu',
            'password_confirmation' => 'Xác nhận mật khẩu',
            'avatar' => 'Ảnh đại diện',
            'status' => 'Trạng thái',
            'roles' => 'Vai trò',
            'force_renew_password' => 'Buộc đổi mật khẩu',
        ],
        'sections' => [
            'personal_information' => 'Thông tin cá nhân',
            'account_settings' => 'Cài đặt tài khoản',
            'security' => 'Bảo mật',
        ],
        'placeholders' => [
            'name' => 'Nhập họ và tên',
            'email' => 'Nhập địa chỉ email',
            'password' => 'Nhập mật khẩu',
            'password_confirmation' => 'Xác nhận mật khẩu của bạn',
        ],
        'help_text' => [
            'avatar' => 'Tải lên ảnh đại diện (chỉ hỗ trợ định dạng JPG, PNG, WebP)',
            'roles' => 'Chọn vai trò người dùng (chỉ hiển thị với quản trị viên cấp cao)',
            'status' => 'Thiết lập trạng thái hiện tại của tài khoản người dùng',
        ],
    ],

    'table' => [
        'columns' => [
            'id' => 'ID',
            'avatar' => 'Ảnh đại diện',
            'name' => 'Họ và tên',
            'email' => 'Email',
            'email_verified_at' => 'Email đã xác thực',
            'status' => 'Trạng thái',
            'roles' => [
                'name' => 'Vai trò',
            ],
            'force_renew_password' => 'Buộc đổi mật khẩu',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'deleted_at' => 'Ngày xóa',
        ],
        'filters' => [
            'status' => 'Lọc theo trạng thái',
            'roles' => 'Lọc theo vai trò',
            'email_verified' => 'Trạng thái xác thực email',
        ],
        'actions' => [
            'view' => 'Xem',
            'edit' => 'Chỉnh sửa',
            'delete' => 'Xóa',
        ],
    ],

    'infolist' => [
        'sections' => [
            'account_information' => 'Thông tin tài khoản',
            'timestamps' => 'Thời gian',
        ],
        'entries' => [
            'avatar' => 'Ảnh đại diện',
            'name' => 'Họ và tên',
            'email' => 'Địa chỉ email',
            'email_verified_at' => 'Email được xác thực lúc',
            'status' => 'Trạng thái',
            'roles' => [
                'name' => 'Vai trò',
            ],
            'force_renew_password' => 'Buộc đổi mật khẩu',
            'user_id' => 'ID người dùng',
            'email_verified' => 'Email đã xác thực',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Cập nhật lần cuối',
            'deleted_at' => 'Đã xóa',
        ],
    ],

    'notifications' => [
        'created' => 'Tạo người dùng thành công',
        'updated' => 'Cập nhật người dùng thành công',
        'deleted' => 'Xóa người dùng thành công',
        'restored' => 'Khôi phục người dùng thành công',
    ],

    'messages' => [
        'no_avatar' => 'Chưa tải lên ảnh đại diện',
        'email_not_verified' => 'Email chưa được xác thực',
        'email_verified' => 'Email đã được xác thực',
        'no_roles_assigned' => 'Chưa được gán vai trò',
        'not_deleted' => 'Chưa bị xóa',
    ],
];
