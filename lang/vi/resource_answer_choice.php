<?php

return [
  'resource' => [
    'label' => 'Lựa chọn trả lời',
    'plural_label' => 'Các lựa chọn trả lời',
    'navigation_label' => 'Lựa chọn trả lời',
    'navigation_group' => 'Quản lý Quiz',
  ],
  'pages' => [
    'list' => ['title' => 'Các lựa chọn trả lời'],
    'create' => ['title' => 'Tạo lựa chọn'],
    'edit' => ['title' => 'Sửa lựa chọn'],
  ],
  'form' => [
    'fields' => [
      'question_id' => 'Câu hỏi',
      'is_multi_choice' => 'Nhiều lựa chọn',
      'answer_choices' => 'Các lựa chọn trả lời',
      'title' => 'Tiêu đề',
      'description' => 'Mô tả',
      'is_correct' => 'Là đáp án đúng',
    ],
  ],
  'table' => [
    'columns' => [
      'id' => 'ID',
      'question' => [
        'title' => 'Câu hỏi',
      ],
      'title' => 'Câu trả lời',
      'is_correct' => 'Đáp án đúng',
      'created_at' => 'Ngày tạo',
      'updated_at' => 'Ngày cập nhật',
      'deleted_at' => 'Ngày xóa',
    ],
  ],
];
