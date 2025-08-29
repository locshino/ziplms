<?php

return [
  'resource' => [
    'label' => 'Assignment',
    'plural_label' => 'Assignments',
    'navigation_label' => 'Assignments',
    'navigation_group' => 'Course Management',
  ],
  'pages' => [
    'list' => ['title' => 'Assignments'],
    'create' => ['title' => 'Create Assignment'],
    'edit' => ['title' => 'Edit Assignment'],
    'view' => ['title' => 'View Assignment'],
  ],
  'form' => [
    'fields' => [
      'title' => 'Title',
      'description' => 'Description',
      'max_points' => 'Max Points',
      'max_attempts' => 'Max Attempts',
      'status' => 'Status',
      'tags' => 'Tags',
      'course_documents' => 'Assignment Documents',
    ],
  ],
  'table' => [
    'columns' => [
      'id' => 'ID',
      'title' => 'Title',
      'status' => 'Status',
      'max_points' => 'Max Points',
      'max_attempts' => 'Max Attempts',
      'tags' => 'Tags',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
      'deleted_at' => 'Deleted At',
    ],
  ],
  'infolist' => [
    'entries' => [
      'id' => 'ID',
      'title' => 'Title',
      'description' => 'Description',
      'status' => 'Status',
      'max_points' => 'Max Points',
      'max_attempts' => 'Max Attempts',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
      'deleted_at' => 'Deleted At',
    ],
  ],
  'notifications' => [
    'created' => 'Assignment created successfully',
    'updated' => 'Assignment updated successfully',
    'deleted' => 'Assignment deleted successfully',
  ],
];
