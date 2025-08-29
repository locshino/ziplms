<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | Các dòng ngôn ngữ sau được sử dụng cho các thông báo lỗi khác nhau
    | được ném ra trong toàn bộ ứng dụng. Bạn có thể tự do chỉnh sửa
    | các dòng ngôn ngữ này theo yêu cầu của ứng dụng.
    |
    */

    // Base Application Exception
    'application_error' => 'Đã xảy ra lỗi ứng dụng.',
    'application_error_with_reason' => 'Đã xảy ra lỗi ứng dụng: :reason',

    // Repository Exceptions
    'repositories' => [
        'resource_not_found' => 'Không tìm thấy tài nguyên được yêu cầu.',
        'resource_not_found_with_id' => 'Không tìm thấy tài nguyên với ID ":id".',
        'resource_not_found_with_reason' => 'Không tìm thấy tài nguyên: :reason',

        'create_failed' => 'Không thể tạo tài nguyên.',
        'create_failed_with_reason' => 'Không thể tạo tài nguyên: :reason',

        'update_failed' => 'Không thể cập nhật tài nguyên.',
        'update_failed_with_id' => 'Không thể cập nhật tài nguyên với ID ":id".',
        'update_failed_with_reason' => 'Không thể cập nhật tài nguyên: :reason',

        'delete_failed' => 'Không thể xóa tài nguyên.',
        'delete_failed_with_id' => 'Không thể xóa tài nguyên với ID ":id".',
        'delete_failed_with_reason' => 'Không thể xóa tài nguyên: :reason',

        'validation_failed' => 'Xác thực dữ liệu thất bại.',
        'validation_failed_with_reason' => 'Xác thực thất bại: :reason',

        'database_error' => 'Đã xảy ra lỗi cơ sở dữ liệu.',
        'database_error_with_reason' => 'Lỗi cơ sở dữ liệu: :reason',

        'duplicate_entry' => 'Phát hiện mục nhập trùng lặp.',
        'duplicate_entry_with_reason' => 'Mục nhập trùng lặp: :reason',

        'resource_in_use' => 'Tài nguyên hiện đang được sử dụng và không thể chỉnh sửa.',
        'resource_in_use_with_reason' => 'Tài nguyên đang được sử dụng: :reason',
    ],

    // Service Exceptions
    'services' => [
        'service_error' => 'Đã xảy ra lỗi dịch vụ.',
        'service_error_with_reason' => 'Lỗi dịch vụ: :reason',

        'business_logic_error' => 'Đã xảy ra lỗi logic nghiệp vụ.',
        'business_logic_error_with_reason' => 'Lỗi logic nghiệp vụ: :reason',

        'operation_failed' => 'Thao tác không thể hoàn thành.',
        'operation_failed_with_reason' => 'Thao tác thất bại: :reason',

        'operation_not_permitted' => 'Thao tác không được phép.',
        'operation_not_permitted_with_reason' => 'Thao tác không được phép: :reason',

        'invalid_input' => 'Dữ liệu đầu vào không hợp lệ.',
        'invalid_input_with_reason' => 'Dữ liệu đầu vào không hợp lệ: :reason',

        'validation_error' => 'Xác thực dữ liệu đầu vào thất bại.',
        'validation_error_with_reason' => 'Lỗi xác thực: :reason',

        'authorization_failed' => 'Xác thực quyền truy cập thất bại.',
        'authorization_failed_with_reason' => 'Xác thực quyền truy cập thất bại: :reason',

        'resource_conflict' => 'Đã xảy ra xung đột tài nguyên.',
        'resource_conflict_with_reason' => 'Xung đột tài nguyên: :reason',

        'dependency_error' => 'Đã xảy ra lỗi phụ thuộc.',
        'dependency_error_with_reason' => 'Lỗi phụ thuộc: :reason',

        'transaction_failed' => 'Giao dịch cơ sở dữ liệu thất bại.',
        'transaction_failed_with_reason' => 'Giao dịch thất bại: :reason',

        'external_service_error' => 'Đã xảy ra lỗi dịch vụ bên ngoài.',
        'external_service_error_with_reason' => 'Lỗi dịch vụ bên ngoài: :reason',

        'configuration_error' => 'Đã xảy ra lỗi cấu hình.',
        'configuration_error_with_reason' => 'Lỗi cấu hình: :reason',

        'timeout_error' => 'Thao tác đã hết thời gian chờ.',
        'timeout_error_with_reason' => 'Lỗi hết thời gian chờ: :reason',

        'rate_limit_exceeded' => 'Đã vượt quá giới hạn tốc độ.',
        'rate_limit_exceeded_with_reason' => 'Vượt quá giới hạn tốc độ: :reason',

        'quota_exceeded' => 'Đã vượt quá hạn ngạch.',
        'quota_exceeded_with_reason' => 'Vượt quá hạn ngạch: :reason',

        'service_unavailable' => 'Dịch vụ hiện không khả dụng.',
        'service_unavailable_with_reason' => 'Dịch vụ không khả dụng: :reason',

        'maintenance_mode' => 'Hệ thống hiện đang trong chế độ bảo trì.',
        'maintenance_mode_with_reason' => 'Chế độ bảo trì: :reason',

        'feature_disabled' => 'Tính năng này hiện đang bị vô hiệu hóa.',
        'feature_disabled_with_reason' => 'Tính năng bị vô hiệu hóa: :reason',

        'insufficient_data' => 'Không đủ dữ liệu để hoàn thành thao tác.',
        'insufficient_data_with_reason' => 'Dữ liệu không đủ: :reason',

        'data_integrity_error' => 'Đã xảy ra lỗi toàn vẹn dữ liệu.',
        'data_integrity_error_with_reason' => 'Lỗi toàn vẹn dữ liệu: :reason',
    ],
];
