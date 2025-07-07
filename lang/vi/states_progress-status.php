<?php

return [
    'pending' => [
        'label' => 'Đang chờ',
        'description' => 'Tác vụ đang chờ để được xử lý.',
    ],
    'in_progress' => [
        'label' => 'Đang xử lý',
        'description' => 'Tác vụ đang được thực thi.',
    ],
    'done' => [
        'label' => 'Hoàn thành',
        'description' => 'Tác vụ đã hoàn thành thành công.',
    ],
    'done_with_errors' => [
        'label' => 'Hoàn thành (có lỗi)',
        'description' => 'Tác vụ đã hoàn thành nhưng có một vài lỗi không nghiêm trọng.',
    ],
    'failed' => [
        'label' => 'Thất bại',
        'description' => 'Tác vụ đã thất bại và không thể hoàn thành.',
    ],
    'canceled' => [
        'label' => 'Đã hủy',
        'description' => 'Tác vụ đã bị người dùng hoặc hệ thống hủy bỏ.',
    ],
    'retrying' => [
        'label' => 'Đang thử lại',
        'description' => 'Tác vụ thất bại đang được thử lại.',
    ],
];
