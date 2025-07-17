<?php

return [
    'active' => [
        'label' => 'Đang hoạt động',
        'description' => 'Đối tượng đang ở trạng thái hoạt động và sẵn sàng sử dụng.',
    ],
    'inactive' => [
        'label' => 'Không hoạt động',
        'description' => 'Đối tượng đã bị vô hiệu hóa thủ công và tạm thời ẩn đi.',
    ],
    'pending' => [
        'label' => 'Chờ xử lý',
        'description' => 'Lịch trình đã được tạo nhưng đang chờ xác nhận hoặc phê duyệt.',
    ],
    'in_progress' => [
        'label' => 'Đang diễn ra',
        'description' => 'Sự kiện hoặc lịch trình đang diễn ra tại thời điểm hiện tại.',
    ],
    'completed' => [
        'label' => 'Đã hoàn thành',
        'description' => 'Sự kiện đã kết thúc thành công theo đúng lịch trình.',
    ],
    'cancelled' => [
        'label' => 'Đã hủy',
        'description' => 'Sự kiện đã bị hủy trước khi có thể diễn ra hoặc hoàn thành.',
    ],
    'postponed' => [
        'label' => 'Đã hoãn',
        'description' => 'Sự kiện đã bị hoãn và sẽ được lên lịch lại vào một thời điểm khác.',
    ],
    'archived' => [
        'label' => 'Đã lưu trữ',
        'description' => 'Đối tượng đã cũ và được chuyển vào kho lưu trữ, không hiển thị ở các danh sách thông thường.',
    ],
    'default' => [
        'label' => 'Không xác định',
        'description' => 'Trạng thái của đối tượng không được xác định.',
    ],
];
