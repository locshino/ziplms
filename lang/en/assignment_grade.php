<?php

return [

    'label' => [
        'singular' => 'Assignment Grade',
        'plural' => 'Assignment Grades',
    ],

    'fields' => [
        'submission_user_name' => 'Submitted By',
        'submission_assignment_title' => 'Assignment',
        'grade' => 'Grade',
        'feedback' => 'Feedback',
        'updated_at' => 'Updated At',
    ],

    'form' => [
        'grade' => 'Grade',
        'feedback' => 'Feedback',
    ],

    'actions' => [
        'edit' => 'Grade',
        'download' => 'Download Submission',
        'delete' => 'Delete',
        'bulk_delete' => 'Delete Selected',
    ],

    'filters' => [
        'classroom' => 'Filter by Classroom',
        'course' => 'Filter by Course',
    ],

];
