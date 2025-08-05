<?php

namespace App\Exceptions\Services;

/**
 * Exception for quiz service-related errors.
 * 
 * This exception class provides localized error messages specific to quiz operations.
 * 
 * @throws QuizServiceException When quiz service operations fail
 */
class QuizServiceException extends ServiceException
{
    /**
     * Create exception for quiz not found.
     *
     * @param string|null $id The quiz ID
     * @return static
     */
    public static function quizNotFound(?string $id = null): static
    {
        $key = $id 
            ? 'exceptions_services_quizservice.quiz_not_found_with_id'
            : 'exceptions_services_quizservice.quiz_not_found';
        
        return new static($key, ['id' => $id]);
    }

    /**
     * Create exception for quiz title required.
     *
     * @return static
     */
    public static function quizTitleRequired(): static
    {
        return new static('exceptions_services_quizservice.quiz_title_required');
    }

    /**
     * Create exception for quiz title too long.
     *
     * @return static
     */
    public static function quizTitleTooLong(): static
    {
        return new static('exceptions_services_quizservice.quiz_title_too_long');
    }

    /**
     * Create exception for quiz description required.
     *
     * @return static
     */
    public static function quizDescriptionRequired(): static
    {
        return new static('exceptions_services_quizservice.quiz_description_required');
    }

    /**
     * Create exception for invalid time limit.
     *
     * @return static
     */
    public static function invalidTimeLimit(): static
    {
        return new static('exceptions_services_quizservice.quiz_time_limit_invalid');
    }

    /**
     * Create exception for invalid max attempts.
     *
     * @return static
     */
    public static function invalidMaxAttempts(): static
    {
        return new static('exceptions_services_quizservice.quiz_max_attempts_invalid');
    }

    /**
     * Create exception for invalid passing score.
     *
     * @return static
     */
    public static function invalidPassingScore(): static
    {
        return new static('exceptions_services_quizservice.quiz_passing_score_invalid');
    }

    /**
     * Create exception for quiz not published.
     *
     * @return static
     */
    public static function quizNotPublished(): static
    {
        return new static('exceptions_services_quizservice.quiz_not_published');
    }

    /**
     * Create exception for quiz already published.
     *
     * @return static
     */
    public static function quizAlreadyPublished(): static
    {
        return new static('exceptions_services_quizservice.quiz_already_published');
    }

    /**
     * Create exception for quiz not active.
     *
     * @return static
     */
    public static function quizNotActive(): static
    {
        return new static('exceptions_services_quizservice.quiz_not_active');
    }

    /**
     * Create exception for quiz expired.
     *
     * @return static
     */
    public static function quizExpired(): static
    {
        return new static('exceptions_services_quizservice.quiz_expired');
    }

    /**
     * Create exception for quiz not started.
     *
     * @return static
     */
    public static function quizNotStarted(): static
    {
        return new static('exceptions_services_quizservice.quiz_not_started');
    }

    /**
     * Create exception for quiz attempt not found.
     *
     * @return static
     */
    public static function quizAttemptNotFound(): static
    {
        return new static('exceptions_services_quizservice.quiz_attempt_not_found');
    }

    /**
     * Create exception for quiz attempt already exists.
     *
     * @return static
     */
    public static function quizAttemptAlreadyExists(): static
    {
        return new static('exceptions_services_quizservice.quiz_attempt_already_exists');
    }

    /**
     * Create exception for quiz attempt already completed.
     *
     * @return static
     */
    public static function quizAttemptAlreadyCompleted(): static
    {
        return new static('exceptions_services_quizservice.quiz_attempt_already_completed');
    }

    /**
     * Create exception for quiz attempt already submitted.
     *
     * @return static
     */
    public static function quizAttemptAlreadySubmitted(): static
    {
        return new static('exceptions_services_quizservice.quiz_attempt_already_submitted');
    }

