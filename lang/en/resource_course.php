<?php

return [
  'resource' => [
    'label' => 'Course',
    'plural_label' => 'Courses',
    'navigation_label' => 'Courses',
    'navigation_group' => 'Course Management',
  ],
  'form' => [
    'fields' => [
      'course_cover' => 'Course Cover',
      'title' => 'Title',
      'teacher_id' => 'Teacher',
      'status' => 'Status',
      'tags' => 'Tags',
      'start_at' => 'Start At',
      'end_at' => 'End At',
      'course_documents' => 'Course Documents',
      'slug' => 'Slug',
      'price' => 'Price',
      'is_featured' => 'Is Featured',
      'description' => 'Description',
    ],
  ],
  'table' => [
    'columns' => [
      'id' => 'ID',
      'title' => 'Title',
      'slug' => 'Slug',
      'teacher' => [
        'name' => 'Teacher',
      ],
      'start_at' => 'Start At',
      'end_at' => 'End At',
      'status' => 'Status',
      'price' => 'Price',
      'is_featured' => 'Featured',
      'tags' => 'Tags',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
      'deleted_at' => 'Deleted At',
    ],
  ],
];
