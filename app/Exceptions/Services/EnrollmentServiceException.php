<?php

namespace App\Exceptions\Services;

/**
 * Exception for enrollment service-related errors.
 * 
 * This exception class provides localized error messages specific to enrollment operations.
 * 
 * @throws EnrollmentServiceException When enrollment service operations fail
 */
class EnrollmentServiceException extends ServiceException
{
    /**
     * Create exception for enrollment not found.
     *
     * @param string|null $id The enrollment ID
     * @return static
     */
    public static function enrollmentNotFound(?string $id = null): static
    {
        $key = $id 
            ? 'exceptions_services_enrollmentservice.enrollment_not_found_with_id'
            : 'exceptions_services_enrollmentservice.enrollment_not_found';
        
        return new static($key, ['id' => $id]);
    }

    /**
     * Create exception for enrollment not found for user.
     *
     * @param string $userId The user ID
     * @param string $courseId The course ID
     * @return static
     */
    public static function enrollmentNotFoundForUser(string $userId, string $courseId): static
    {
        return new static(
            'exceptions_services_enrollmentservice.enrollment_not_found_for_user',
            ['user_id' => $userId, 'course_id' => $courseId]
        );
    }

    /**
     * Create exception for user required.
     *
     * @return static
     */
    public static function userRequired(): static
    {
        return new static('exceptions_services_enrollmentservice.user_required');
    }

    /**
     * Create exception for course required.
     *
     * @return static
     */
    public static function courseRequired(): static
    {
        return new static('exceptions_services_enrollmentservice.course_required');
    }

    /**
     * Create exception for invalid user.
     *
     * @return static
     */
    public static function invalidUser(): static
    {
        return new static('exceptions_services_enrollmentservice.invalid_user');
    }

    /**
     * Create exception for invalid course.
     *
     * @return static
     */
    public static function invalidCourse(): static
    {
        return new static('exceptions_services_enrollmentservice.invalid_course');
    }

    /**
     * Create exception for user not found.
     *
     * @return static
     */
    public static function userNotFound(): static
    {
        return new static('exceptions_services_enrollmentservice.user_not_found');
    }

    /**
     * Create exception for course not found.
     *
     * @return static
     */
    public static function courseNotFound(): static
    {
        return new static('exceptions_services_enrollmentservice.course_not_found');
    }

    /**
     * Create exception for enrollment already exists.
     *
     * @return static
     */
    public static function enrollmentAlreadyExists(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_already_exists');
    }

    /**
     * Create exception for enrollment already active.
     *
     * @return static
     */
    public static function enrollmentAlreadyActive(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_already_active');
    }

    /**
     * Create exception for enrollment already completed.
     *
     * @return static
     */
    public static function enrollmentAlreadyCompleted(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_already_completed');
    }

    /**
     * Create exception for enrollment already cancelled.
     *
     * @return static
     */
    public static function enrollmentAlreadyCancelled(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_already_cancelled');
    }

    /**
     * Create exception for enrollment not active.
     *
     * @return static
     */
    public static function enrollmentNotActive(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_not_active');
    }

    /**
     * Create exception for enrollment not pending.
     *
     * @return static
     */
    public static function enrollmentNotPending(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_not_pending');
    }

    /**
     * Create exception for enrollment expired.
     *
     * @return static
     */
    public static function enrollmentExpired(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_expired');
    }

    /**
     * Create exception for invalid enrollment status.
     *
     * @return static
     */
    public static function invalidEnrollmentStatus(): static
    {
        return new static('exceptions_services_enrollmentservice.invalid_enrollment_status');
    }

    /**
     * Create exception for course not published.
     *
     * @return static
     */
    public static function courseNotPublished(): static
    {
        return new static('exceptions_services_enrollmentservice.course_not_published');
    }

    /**
     * Create exception for course not available.
     *
     * @return static
     */
    public static function courseNotAvailable(): static
    {
        return new static('exceptions_services_enrollmentservice.course_not_available');
    }

    /**
     * Create exception for course enrollment closed.
     *
     * @return static
     */
    public static function courseEnrollmentClosed(): static
    {
        return new static('exceptions_services_enrollmentservice.course_enrollment_closed');
    }

    /**
     * Create exception for course capacity full.
     *
     * @return static
     */
    public static function courseCapacityFull(): static
    {
        return new static('exceptions_services_enrollmentservice.course_capacity_full');
    }

    /**
     * Create exception for course prerequisites not met.
     *
     * @return static
     */
    public static function coursePrerequisitesNotMet(): static
    {
        return new static('exceptions_services_enrollmentservice.course_prerequisites_not_met');
    }

    /**
     * Create exception for course not started.
     *
     * @return static
     */
    public static function courseNotStarted(): static
    {
        return new static('exceptions_services_enrollmentservice.course_not_started');
    }

    /**
     * Create exception for course already ended.
     *
     * @return static
     */
    public static function courseAlreadyEnded(): static
    {
        return new static('exceptions_services_enrollmentservice.course_already_ended');
    }

