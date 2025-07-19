<?php

return [
    'draft' => [
        'label' => 'Nháp',
        'description' => 'Bài tập đang được tạo, chưa công khai.',
        'color' => 'gray', 
        'icon' => 'heroicon-o-pencil-square', 
    ],
    'published' => [
        'label' => 'Công khai',
        'description' => 'Bài tập đã công khai cho học sinh.',
        'color' => 'success', 
        'icon' => 'heroicon-o-eye',
    ],
    'closed' => [
        'label' => 'Đã đóng',
        'description' => 'Bài tập đã kết thúc và không thể nộp.',
    ],
    'cancelled' => [
        'label' => 'Hủy bỏ',
        'description' => 'Bài tập đã bị hủy.',
    ],
];
