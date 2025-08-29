<?php

return [
  'resource' => [
    'label' => 'Question',
    'plural_label' => 'Questions',
    'navigation_label' => 'Questions',
    'navigation_group' => 'Quiz Management',
  ],
  'form' => [
    'fields' => [
      'title' => 'Title',
      'description' => 'Description',
      'status' => 'Status',
    ],
  ],
  'table' => [
    'columns' => [
      'id' => 'ID',
      'title' => 'Title',
      'status' => 'Status',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
      'deleted_at' => 'Deleted At',
    ],
  ],
  'infolist' => [
    'entries' => [
      'id' => 'ID',
      'status' => 'Status',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
      'deleted_at' => 'Deleted At',
    ],
  ],
];
