<?php

return [
  'resource' => [
    'label' => 'Bài tập',
    'plural_label' => 'Các bài tập',
    'navigation_label' => 'Bài tập',
    'navigation_group' => 'Quản lý khóa học',
  ],
  'pages' => [
    'list' => ['title' => 'Các bài tập'],
    'create' => ['title' => 'Tạo bài tập'],
    'edit' => ['title' => 'Sửa bài tập'],
    'view' => ['title' => 'Xem bài tập'],
  ],
  'form' => [
    'fields' => [
      'title' => 'Tiêu đề',
      'description' => 'Mô tả',
      'max_points' => 'Điểm tối đa',
      'max_attempts' => 'Số lần thử tối đa',
      'status' => 'Trạng thái',
      'tags' => 'Phân loại',
      'course_documents' => 'Tài liệu bài tập',
    ],
  ],
  'table' => [
    'columns' => [
      'id' => 'ID',
      'title' => 'Tiêu đề',
      'status' => 'Trạng thái',
      'max_points' => 'Điểm tối đa',
      'max_attempts' => 'Số lần thử',
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
      'description' => 'Mô tả',
      'status' => 'Trạng thái',
      'max_points' => 'Điểm tối đa',
      'max_attempts' => 'Số lần thử tối đa',
      'created_at' => 'Ngày tạo',
      'updated_at' => 'Ngày cập nhật',
      'deleted_at' => 'Ngày xóa',
    ],
  ],
  'notifications' => [
    'created' => 'Tạo bài tập thành công',
    'updated' => 'Cập nhật bài tập thành công',
    'deleted' => 'Xóa bài tập thành công',
  ],
];
