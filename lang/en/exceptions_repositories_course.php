<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Course Repository Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the course repository exception
    | classes to provide localized error messages for course-related operations.
    |
    */

    'course_not_found' => 'Course not found.',
    'course_not_found_with_id' => 'Course with ID :id not found.',
    
    'course_not_published' => 'Course is not published.',
    'course_not_published_with_id' => 'Course with ID :id is not published.',
    
    'course_not_available' => 'Course is not available.',
    'course_not_available_with_id' => 'Course with ID :id is not available.',
    
    'enrollment_closed' => 'Course enrollment is closed.',
    'enrollment_closed_with_id' => 'Enrollment is closed for course with ID :id.',
    
    'capacity_full' => 'Course capacity is full.',
    'capacity_full_with_details' => 'Course with ID :id has reached its capacity of :capacity students.',
    
    'already_enrolled' => 'Student is already enrolled in this course.',
    'already_enrolled_with_details' => 'Student :student_id is already enrolled in course :course_id.',
    
    'not_enrolled' => 'Student is not enrolled in this course.',
    'not_enrolled_with_details' => 'Student :student_id is not enrolled in course :course_id.',
    
    'enrollment_failed' => 'Failed to enroll student in course.',
    'enrollment_failed_with_details' => 'Failed to enroll student :student_id in course :course_id: :reason',
    
    'unenrollment_failed' => 'Failed to unenroll student from course.',
    'unenrollment_failed_with_details' => 'Failed to unenroll student :student_id from course :course_id: :reason',
    
    'instructor_not_assigned' => 'Instructor is not assigned to this course.',
    'instructor_not_assigned_with_details' => 'Instructor :instructor_id is not assigned to course :course_id.',
    
    'instructor_already_assigned' => 'Instructor is already assigned to this course.',
    'instructor_already_assigned_with_details' => 'Instructor :instructor_id is already assigned to course :course_id.',
    
    'progress_calculation_failed' => 'Failed to calculate course progress.',
    'progress_calculation_failed_with_details' => 'Failed to calculate progress for course :course_id: :reason',
    
    'statistics_calculation_failed' => 'Failed to calculate course statistics.',
    'statistics_calculation_failed_with_reason' => 'Failed to calculate course statistics: :reason',
    
    'insufficient_permissions' => 'Insufficient permissions to perform this course operation.',
    'insufficient_permissions_with_action' => 'Insufficient permissions to perform course action: :action',
];