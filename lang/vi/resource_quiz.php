<?php

return [
  'resource' => [
    'label' => 'Quiz',
    'plural_label' => 'Các quiz',
    'navigation_label' => 'Quiz',
    'navigation_group' => 'Quản lý khóa học',
  ],
  'form' => [
    'fields' => [
      'title' => 'Tiêu đề',
      'max_attempts' => 'Số lần thử tối đa',
      'time_limit_minutes' => 'Giới hạn thời gian (phút)',
      'status' => 'Trạng thái',
      'is_single_session' => 'Phiên duy nhất',
      'tags' => 'Phân loại',
      'description' => 'Mô tả',
    ],
  ],
  'table' => [
    'columns' => [
      'id' => 'ID',
      'title' => 'Tiêu đề',
      'max_attempts' => 'Số lần thử',
      'is_single_session' => 'Phiên duy nhất',
      'time_limit_minutes' => 'Giới hạn thời gian',
      'status' => 'Trạng thái',
      'tags' => 'Phân loại',
      'created_at' => 'Ngày tạo',
      'updated_at' => 'Ngày cập nhật',
      'deleted_at' => 'Ngày xóa',
    ],
  ],
  'infolist' => [
    'entries' => [
      'id' => 'ID',
      'title' => 'Tiêu đề',
      'max_attempts' => 'Số lần thử',
      'is_single_session' => 'Phiên duy nhất',
      'time_limit_minutes' => 'Giới hạn thời gian',
      'status' => 'Trạng thái',
      'created_at' => 'Ngày tạo',
      'updated_at' => 'Ngày cập nhật',
      'deleted_at' => 'Ngày xóa',
    ],
  ],
];
