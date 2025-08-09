<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Repository Exception Messages
    |--------------------------------------------------------------------------
    |
    | Các thông báo lỗi sau được sử dụng cho các exception của repository
    | được ném ra bởi các lớp repository khác nhau trong ứng dụng.
    |
    */

    'resource_not_found' => 'Không tìm thấy tài nguyên được yêu cầu.',
    'resource_not_found_with_id' => 'Không tìm thấy tài nguyên với ID :id.',
    'create_failed' => 'Không thể tạo tài nguyên.',
    'create_failed_with_reason' => 'Không thể tạo tài nguyên: :reason',
    'update_failed' => 'Không thể cập nhật tài nguyên.',
    'update_failed_with_id' => 'Không thể cập nhật tài nguyên với ID :id.',
    'update_failed_with_reason' => 'Không thể cập nhật tài nguyên: :reason',
    'delete_failed' => 'Không thể xóa tài nguyên.',
    'delete_failed_with_id' => 'Không thể xóa tài nguyên với ID :id.',
    'delete_failed_with_reason' => 'Không thể xóa tài nguyên: :reason',
    'validation_failed' => 'Xác thực dữ liệu thất bại.',
    'database_error' => 'Đã xảy ra lỗi cơ sở dữ liệu.',
    'constraint_violation' => 'Vi phạm ràng buộc cơ sở dữ liệu.',
    'duplicate_entry' => 'Phát hiện dữ liệu trùng lặp.',
    'foreign_key_constraint' => 'Vi phạm ràng buộc khóa ngoại.',
    'invalid_data' => 'Dữ liệu không hợp lệ.',
    'operation_not_allowed' => 'Thao tác này không được phép.',
    'resource_in_use' => 'Tài nguyên đang được sử dụng và không thể thay đổi.',
    'insufficient_permissions' => 'Không đủ quyền để thực hiện thao tác này.',
];
