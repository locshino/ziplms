<?php

return [
    'title' => 'Cài đặt',
    'group' => 'Cài đặt',
    'back' => 'Quay lại',
    'settings' => [
        'site' => [
            'title' => 'Cài đặt trang web',
            'description' => 'Quản lý cài đặt trang web của bạn',
            'form' => [
                'site_name' => 'Tên trang web',
                'site_description' => 'Mô tả trang web',
                'site_logo' => 'Logo trang web',
                'site_profile' => 'Hình ảnh hồ sơ trang web',
                'site_keywords' => 'Từ khóa trang web',
                'site_email' => 'Email trang web',
                'site_phone' => 'Số điện thoại trang web',
                'site_author' => 'Tác giả trang web',
            ],
            'site-map' => 'Tạo bản đồ trang web',
            'site-map-notification' => 'Bản đồ trang web đã được tạo thành công',
        ],
        'social' => [
            'title' => 'Menu mạng xã hội',
            'description' => 'Quản lý menu mạng xã hội của bạn',
            'form' => [
                'site_social' => 'Liên kết mạng xã hội',
                'vendor' => 'Nhà cung cấp',
                'link' => 'Liên kết',
            ],
        ],
        'location' => [
            'title' => 'Cài đặt vị trí',
            'description' => 'Quản lý cài đặt vị trí của bạn',
            'form' => [
                'site_address' => 'Địa chỉ trang web',
                'site_phone_code' => 'Mã điện thoại trang web',
                'site_location' => 'Vị trí trang web',
                'site_currency' => 'Tiền tệ trang web',
                'site_language' => 'Ngôn ngữ trang web',
            ],
        ],
    ],
];
