<?php

return [
    'columns' => [
        'id' => 'ID',
        'course_title' => 'Course',
        'student_name' => 'Student',
        'enrolled_at' => 'Enrolled At',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'deleted_at' => 'Deleted At',
    ],
    'fields' => [
        'course_id' => 'Course',
        'student_id' => 'Student',
        'enrolled_at' => 'Enrolled At',
    ],
    'model' => [
        'singular' => 'Enrollment',
        'plural' => 'Enrollments',
    ],
];
