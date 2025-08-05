<?php

return [
    // Enrollment not found errors
    'enrollment_not_found' => 'Enrollment not found.',
    'enrollment_not_found_with_id' => 'Enrollment with ID :id not found.',
    'enrollment_not_found_for_user' => 'Enrollment not found for user :user_id in course :course_id.',

    // Enrollment validation errors
    'user_required' => 'User is required for enrollment.',
    'course_required' => 'Course is required for enrollment.',
    'invalid_user' => 'Invalid user for enrollment.',
    'invalid_course' => 'Invalid course for enrollment.',
    'user_not_found' => 'User not found.',
    'course_not_found' => 'Course not found.',

    // Enrollment status errors
    'enrollment_already_exists' => 'User is already enrolled in this course.',
    'enrollment_already_active' => 'Enrollment is already active.',
    'enrollment_already_completed' => 'Enrollment is already completed.',
    'enrollment_already_cancelled' => 'Enrollment is already cancelled.',
    'enrollment_not_active' => 'Enrollment is not active.',
    'enrollment_not_pending' => 'Enrollment is not pending.',
    'enrollment_expired' => 'Enrollment has expired.',
    'invalid_enrollment_status' => 'Invalid enrollment status.',

    // Course availability errors
    'course_not_published' => 'Course is not published.',
    'course_not_available' => 'Course is not available for enrollment.',
    'course_enrollment_closed' => 'Course enrollment is closed.',
    'course_capacity_full' => 'Course has reached maximum capacity.',
    'course_prerequisites_not_met' => 'Course prerequisites are not met.',
    'course_not_started' => 'Course has not started yet.',
    'course_already_ended' => 'Course has already ended.',

    // User eligibility errors
    'user_not_active' => 'User account is not active.',
    'user_suspended' => 'User account is suspended.',
    'user_not_eligible' => 'User is not eligible for this course.',
    'user_already_completed_course' => 'User has already completed this course.',
    'user_has_pending_enrollment' => 'User has a pending enrollment for this course.',

    // Payment and pricing errors
    'course_requires_payment' => 'Course requires payment for enrollment.',
    'invalid_payment_method' => 'Invalid payment method.',
    'payment_failed' => 'Payment processing failed.',
    'insufficient_funds' => 'Insufficient funds for enrollment.',
    'pricing_not_available' => 'Course pricing information not available.',

    // Enrollment operation errors
    'enrollment_create_failed' => 'Failed to create enrollment.',
    'enrollment_update_failed' => 'Failed to update enrollment.',
    'enrollment_delete_failed' => 'Failed to delete enrollment.',
    'enrollment_activate_failed' => 'Failed to activate enrollment.',
    'enrollment_deactivate_failed' => 'Failed to deactivate enrollment.',
    'enrollment_complete_failed' => 'Failed to complete enrollment.',
    'enrollment_cancel_failed' => 'Failed to cancel enrollment.',
    'enrollment_suspend_failed' => 'Failed to suspend enrollment.',
    'enrollment_resume_failed' => 'Failed to resume enrollment.',

    // Bulk operation errors
    'bulk_enrollment_failed' => 'Bulk enrollment operation failed.',
    'bulk_enrollment_partial_success' => 'Bulk enrollment completed with some failures.',
    'invalid_bulk_data' => 'Invalid data provided for bulk enrollment.',
    'bulk_operation_not_permitted' => 'Bulk operation is not permitted.',
    'too_many_enrollments' => 'Too many enrollments in bulk operation.',

    // Progress and completion errors
    'progress_update_failed' => 'Failed to update enrollment progress.',
    'invalid_progress_value' => 'Invalid progress value.',
    'progress_cannot_decrease' => 'Progress cannot be decreased.',
    'completion_requirements_not_met' => 'Course completion requirements are not met.',
    'certificate_generation_failed' => 'Failed to generate completion certificate.',

    // Statistics and reporting errors
    'enrollment_statistics_not_available' => 'Enrollment statistics are not available.',
    'insufficient_data_for_statistics' => 'Insufficient data to generate enrollment statistics.',
    'report_generation_failed' => 'Failed to generate enrollment report.',

    // Permission and access errors
    'enrollment_access_denied' => 'Access to enrollment is denied.',
    'insufficient_permissions' => 'Insufficient permissions for enrollment operation.',
    'instructor_cannot_enroll' => 'Course instructor cannot enroll in their own course.',
    'admin_enrollment_required' => 'Administrator approval required for enrollment.',

    // Waitlist errors
    'waitlist_full' => 'Course waitlist is full.',
    'already_on_waitlist' => 'User is already on the waitlist for this course.',
    'not_on_waitlist' => 'User is not on the waitlist for this course.',
    'waitlist_join_failed' => 'Failed to join course waitlist.',
    'waitlist_remove_failed' => 'Failed to remove from course waitlist.',

    // Notification errors
    'enrollment_notification_failed' => 'Failed to send enrollment notification.',
    'welcome_email_failed' => 'Failed to send welcome email.',
    'completion_notification_failed' => 'Failed to send completion notification.',

    // Data integrity errors
    'enrollment_data_corrupted' => 'Enrollment data is corrupted.',
    'enrollment_history_missing' => 'Enrollment history is missing.',
    'duplicate_enrollment_detected' => 'Duplicate enrollment detected.',

    // Transfer and migration errors
    'enrollment_transfer_failed' => 'Failed to transfer enrollment.',
    'invalid_transfer_target' => 'Invalid target for enrollment transfer.',
    'transfer_not_permitted' => 'Enrollment transfer is not permitted.',

    // Refund and cancellation errors
    'refund_not_available' => 'Refund is not available for this enrollment.',
    'refund_period_expired' => 'Refund period has expired.',
    'refund_processing_failed' => 'Refund processing failed.',
    'cancellation_not_permitted' => 'Enrollment cancellation is not permitted.',
    'cancellation_deadline_passed' => 'Enrollment cancellation deadline has passed.',
];
