<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Repository Exception Messages
    |--------------------------------------------------------------------------
    |
    | Các thông báo lỗi sau được sử dụng cho các exception cụ thể của
    | UserRepository được ném ra bởi lớp UserRepository.
    |
    */

    'user_not_found' => 'Không tìm thấy người dùng.',
    'user_not_found_with_id' => 'Không tìm thấy người dùng với ID :id.',
    'user_not_found_with_email' => 'Không tìm thấy người dùng với email :email.',
    'email_already_exists' => 'Địa chỉ email đã tồn tại.',
    'email_already_taken' => 'Email :email đã được sử dụng.',
    'invalid_email_format' => 'Định dạng email không hợp lệ.',
    'password_too_weak' => 'Mật khẩu không đáp ứng yêu cầu bảo mật.',
    'invalid_role' => 'Vai trò không hợp lệ.',
    'role_not_found' => 'Không tìm thấy vai trò :role.',
    'user_already_has_role' => 'Người dùng đã có vai trò này.',
    'user_does_not_have_role' => 'Người dùng không có vai trò này.',
    'cannot_delete_admin' => 'Không thể xóa tài khoản quản trị viên.',
    'cannot_modify_own_role' => 'Không thể thay đổi vai trò của chính mình.',
    'user_is_inactive' => 'Tài khoản người dùng không hoạt động.',
    'user_is_suspended' => 'Tài khoản người dùng bị tạm ngưng.',
    'invalid_user_status' => 'Trạng thái người dùng không hợp lệ.',
    'create_user_failed' => 'Không thể tạo tài khoản người dùng.',
    'update_user_failed' => 'Không thể cập nhật thông tin người dùng.',
    'delete_user_failed' => 'Không thể xóa tài khoản người dùng.',
    'user_has_active_enrollments' => 'Không thể xóa người dùng có đăng ký khóa học đang hoạt động.',
    'user_has_pending_assignments' => 'Không thể xóa người dùng có bài tập chưa hoàn thành.',
    'instructor_has_active_courses' => 'Không thể xóa giảng viên có khóa học đang hoạt động.',
];
