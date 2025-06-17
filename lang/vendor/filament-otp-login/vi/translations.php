<?php

return [
    'otp_code' => 'Mã OTP',

    'mail' => [
        'subject' => 'Mã OTP',
        'greeting' => 'Xin chào!',
        'line1' => 'Mã OTP của bạn là: :code',
        'line2' => 'Mã này sẽ có hiệu lực trong :seconds giây.',
        'line3' => 'Nếu bạn không yêu cầu mã này, vui lòng bỏ qua email này.',
        'salutation' => 'Trân trọng, :app_name',
    ],

    'view' => [
        'time_left' => 'giây còn lại',
        'resend_code' => 'Gửi lại mã',
        'verify' => 'Xác minh',
        'go_back' => 'Quay lại',
    ],

    'notifications' => [
        'title' => 'Đã gửi mã OTP',
        'body' => 'Mã xác minh đã được gửi đến địa chỉ e-mail của bạn. Mã sẽ có hiệu lực trong :seconds giây.',
    ],

    'validation' => [
        'invalid_code' => 'Mã bạn nhập không hợp lệ.',
        'expired_code' => 'Mã bạn nhập đã hết hạn.',
    ],
];
