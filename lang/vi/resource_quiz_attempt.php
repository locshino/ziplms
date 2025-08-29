<?php

return [
  'resource' => [
    'label' => 'Lần thử Quiz',
    'plural_label' => 'Các lần thử Quiz',
    'navigation_label' => 'Lần thử Quiz',
    'navigation_group' => 'Quản lý Quiz',
  ],
  'form' => [
    'fields' => [
      'quiz_id' => 'Quiz',
      'student_id' => 'Học viên',
      'points' => 'Điểm',
      'status' => 'Trạng thái',
      'start_at' => 'Bắt đầu lúc',
      'end_at' => 'Kết thúc lúc',
      'answers' => 'Câu trả lời',
    ],
  ],
  'table' => [
    'columns' => [
      'id' => 'ID',
      'quiz' => [
        'title' => 'Quiz',
      ],
      'student' => [
        'name' => 'Học viên',
      ],
      'points' => 'Điểm',
      'start_at' => 'Bắt đầu lúc',
      'end_at' => 'Kết thúc lúc',
      'status' => 'Trạng thái',
      'created_at' => 'Ngày tạo',
      'updated_at' => 'Ngày cập nhật',
      'deleted_at' => 'Ngày xóa',
    ],
  ],
  'infolist' => [
    'entries' => [
      'quiz' => [
        'title' => 'Quiz',
      ],
      'student' => [
        'name' => 'Học viên',
      ],
      'points' => 'Điểm',
      'status' => 'Trạng thái',
      'start_at' => 'Bắt đầu lúc',
      'end_at' => 'Kết thúc lúc',
      'answers' => 'Câu trả lời',
    ],
  ],
];
