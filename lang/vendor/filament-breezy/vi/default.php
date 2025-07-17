<?php

return [
    'password_confirm' => [
        'heading' => 'Xác nhận mật khẩu',
        'description' => 'Vui lòng xác nhận mật khẩu của bạn để hoàn tất hành động này.',
        'current_password' => 'Mật khẩu hiện tại',
    ],
    'two_factor' => [
        'heading' => 'Xác thực hai yếu tố',
        'description' => 'Vui lòng xác nhận truy cập vào tài khoản của bạn bằng cách nhập mã được cung cấp bởi ứng dụng xác thực của bạn.',
        'code_placeholder' => 'XXX-XXX',
        'recovery' => [
            'heading' => 'Xác thực hai yếu tố',
            'description' => 'Vui lòng xác nhận truy cập vào tài khoản của bạn bằng cách nhập một trong các mã khôi phục khẩn cấp của bạn.',
        ],
        'recovery_code_placeholder' => 'abcdef-98765',
        'recovery_code_text' => 'Mất thiết bị?',
        'recovery_code_link' => 'Sử dụng mã khôi phục',
        'back_to_login_link' => 'Quay lại đăng nhập',
    ],
    'profile' => [
        'account' => 'Tài khoản',
        'profile' => 'Hồ sơ',
        'my_profile' => 'Hồ sơ của tôi',
        'subheading' => 'Quản lý hồ sơ người dùng của bạn tại đây.',
        'personal_info' => [
            'heading' => 'Thông tin cá nhân',
            'subheading' => 'Quản lý thông tin cá nhân của bạn.',
            'submit' => [
                'label' => 'Cập nhật',
            ],
            'notify' => 'Hồ sơ đã được cập nhật thành công!',
        ],
        'password' => [
            'heading' => 'Mật khẩu',
            'subheading' => 'Phải có ít nhất 8 ký tự.',
            'submit' => [
                'label' => 'Cập nhật',
            ],
            'notify' => 'Mật khẩu đã được cập nhật thành công!',
        ],
        '2fa' => [
            'title' => 'Xác thực hai yếu tố',
            'description' => 'Quản lý xác thực hai yếu tố cho tài khoản của bạn (khuyến khích).',
            'actions' => [
                'enable' => 'Bật',
                'regenerate_codes' => 'Tạo lại mã khôi phục',
                'disable' => 'Tắt',
                'confirm_finish' => 'Xác nhận & hoàn tất',
                'cancel_setup' => 'Hủy thiết lập',
            ],
            'setup_key' => 'Khóa thiết lập',
            'must_enable' => 'Bạn phải bật xác thực hai yếu tố để sử dụng ứng dụng này.',
            'not_enabled' => [
                'title' => 'Bạn chưa bật xác thực hai yếu tố.',
                'description' => 'Khi xác thực hai yếu tố được bật, bạn sẽ được yêu cầu nhập mã bảo mật ngẫu nhiên trong quá trình xác thực. Bạn có thể sử dụng các ứng dụng xác thực trên điện thoại thông minh của bạn như Google Authenticator, Microsoft Authenticator, v.v. để thực hiện điều này.',
            ],
            'finish_enabling' => [
                'title' => 'Hoàn tất việc bật xác thực hai yếu tố.',
                'description' => 'Để hoàn tất việc bật xác thực hai yếu tố, hãy quét mã QR sau bằng ứng dụng xác thực trên điện thoại của bạn hoặc nhập khóa thiết lập và cung cấp mã OTP được tạo.',
            ],
            'enabled' => [
                'notify' => 'Xác thực hai yếu tố đã được bật.',
                'title' => 'Bạn đã bật xác thực hai yếu tố!',
                'description' => 'Xác thực hai yếu tố hiện đã được bật. Điều này giúp làm cho tài khoản của bạn an toàn hơn.',
                'store_codes' => 'Các mã này có thể được sử dụng để khôi phục truy cập vào tài khoản của bạn nếu bạn mất thiết bị. Cảnh báo! Các mã này chỉ được hiển thị một lần.',
            ],
            'disabling' => [
                'notify' => 'Xác thực hai yếu tố đã bị tắt.',
            ],
            'regenerate_codes' => [
                'notify' => 'Đã tạo lại mã khôi phục mới.',
            ],
            'confirmation' => [
                'success_notification' => 'Mã đã được xác minh. Xác thực hai yếu tố đã được bật.',
                'invalid_code' => 'Mã bạn đã nhập không hợp lệ.',
            ],
        ],
        'sanctum' => [
            'title' => 'API Tokens',
            'description' => 'Quản lý các token API cho phép các dịch vụ bên thứ ba truy cập ứng dụng này thay mặt bạn.',
            'create' => [
                'notify' => 'Token đã được tạo thành công!',
                'message' => 'Token của bạn chỉ được hiển thị một lần khi tạo. Nếu bạn mất token, bạn sẽ cần xóa nó và tạo một token mới.',
                'submit' => [
                    'label' => 'Tạo',
                ],
            ],
            'update' => [
                'notify' => 'Token đã được cập nhật thành công!',
                'submit' => [
                    'label' => 'Cập nhật',
                ],
            ],
            'copied' => [
                'label' => 'Tôi đã sao chép token của mình',
            ],
        ],
        'browser_sessions' => [
            'heading' => 'Phiên trình duyệt',
            'subheading' => 'Quản lý các phiên hoạt động của bạn.',
            'label' => 'Phiên trình duyệt',
            'content' => 'Nếu cần thiết, bạn có thể đăng xuất khỏi tất cả các phiên trình duyệt khác trên tất cả các thiết bị của bạn. Một số phiên gần đây của bạn được liệt kê bên dưới; tuy nhiên, danh sách này có thể không đầy đủ. Nếu bạn cảm thấy tài khoản của mình đã bị xâm phạm, bạn cũng nên cập nhật mật khẩu của mình.',
            'device' => 'Thiết bị này',
            'last_active' => 'Hoạt động lần cuối',
            'logout_other_sessions' => 'Đăng xuất các phiên trình duyệt khác',
            'logout_heading' => 'Đăng xuất các phiên trình duyệt khác',
            'logout_description' => 'Vui lòng nhập mật khẩu của bạn để xác nhận rằng bạn muốn đăng xuất khỏi các phiên trình duyệt khác trên tất cả các thiết bị của bạn.',
            'logout_action' => 'Đăng xuất các phiên trình duyệt khác',
            'incorrect_password' => 'Mật khẩu bạn nhập không chính xác. Vui lòng thử lại.',
            'logout_success' => 'Tất cả các phiên trình duyệt khác đã được đăng xuất thành công.',
        ],
    ],
    'clipboard' => [
        'link' => 'Sao chép vào clipboard',
        'tooltip' => 'Đã sao chép!',
    ],
    'fields' => [
        'avatar' => 'Ảnh đại diện',
        'email' => 'Email',
        'login' => 'Đăng nhập',
        'name' => 'Tên',
        'password' => 'Mật khẩu',
        'password_confirm' => 'Xác nhận mật khẩu',
        'new_password' => 'Mật khẩu mới',
        'new_password_confirmation' => 'Xác nhận mật khẩu',
        'token_name' => 'Tên token',
        'token_expiry' => 'Hết hạn token',
        'abilities' => 'Khả năng',
        '2fa_code' => 'Mã',
        '2fa_recovery_code' => 'Mã khôi phục',
        'created' => 'Đã tạo',
        'expires' => 'Hết hạn',
    ],
    'or' => 'Hoặc',
    'cancel' => 'Hủy',
];