    /**
     * Create exception for quiz attempt time expired.
     *
     * @return static
     */
    public static function quizAttemptTimeExpired(): static
    {
        return new static('exceptions_services_quizservice.quiz_attempt_time_expired');
    }

    /**
     * Create exception for max attempts reached.
     *
     * @return static
     */
    public static function maxAttemptsReached(): static
    {
        return new static('exceptions_services_quizservice.quiz_max_attempts_reached');
    }

    /**
     * Create exception for quiz attempt not started.
     *
     * @return static
     */
    public static function quizAttemptNotStarted(): static
    {
        return new static('exceptions_services_quizservice.quiz_attempt_not_started');
    }

    /**
     * Create exception for quiz attempt in progress.
     *
     * @return static
     */
    public static function quizAttemptInProgress(): static
    {
        return new static('exceptions_services_quizservice.quiz_attempt_in_progress');
    }

    /**
     * Create exception for quiz access denied.
     *
     * @return static
     */
    public static function quizAccessDenied(): static
    {
        return new static('exceptions_services_quizservice.quiz_access_denied');
    }

    /**
     * Create exception for user not enrolled.
     *
     * @return static
     */
    public static function userNotEnrolled(): static
    {
        return new static('exceptions_services_quizservice.quiz_not_enrolled');
    }

    /**
     * Create exception for prerequisites not met.
     *
     * @return static
     */
    public static function prerequisitesNotMet(): static
    {
        return new static('exceptions_services_quizservice.quiz_prerequisites_not_met');
    }

    /**
     * Create exception for quiz not available for user.
     *
     * @return static
     */
    public static function quizNotAvailableForUser(): static
    {
        return new static('exceptions_services_quizservice.quiz_not_available_for_user');
    }

    /**
     * Create exception for quiz has no questions.
     *
     * @return static
     */
    public static function quizHasNoQuestions(): static
    {
        return new static('exceptions_services_quizservice.quiz_has_no_questions');
    }

    /**
     * Create exception for quiz question not found.
     *
     * @return static
     */
    public static function quizQuestionNotFound(): static
    {
        return new static('exceptions_services_quizservice.quiz_question_not_found');
    }

    /**
     * Create exception for answer required.
     *
     * @return static
     */
    public static function answerRequired(): static
    {
        return new static('exceptions_services_quizservice.quiz_answer_required');
    }

    /**
     * Create exception for invalid answer format.
     *
     * @return static
     */
    public static function invalidAnswerFormat(): static
    {
        return new static('exceptions_services_quizservice.quiz_invalid_answer_format');
    }

    /**
     * Create exception for answer out of range.
     *
     * @return static
     */
    public static function answerOutOfRange(): static
    {
        return new static('exceptions_services_quizservice.quiz_answer_out_of_range');
    }

    /**
     * Create exception for quiz start failed.
     *
     * @return static
     */
    public static function quizStartFailed(): static
    {
        return new static('exceptions_services_quizservice.quiz_start_failed');
    }

    /**
     * Create exception for quiz submit failed.
     *
     * @return static
     */
    public static function quizSubmitFailed(): static
    {
        return new static('exceptions_services_quizservice.quiz_submit_failed');
    }

    /**
     * Create exception for save answer failed.
     *
     * @return static
     */
    public static function saveAnswerFailed(): static
    {
        return new static('exceptions_services_quizservice.quiz_save_answer_failed');
    }

    /**
     * Create exception for calculate score failed.
     *
     * @return static
     */
    public static function calculateScoreFailed(): static
    {
        return new static('exceptions_services_quizservice.quiz_calculate_score_failed');
    }

    /**
     * Create exception for generate report failed.
     *
     * @return static
     */
    public static function generateReportFailed(): static
    {
        return new static('exceptions_services_quizservice.quiz_generate_report_failed');
    }

    /**
     * Create exception for grading failed.
     *
     * @return static
     */
    public static function gradingFailed(): static
    {
        return new static('exceptions_services_quizservice.quiz_grading_failed');
    }

