<?php

return [
    'model_label' => 'Người dùng',
    'model_label_plural' => 'Người dùng',

    'form' => [
        'section' => [
            'main' => 'Thông tin người dùng',
            'roles_permissions' => 'Vai trò, Tổ chức & Lớp học',
            'learning_stats' => 'Thống kê học tập',
            'status_history' => 'Trạng thái & Lịch sử',
        ],
        'name' => 'Họ và tên',
        'email' => 'Địa chỉ Email',
        'password' => 'Mật khẩu',
        'password_confirmation' => 'Xác nhận mật khẩu',
        'code' => 'Mã người dùng',
        'phone_number' => 'Số điện thoại',
        'address' => 'Địa chỉ',
        'profile_picture' => 'Ảnh đại diện',
        'roles' => 'Vai trò',
        'status' => 'Trạng thái',
        'organizations' => 'Tổ chức',
        'classes_majors' => 'Lớp/Chuyên ngành',
    ],

    'table' => [
        'profile_picture' => 'Ảnh',
        'code' => 'Mã',
        'name' => 'Tên',
        'organizations' => 'Tổ chức',
        'classes_majors' => 'Lớp/Chuyên ngành',
        'roles' => 'Vai trò',
        'status' => 'Trạng thái',
        'created_at' => 'Ngày tạo',
        'null_text' => 'Chưa có',
    ],

    'infolist' => [
        'registered_courses' => 'Khóa học đã đăng ký',
        'completed_courses' => 'Khóa học đã hoàn thành',
        'learning_progress' => 'Tiến độ khóa học',
        'progress_and_stats_section_title' => 'Thống kê & Tiến độ học tập',
        'lecture_completion' => 'Hoàn thành bài giảng',
        'verified_at' => 'Đã xác thực lúc',
        'joined_at' => 'Tham gia lúc',
        'last_updated' => 'Cập nhật lần cuối',
        'not_updated' => 'Chưa cập nhật',
        'not_verified' => 'Chưa xác thực',
    ],

    'filters' => [
        'roles' => 'Vai trò',
        'organizations' => 'Tổ chức',
        'classes_majors' => 'Lớp/Chuyên ngành',
        'status' => 'Trạng thái',
    ],

    'actions' => [
        'view' => 'Xem',
        'edit' => 'Sửa',
        'delete' => 'Xóa',
        'delete_notification_title' => 'Đã xóa người dùng',
        'delete_notification_body' => 'Người dùng đã được xóa khỏi hệ thống.',
        'export_selected' => 'Xuất mục đã chọn',
        'bulk_delete_confirmation' => 'Bạn có chắc chắn muốn xóa những người dùng đã chọn không?',
        'bulk_delete_success_title' => 'Đã xóa người dùng',
        'bulk_delete_success_body' => 'Những người dùng đã chọn đã được xóa khỏi hệ thống.',
        'import_students' => 'Nhập Học sinh',
        'import_teachers' => 'Nhập Giáo viên',
        'import_from_csv' => 'Nhập từ CSV',
    ],

    'pages' => [
        'create_with_role' => 'Tạo mới :role',
        'create_success_title' => 'Đã tạo người dùng',
        'create_success_body' => 'Một người dùng mới đã được thêm vào hệ thống.',
    ],

    'relation_manager' => [
        'tab_title' => 'Các Bài giảng',
        'table' => [
            'title' => 'Tiêu đề bài giảng',
            'status' => 'Trạng thái',
            'completed_at' => 'Hoàn thành lúc',
        ],
        'status' => [
            'not_started' => 'Chưa bắt đầu',
            'in_progress' => 'Đang học',
            'completed' => 'Đã hoàn thành',
        ],
        'actions' => [
            'edit_progress' => 'Sửa tiến độ',
        ],
    ],
];
