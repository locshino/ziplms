<?php

return [
    'columns' => [
        'id' => 'ID',
        'teacher_id' => 'Teacher ID',
        'teacher_name' => 'Teacher',
        'title' => 'Title',
        'description' => 'Description',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',
    ],
    'fields' => [
        'title' => 'Title',
        'description' => 'Description',
        'teacher_id' => 'Teacher',
    ],
    'model' => [
        'singular' => 'Course',
        'plural' => 'Courses',
    ],
];