    /**
     * Create exception for auto grading not available.
     *
     * @return static
     */
    public static function autoGradingNotAvailable(): static
    {
        return new static('exceptions_services_quizservice.quiz_auto_grading_not_available');
    }

    /**
     * Create exception for manual grading required.
     *
     * @return static
     */
    public static function manualGradingRequired(): static
    {
        return new static('exceptions_services_quizservice.quiz_manual_grading_required');
    }

    /**
     * Create exception for score calculation error.
     *
     * @return static
     */
    public static function scoreCalculationError(): static
    {
        return new static('exceptions_services_quizservice.quiz_score_calculation_error');
    }

    /**
     * Create exception for statistics not available.
     *
     * @return static
     */
    public static function statisticsNotAvailable(): static
    {
        return new static('exceptions_services_quizservice.quiz_statistics_not_available');
    }

    /**
     * Create exception for insufficient data for statistics.
     *
     * @return static
     */
    public static function insufficientDataForStatistics(): static
    {
        return new static('exceptions_services_quizservice.quiz_insufficient_data_for_statistics');
    }

    /**
     * Create exception for invalid configuration.
     *
     * @return static
     */
    public static function invalidConfiguration(): static
    {
        return new static('exceptions_services_quizservice.quiz_invalid_configuration');
    }

    /**
     * Create exception for randomization failed.
     *
     * @return static
     */
    public static function randomizationFailed(): static
    {
        return new static('exceptions_services_quizservice.quiz_randomization_failed');
    }

    /**
     * Create exception for time limit exceeded.
     *
     * @return static
     */
    public static function timeLimitExceeded(): static
    {
        return new static('exceptions_services_quizservice.quiz_time_limit_exceeded');
    }

    /**
     * Create exception for cannot delete published quiz.
     *
     * @return static
     */
    public static function cannotDeletePublished(): static
    {
        return new static('exceptions_services_quizservice.quiz_cannot_delete_published');
    }

    /**
     * Create exception for cannot delete quiz with attempts.
     *
     * @return static
     */
    public static function cannotDeleteWithAttempts(): static
    {
        return new static('exceptions_services_quizservice.quiz_cannot_delete_with_attempts');
    }

    /**
     * Create exception for delete failed.
     *
     * @return static
     */
    public static function deleteFailed(): static
    {
        return new static('exceptions_services_quizservice.quiz_delete_failed');
    }

    /**
     * Create exception for cannot update published quiz.
     *
     * @return static
     */
    public static function cannotUpdatePublished(): static
    {
        return new static('exceptions_services_quizservice.quiz_cannot_update_published');
    }

    /**
     * Create exception for update failed.
     *
     * @return static
     */
    public static function updateFailed(): static
    {
        return new static('exceptions_services_quizservice.quiz_update_failed');
    }

    /**
     * Create exception for create failed.
     *
     * @return static
     */
    public static function createFailed(): static
    {
        return new static('exceptions_services_quizservice.quiz_create_failed');
    }

    /**
     * Create exception for duplicate title.
     *
     * @return static
     */
    public static function duplicateTitle(): static
    {
        return new static('exceptions_services_quizservice.quiz_duplicate_title');
    }

    /**
     * Create exception for course not found.
     *
     * @return static
     */
    public static function courseNotFound(): static
    {
        return new static('exceptions_services_quizservice.quiz_course_not_found');
    }

    /**
     * Create exception for course not active.
     *
     * @return static
     */
    public static function courseNotActive(): static
    {
        return new static('exceptions_services_quizservice.quiz_course_not_active');
    }

    /**
     * Create exception for quiz not belongs to course.
     *
     * @return static
     */
    public static function quizNotBelongsToCourse(): static
    {
        return new static('exceptions_services_quizservice.quiz_not_belongs_to_course');
    }
}