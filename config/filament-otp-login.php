<?php

return [
    'table_name' => 'filament_otp_login_codes',

    'otp_code' => [
        'length' => (int) env('OTP_LOGIN_CODE_LENGTH', 6),
        'expires' => (int) env('OTP_LOGIN_CODE_EXPIRES_SECONDS', 120),
    ],

    'notification_class' => \Afsakar\FilamentOtpLogin\Notifications\SendOtpCode::class,
];
