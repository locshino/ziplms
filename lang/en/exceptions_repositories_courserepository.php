<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Course Repository Exception Messages
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for course repository specific
    | exception messages that are thrown by CourseRepository class.
    |
    */

    'course_not_found' => 'Course not found.',
    'course_not_found_with_id' => 'Course with ID :id not found.',
    'course_title_required' => 'Course title is required.',
    'course_title_too_long' => 'Course title is too long.',
    'course_description_required' => 'Course description is required.',
    'invalid_instructor' => 'Invalid instructor specified.',
    'instructor_not_found' => 'Instructor with ID :id not found.',
    'course_already_published' => 'Course is already published.',
    'course_not_published' => 'Course is not published.',
    'cannot_delete_published_course' => 'Cannot delete published course.',
    'course_has_enrollments' => 'Cannot delete course with active enrollments.',
    'course_has_assignments' => 'Cannot delete course with assignments.',
    'course_has_quizzes' => 'Cannot delete course with quizzes.',
    'invalid_course_status' => 'Invalid course status provided.',
    'invalid_course_category' => 'Invalid course category specified.',
    'course_capacity_exceeded' => 'Course enrollment capacity exceeded.',
    'course_enrollment_closed' => 'Course enrollment is closed.',
    'course_not_available' => 'Course is not available for enrollment.',
    'duplicate_course_title' => 'Course title already exists.',
    'invalid_course_duration' => 'Invalid course duration specified.',
    'invalid_course_price' => 'Invalid course price specified.',
    'course_start_date_invalid' => 'Course start date is invalid.',
    'course_end_date_invalid' => 'Course end date is invalid.',
    'course_dates_conflict' => 'Course start date must be before end date.',
    'create_course_failed' => 'Failed to create course.',
    'update_course_failed' => 'Failed to update course.',
    'delete_course_failed' => 'Failed to delete course.',
    'publish_course_failed' => 'Failed to publish course.',
    'unpublish_course_failed' => 'Failed to unpublish course.',
];