<?php

return [
    'columns' => [
        'id' => 'ID',
        'name' => 'Tên',
        'email' => 'Email',
        'email_verified_at' => 'Email xác thực lúc',
        'created_at' => 'Ngày tạo',
        'updated_at' => 'Ngày cập nhật',
        'deleted_at' => 'Ngày xóa',
    ],
    'fields' => [

        'name' => 'Tên',
        'email' => 'Email',
        'password' => 'Mật khẩu',
        'email_verified_at' => 'Email xác thực lúc',
        'avatar' => 'Ảnh',
        'roles' => 'Vai trò',
    ],
    'sections' => [
        'basic_info' => 'Thông tin cơ bản',
        'authorization' => 'Phân quyền người dùng',
        'description' => 'Chọn một hoặc nhiều vai trò cho người dùng.',
        'update' => 'Tải lên hình ảnh của bạn.',
    ],
    'model' => [
        'singular' => 'Người dùng',
        'plural' => 'Người dùng',
    ],
    'header' => [
        'email' => 'Email',
        'add_course_button' => 'Thêm vào khóa học',
        'select_course_placeholder' => '-- Chọn khóa học --',
        'save_button' => 'Lưu',
    ],
    'tabs' => [
        'course_number' => 'Số khóa học',
        'badge' => 'Huy hiệu',
    ],
    'course_list' => [
        'no_title' => 'Không có tiêu đề',
        'no_description' => 'Không có mô tả',
        'not_enrolled' => 'Chưa tham gia môn học',
        'not_enrolled_detail' => 'Bạn chưa đăng ký môn học nào. Hãy khám phá và đăng ký ngay hôm nay!',
        'progress_text' => '{progress}%',
    ],
    'badge_list' => [
        'no_badges' => 'Chưa có huy hiệu nào',
    ],
];