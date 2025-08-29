<?php

return [
  'resource' => [
    'label' => 'Bài nộp',
    'plural_label' => 'Các bài nộp',
    'navigation_label' => 'Bài nộp',
    'navigation_group' => 'Quản lý bài tập',
  ],
  'form' => [
    'fields' => [
      'assignment_id' => 'Bài tập',
      'student_id' => 'Học viên',
      'content' => 'Nội dung',
      'status' => 'Trạng thái',
      'submitted_at' => 'Nộp lúc',
      'graded_by' => 'Chấm bởi',
      'points' => 'Điểm',
      'feedback' => 'Phản hồi',
      'graded_at' => 'Chấm lúc',
    ],
  ],
  'table' => [
    'columns' => [
      'id' => 'ID',
      'assignment' => [
        'title' => 'Bài tập',
      ],
      'student' => [
        'name' => 'Học viên',
      ],
      'status' => 'Trạng thái',
      'submitted_at' => 'Nộp lúc',
      'grader' => [
        'name' => 'Chấm bởi',
      ],
      'points' => 'Điểm',
      'graded_at' => 'Chấm lúc',
      'created_at' => 'Ngày tạo',
      'updated_at' => 'Ngày cập nhật',
      'deleted_at' => 'Ngày xóa',
    ],
  ],
  'infolist' => [
    'entries' => [
      'id' => 'ID',
      'assignment' => [
        'title' => 'Bài tập',
      ],
      'student' => [
        'name' => 'Học viên',
      ],
      'status' => 'Trạng thái',
      'submitted_at' => 'Nộp lúc',
      'graded_by' => 'Chấm bởi',
      'points' => 'Điểm',
      'graded_at' => 'Chấm lúc',
      'created_at' => 'Ngày tạo',
      'updated_at' => 'Ngày cập nhật',
      'deleted_at' => 'Ngày xóa',
    ],
  ],
];