    /**
     * Create exception for user not active.
     *
     * @return static
     */
    public static function userNotActive(): static
    {
        return new static('exceptions_services_enrollmentservice.user_not_active');
    }

    /**
     * Create exception for user suspended.
     *
     * @return static
     */
    public static function userSuspended(): static
    {
        return new static('exceptions_services_enrollmentservice.user_suspended');
    }

    /**
     * Create exception for user not eligible.
     *
     * @return static
     */
    public static function userNotEligible(): static
    {
        return new static('exceptions_services_enrollmentservice.user_not_eligible');
    }

    /**
     * Create exception for user already completed course.
     *
     * @return static
     */
    public static function userAlreadyCompletedCourse(): static
    {
        return new static('exceptions_services_enrollmentservice.user_already_completed_course');
    }

    /**
     * Create exception for user has pending enrollment.
     *
     * @return static
     */
    public static function userHasPendingEnrollment(): static
    {
        return new static('exceptions_services_enrollmentservice.user_has_pending_enrollment');
    }

    /**
     * Create exception for course requires payment.
     *
     * @return static
     */
    public static function courseRequiresPayment(): static
    {
        return new static('exceptions_services_enrollmentservice.course_requires_payment');
    }

    /**
     * Create exception for invalid payment method.
     *
     * @return static
     */
    public static function invalidPaymentMethod(): static
    {
        return new static('exceptions_services_enrollmentservice.invalid_payment_method');
    }

