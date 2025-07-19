<?php

return [
    'pending' => [
        'label' => 'Chờ xử lý',
        'description' => 'Bài nộp đang chờ bắt đầu.',
        'color' => 'gray',
        'icon' => 'heroicon-o-clock', 
    ],
    'in_progress' => [
        'label' => 'Đang thực hiện',
        'description' => 'Học sinh đang làm bài.',
        'color' => 'blue',
        'icon' => 'heroicon-o-arrow-path',
    ],
    'active' => [
        'label' => 'Đang hoạt động',
        'description' => 'Bài nộp đang được xử lý.',
        'color' => 'green',
        'icon' => 'heroicon-o-play', 
    ],
   'submitted' => [
    'label' => 'Đã nộp',
    'description' => 'Học sinh đã nộp bài.',
    'color' => 'yellow',
    'icon' => 'heroicon-o-arrow-up-tray',
],
'graded' => [
    'label' => 'Đã chấm điểm',
    'description' => 'Bài đã được chấm điểm.',
    'color' => 'purple',
    'icon' => 'heroicon-o-check-circle',
],
'completed' => [
    'label' => 'Hoàn thành',
    'description' => 'Quá trình đã hoàn tất.',
    'color' => 'teal',
    'icon' => 'heroicon-o-check',
],
'archived' => [
    'label' => 'Đã lưu trữ',
    'description' => 'Bài nộp đã được lưu trữ.',
    'color' => 'gray',
    'icon' => 'heroicon-o-archive-box',
],
'inactive' => [
    'label' => 'Không hoạt động',
    'description' => 'Bài nộp hiện không hoạt động.',
    'color' => 'red',
    'icon' => 'heroicon-o-pause-circle',
],
'postponed' => [
    'label' => 'Tạm hoãn',
    'description' => 'Bài nộp bị hoãn lại.',
    'color' => 'orange',
    'icon' => 'heroicon-o-calendar',
],
];