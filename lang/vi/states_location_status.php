<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Location Status Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for the location status state machine.
    | You are free to change them to anything you want to customize
    | your application's UI.
    |
    */

    'planned' => [
        'label' => 'Đã lên kế hoạch',
        'description' => 'Địa điểm được lên kế hoạch cho tương lai và chưa hoạt động.',
    ],

    'available' => [
        'label' => 'Sẵn sàng',
        'description' => 'Địa điểm đang mở và sẵn sàng để tạo lịch học.',
    ],

    'under_maintenance' => [
        'label' => 'Đang bảo trì',
        'description' => 'Địa điểm tạm thời không hoạt động để bảo trì.',
    ],

    'archived' => [
        'label' => 'Đã lưu trữ',
        'description' => 'Địa điểm đã đóng cửa vĩnh viễn và được giữ lại cho mục đích lịch sử.',
    ],
];