    /**
     * Create exception for payment failed.
     *
     * @return static
     */
    public static function paymentFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.payment_failed');
    }

    /**
     * Create exception for insufficient funds.
     *
     * @return static
     */
    public static function insufficientFunds(): static
    {
        return new static('exceptions_services_enrollmentservice.insufficient_funds');
    }

    /**
     * Create exception for pricing not available.
     *
     * @return static
     */
    public static function pricingNotAvailable(): static
    {
        return new static('exceptions_services_enrollmentservice.pricing_not_available');
    }

    /**
     * Create exception for enrollment create failed.
     *
     * @return static
     */
    public static function enrollmentCreateFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_create_failed');
    }

    /**
     * Create exception for enrollment update failed.
     *
     * @return static
     */
    public static function enrollmentUpdateFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_update_failed');
    }

    /**
     * Create exception for enrollment delete failed.
     *
     * @return static
     */
    public static function enrollmentDeleteFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_delete_failed');
    }

    /**
     * Create exception for enrollment activate failed.
     *
     * @return static
     */
    public static function enrollmentActivateFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_activate_failed');
    }

    /**
     * Create exception for enrollment deactivate failed.
     *
     * @return static
     */
    public static function enrollmentDeactivateFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_deactivate_failed');
    }

    /**
     * Create exception for enrollment complete failed.
     *
     * @return static
     */
    public static function enrollmentCompleteFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_complete_failed');
    }

    /**
     * Create exception for enrollment cancel failed.
     *
     * @return static
     */
    public static function enrollmentCancelFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_cancel_failed');
    }

    /**
     * Create exception for enrollment suspend failed.
     *
     * @return static
     */
    public static function enrollmentSuspendFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_suspend_failed');
    }

    /**
     * Create exception for enrollment resume failed.
     *
     * @return static
     */
    public static function enrollmentResumeFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_resume_failed');
    }

    /**
     * Create exception for bulk enrollment failed.
     *
     * @return static
     */
    public static function bulkEnrollmentFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.bulk_enrollment_failed');
    }

    /**
     * Create exception for bulk enrollment partial success.
     *
     * @return static
     */
    public static function bulkEnrollmentPartialSuccess(): static
    {
        return new static('exceptions_services_enrollmentservice.bulk_enrollment_partial_success');
    }

    /**
     * Create exception for invalid bulk data.
     *
     * @return static
     */
    public static function invalidBulkData(): static
    {
        return new static('exceptions_services_enrollmentservice.invalid_bulk_data');
    }

    /**
     * Create exception for bulk operation not permitted.
     *
     * @return static
     */
    public static function bulkOperationNotPermitted(): static
    {
        return new static('exceptions_services_enrollmentservice.bulk_operation_not_permitted');
    }

    /**
     * Create exception for too many enrollments.
     *
     * @return static
     */
    public static function tooManyEnrollments(): static
    {
        return new static('exceptions_services_enrollmentservice.too_many_enrollments');
    }

    /**
     * Create exception for progress update failed.
     *
     * @return static
     */
    public static function progressUpdateFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.progress_update_failed');
    }

    /**
     * Create exception for invalid progress value.
     *
     * @return static
     */
    public static function invalidProgressValue(): static
    {
        return new static('exceptions_services_enrollmentservice.invalid_progress_value');
    }

    /**
     * Create exception for progress cannot decrease.
     *
     * @return static
     */
    public static function progressCannotDecrease(): static
    {
        return new static('exceptions_services_enrollmentservice.progress_cannot_decrease');
    }

    /**
     * Create exception for completion requirements not met.
     *
     * @return static
     */
    public static function completionRequirementsNotMet(): static
    {
        return new static('exceptions_services_enrollmentservice.completion_requirements_not_met');
    }

    /**
     * Create exception for certificate generation failed.
     *
     * @return static
     */
    public static function certificateGenerationFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.certificate_generation_failed');
    }

    /**
     * Create exception for enrollment statistics not available.
     *
     * @return static
     */
    public static function enrollmentStatisticsNotAvailable(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_statistics_not_available');
    }

    /**
     * Create exception for insufficient data for statistics.
     *
     * @return static
     */
    public static function insufficientDataForStatistics(): static
    {
        return new static('exceptions_services_enrollmentservice.insufficient_data_for_statistics');
    }

    /**
     * Create exception for report generation failed.
     *
     * @return static
     */
    public static function reportGenerationFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.report_generation_failed');
    }

    /**
     * Create exception for enrollment access denied.
     *
     * @return static
     */
    public static function enrollmentAccessDenied(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_access_denied');
    }

    /**
     * Create exception for insufficient permissions.
     *
     * @return static
     */
    public static function insufficientPermissions(): static
    {
        return new static('exceptions_services_enrollmentservice.insufficient_permissions');
    }

    /**
     * Create exception for instructor cannot enroll.
     *
     * @return static
     */
    public static function instructorCannotEnroll(): static
    {
        return new static('exceptions_services_enrollmentservice.instructor_cannot_enroll');
    }

    /**
     * Create exception for admin enrollment required.
     *
     * @return static
     */
    public static function adminEnrollmentRequired(): static
    {
        return new static('exceptions_services_enrollmentservice.admin_enrollment_required');
    }

    /**
     * Create exception for waitlist full.
     *
     * @return static
     */
    public static function waitlistFull(): static
    {
        return new static('exceptions_services_enrollmentservice.waitlist_full');
    }

    /**
     * Create exception for already on waitlist.
     *
     * @return static
     */
    public static function alreadyOnWaitlist(): static
    {
        return new static('exceptions_services_enrollmentservice.already_on_waitlist');
    }

    /**
     * Create exception for not on waitlist.
     *
     * @return static
     */
    public static function notOnWaitlist(): static
    {
        return new static('exceptions_services_enrollmentservice.not_on_waitlist');
    }

    /**
     * Create exception for waitlist join failed.
     *
     * @return static
     */
    public static function waitlistJoinFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.waitlist_join_failed');
    }

    /**
     * Create exception for waitlist remove failed.
     *
     * @return static
     */
    public static function waitlistRemoveFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.waitlist_remove_failed');
    }

    /**
     * Create exception for enrollment notification failed.
     *
     * @return static
     */
    public static function enrollmentNotificationFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_notification_failed');
    }

    /**
     * Create exception for welcome email failed.
     *
     * @return static
     */
    public static function welcomeEmailFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.welcome_email_failed');
    }

    /**
     * Create exception for completion notification failed.
     *
     * @return static
     */
    public static function completionNotificationFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.completion_notification_failed');
    }

    /**
     * Create exception for enrollment data corrupted.
     *
     * @return static
     */
    public static function enrollmentDataCorrupted(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_data_corrupted');
    }

    /**
     * Create exception for enrollment history missing.
     *
     * @return static
     */
    public static function enrollmentHistoryMissing(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_history_missing');
    }

    /**
     * Create exception for duplicate enrollment detected.
     *
     * @return static
     */
    public static function duplicateEnrollmentDetected(): static
    {
        return new static('exceptions_services_enrollmentservice.duplicate_enrollment_detected');
    }

    /**
     * Create exception for enrollment transfer failed.
     *
     * @return static
     */
    public static function enrollmentTransferFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.enrollment_transfer_failed');
    }

    /**
     * Create exception for invalid transfer target.
     *
     * @return static
     */
    public static function invalidTransferTarget(): static
    {
        return new static('exceptions_services_enrollmentservice.invalid_transfer_target');
    }

    /**
     * Create exception for transfer not permitted.
     *
     * @return static
     */
    public static function transferNotPermitted(): static
    {
        return new static('exceptions_services_enrollmentservice.transfer_not_permitted');
    }

    /**
     * Create exception for refund not available.
     *
     * @return static
     */
    public static function refundNotAvailable(): static
    {
        return new static('exceptions_services_enrollmentservice.refund_not_available');
    }

    /**
     * Create exception for refund period expired.
     *
     * @return static
     */
    public static function refundPeriodExpired(): static
    {
        return new static('exceptions_services_enrollmentservice.refund_period_expired');
    }

    /**
     * Create exception for refund processing failed.
     *
     * @return static
     */
    public static function refundProcessingFailed(): static
    {
        return new static('exceptions_services_enrollmentservice.refund_processing_failed');
    }

    /**
     * Create exception for cancellation not permitted.
     *
     * @return static
     */
    public static function cancellationNotPermitted(): static
    {
        return new static('exceptions_services_enrollmentservice.cancellation_not_permitted');
    }

    /**
     * Create exception for cancellation deadline passed.
     *
     * @return static
     */
    public static function cancellationDeadlinePassed(): static
    {
        return new static('exceptions_services_enrollmentservice.cancellation_deadline_passed');
    }
}