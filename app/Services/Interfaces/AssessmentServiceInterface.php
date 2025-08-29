<?php

namespace App\Services\Interfaces;

use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Submission;

/**
 * Interface for Assessment Service.
 *
 * This service coordinates between Assignment, Quiz, User, and Course repositories
 * to provide comprehensive assessment management functionality.
 */
interface AssessmentServiceInterface
{
    /**
     * Create assignment for a course with validation.
     *
     * @param  array  $assignmentData  Assignment creation data
     * @param  string  $courseId  Course ID
     * @param  string  $instructorId  Instructor user ID
     *
     * @throws \App\Exceptions\Services\AssessmentServiceException
     */
    public function createAssignmentForCourse(array $assignmentData, string $courseId, string $instructorId): Assignment;

    /**
     * Create quiz for a course with questions.
     *
     * @param  array  $quizData  Quiz creation data
     * @param  array  $questions  Array of question data
     * @param  string  $courseId  Course ID
     * @param  string  $instructorId  Instructor user ID
     *
     * @throws \App\Exceptions\Services\AssessmentServiceException
     */
    public function createQuizWithQuestions(array $quizData, array $questions, string $courseId, string $instructorId): Quiz;

    /**
     * Submit assignment solution.
     *
     * @param  string  $assignmentId  Assignment ID
     * @param  string  $studentId  Student user ID
     * @param  array  $submissionData  Submission data
     *
     * @throws \App\Exceptions\Services\AssessmentServiceException
     */
    public function submitAssignment(string $assignmentId, string $studentId, array $submissionData): Submission;

    /**
     * Start quiz attempt for student.
     *
     * @param  string  $quizId  Quiz ID
     * @param  string  $studentId  Student user ID
     *
     * @throws \App\Exceptions\Services\AssessmentServiceException
     */
    public function startQuizAttempt(string $quizId, string $studentId): QuizAttempt;

    /**
     * Submit quiz attempt with answers.
     *
     * @param  string  $attemptId  Quiz attempt ID
     * @param  array  $answers  Array of answers
     *
     * @throws \App\Exceptions\Services\AssessmentServiceException
     */
    public function submitQuizAttempt(string $attemptId, array $answers): QuizAttempt;

    /**
     * Grade assignment submission.
     *
     * @param  string  $submissionId  Submission ID
     * @param  float  $score  Score to assign
     * @param  string  $feedback  Optional feedback
     * @param  string  $graderId  Grader user ID
     *
     * @throws \App\Exceptions\Services\AssessmentServiceException
     */
    public function gradeAssignmentSubmission(string $submissionId, float $score, string $feedback, string $graderId): Submission;

    /**
     * Get comprehensive assessment overview for a course.
     *
     * @param  string  $courseId  Course ID
     * @param  string  $instructorId  Instructor user ID
     *
     * @throws \App\Exceptions\Services\AssessmentServiceException
     */
    public function getCourseAssessmentOverview(string $courseId, string $instructorId): array;

    /**
     * Get student assessment performance in a course.
     *
     * @param  string  $studentId  Student user ID
     * @param  string  $courseId  Course ID
     *
     * @throws \App\Exceptions\Services\AssessmentServiceException
     */
    public function getStudentAssessmentPerformance(string $studentId, string $courseId): array;

    /**
     * Get pending grading items for instructor.
     *
     * @param  string  $instructorId  Instructor user ID
     * @param  array  $courseIds  Optional course IDs filter
     *
     * @throws \App\Exceptions\Services\AssessmentServiceException
     */
    public function getPendingGradingItems(string $instructorId, array $courseIds = []): array;

    /**
     * Generate assessment analytics report.
     *
     * @param  string  $courseId  Course ID
     * @param  string  $type  Assessment type ('assignment', 'quiz', or 'all')
     *
     * @throws \App\Exceptions\Services\AssessmentServiceException
     */
    public function generateAssessmentAnalytics(string $courseId, string $type = 'all'): array;

    /**
     * Bulk grade multiple submissions.
     *
     * @param  array  $gradingData  Array of grading data
     * @param  string  $graderId  Grader user ID
     * @return array Results with success/failure counts
     *
     * @throws \App\Exceptions\Services\AssessmentServiceException
     */
    public function bulkGradeSubmissions(array $gradingData, string $graderId): array;
}
