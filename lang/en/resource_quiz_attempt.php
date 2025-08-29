<?php

return [
  'resource' => [
    'label' => 'Quiz Attempt',
    'plural_label' => 'Quiz Attempts',
    'navigation_label' => 'Quiz Attempts',
    'navigation_group' => 'Quiz Management',
  ],
  'form' => [
    'fields' => [
      'quiz_id' => 'Quiz',
      'student_id' => 'Student',
      'points' => 'Points',
      'status' => 'Status',
      'start_at' => 'Start At',
      'end_at' => 'End At',
      'answers' => 'Answers',
    ],
  ],
  'table' => [
    'columns' => [
      'id' => 'ID',
      'quiz' => [
        'title' => 'Quiz',
      ],
      'student' => [
        'name' => 'Student',
      ],
      'points' => 'Points',
      'start_at' => 'Start At',
      'end_at' => 'End At',
      'status' => 'Status',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
      'deleted_at' => 'Deleted At',
    ],
  ],
  'infolist' => [
    'entries' => [
      'quiz' => [
        'title' => 'Quiz',
      ],
      'student' => [
        'name' => 'Student',
      ],
      'points' => 'Points',
      'status' => 'Status',
      'start_at' => 'Start At',
      'end_at' => 'End At',
      'answers' => 'Answers',
    ],
  ],
];
