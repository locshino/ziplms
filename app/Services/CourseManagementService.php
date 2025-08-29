<?php

namespace App\Services;

use App\Enums\Status\AssignmentStatus;
use App\Enums\Status\CourseStatus;
use App\Enums\Status\QuizStatus;
use App\Exceptions\Services\CourseManagementServiceException;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Interfaces\AssignmentRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\CourseManagementServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Course Management Service.
 *
 * This service coordinates between multiple repositories to provide
 * comprehensive course management functionality.
 */
class CourseManagementService implements CourseManagementServiceInterface
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository,
        private UserRepositoryInterface $userRepository,
        private AssignmentRepositoryInterface $assignmentRepository,
        private QuizRepositoryInterface $quizRepository
    ) {}

    /**
     * Create a new course with instructor assignment.
     */
    public function createCourseWithInstructor(array $courseData, string $instructorId): Course
    {
        try {
            // Validate instructor exists and has correct role
            $instructor = $this->userRepository->findById($instructorId);
            if (! $instructor || ! $this->userRepository->hasRole($instructorId, 'TEACHER')) {
                throw CourseManagementServiceException::invalidInstructor($instructorId);
            }

            return DB::transaction(function () use ($courseData, $instructorId) {
                // Create the course
                $course = $this->courseRepository->create($courseData);

                // Assign instructor to course
                $course->managers()->attach($instructorId);

                Log::info('Course created with instructor', [
                    'course_id' => $course->id,
                    'instructor_id' => $instructorId,
                ]);

                return $course;
            });
        } catch (\Exception $e) {
            if ($e instanceof CourseManagementServiceException) {
                throw $e;
            }
            throw CourseManagementServiceException::operationFailed('createCourseWithInstructor', $e->getMessage());
        }
    }

    /**
     * Enroll a student in a course.
     */
    public function enrollStudent(string $studentId, string $courseId): bool
    {
        try {
            // Validate student exists and has correct role
            $student = $this->userRepository->findById($studentId);
            if (! $student || ! $this->userRepository->hasRole($studentId, 'STUDENT')) {
                throw CourseManagementServiceException::invalidStudent($studentId);
            }

            // Validate course exists and is active
            $course = $this->courseRepository->findById($courseId);
            if (! $course) {
                throw CourseManagementServiceException::courseNotFound($courseId);
            }

            if ($course->status !== 'active') {
                throw CourseManagementServiceException::courseNotActive($courseId);
            }

            // Check if already enrolled
            if ($this->userRepository->isStudentEnrolledInCourse($studentId, $courseId)) {
                throw CourseManagementServiceException::alreadyEnrolled($studentId, $courseId);
            }

            // Check enrollment capacity
            $enrollmentCount = $this->courseRepository->getEnrollmentCount($courseId);
            if ($course->max_students && $enrollmentCount >= $course->max_students) {
                throw CourseManagementServiceException::enrollmentCapacityExceeded($courseId);
            }

            return DB::transaction(function () use ($studentId, $courseId) {
                // Enroll student
                $course = $this->courseRepository->findById($courseId);
                $course->students()->attach($studentId, [
                    'enrolled_at' => now(),
                    'status' => 'active',
                ]);

                Log::info('Student enrolled in course', [
                    'student_id' => $studentId,
                    'course_id' => $courseId,
                ]);

                return true;
            });
        } catch (\Exception $e) {
            if ($e instanceof CourseManagementServiceException) {
                throw $e;
            }
            throw CourseManagementServiceException::operationFailed('enrollStudent', $e->getMessage());
        }
    }

    /**
     * Get comprehensive course dashboard data for an instructor.
     */
    public function getCourseDashboard(string $courseId, string $instructorId): array
    {
        try {
            // Validate instructor access to course
            if (! $this->userRepository->isInstructorOfCourse($instructorId, $courseId)) {
                throw CourseManagementServiceException::unauthorized($instructorId, 'view_course_dashboard');
            }

            $course = $this->courseRepository->getCourseWithEnrollments($courseId);
            if (! $course) {
                throw CourseManagementServiceException::courseNotFound($courseId);
            }

            // Get course statistics
            $enrollmentCount = $this->courseRepository->getEnrollmentCount($courseId);
            $completionStats = $this->courseRepository->getCourseCompletionStats($courseId);

            // Get assignments and quizzes
            $assignments = $this->assignmentRepository->getAssignmentsByCourse($courseId);
            $quizzes = $this->quizRepository->getQuizzesByCourse($courseId);

            // Get pending grading items
            $pendingAssignments = $this->assignmentRepository->getAssignmentsRequiringGrading($courseId);
            $recentQuizAttempts = $this->quizRepository->getRecentQuizAttempts($courseId, 7);

            return [
                'course' => $course,
                'statistics' => [
                    'total_students' => $enrollmentCount,
                    'completion_rate' => $completionStats['completion_rate'] ?? 0,
                    'average_progress' => $completionStats['average_progress'] ?? 0,
                    'total_assignments' => $assignments->count(),
                    'total_quizzes' => $quizzes->count(),
                    'pending_grading' => $pendingAssignments->count(),
                    'recent_quiz_attempts' => $recentQuizAttempts->count(),
                ],
                'recent_activity' => [
                    'assignments' => $assignments->take(5),
                    'quizzes' => $quizzes->take(5),
                    'pending_grading' => $pendingAssignments->take(10),
                ],
            ];
        } catch (\Exception $e) {
            if ($e instanceof CourseManagementServiceException) {
                throw $e;
            }
            throw CourseManagementServiceException::operationFailed('getCourseDashboard', $e->getMessage());
        }
    }

    /**
     * Get student progress in a course.
     */
    public function getStudentProgress(string $studentId, string $courseId): array
    {
        try {
            // Validate student enrollment
            if (! $this->userRepository->isStudentEnrolledInCourse($studentId, $courseId)) {
                throw CourseManagementServiceException::unauthorized($studentId, 'view_course_progress');
            }

            // Get learning progress from user repository
            $learningProgress = $this->userRepository->getLearningProgress($studentId, $courseId);

            // Get detailed assignment progress
            $assignmentProgress = $this->assignmentRepository->getStudentAssignmentProgress($studentId, $courseId);

            // Get quiz performance
            $quizPerformance = $this->quizRepository->getStudentQuizPerformance($studentId, $courseId);

            // Calculate overall progress
            $totalItems = ($learningProgress['assignments']['total'] ?? 0) + ($learningProgress['quizzes']['total'] ?? 0);
            $completedItems = ($learningProgress['assignments']['completed'] ?? 0) + ($learningProgress['quizzes']['completed'] ?? 0);
            $overallProgress = $totalItems > 0 ? round(($completedItems / $totalItems) * 100, 2) : 0;

            return [
                'overall_progress' => $overallProgress,
                'learning_progress' => $learningProgress,
                'assignment_details' => $assignmentProgress,
                'quiz_performance' => $quizPerformance,
                'completion_status' => $overallProgress >= 80 ? 'completed' : 'in_progress',
            ];
        } catch (\Exception $e) {
            if ($e instanceof CourseManagementServiceException) {
                throw $e;
            }
            throw CourseManagementServiceException::operationFailed('getStudentProgress', $e->getMessage());
        }
    }

    /**
     * Get courses with enrollment statistics.
     */
    public function getCoursesWithStats(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->courseRepository->getPaginatedCoursesWithFilters($filters, $perPage, [
                'managers', 'students',
            ]);
        } catch (\Exception $e) {
            throw CourseManagementServiceException::operationFailed('getCoursesWithStats', $e->getMessage());
        }
    }

    /**
     * Archive a course and handle related data.
     */
    public function archiveCourse(string $courseId, string $adminId): bool
    {
        try {
            // Validate admin permissions
            $admin = $this->userRepository->findById($adminId);
            if (! $admin || ! $this->userRepository->hasRole($adminId, 'ADMIN')) {
                throw CourseManagementServiceException::unauthorized($adminId, 'archive_course');
            }

            $course = $this->courseRepository->findById($courseId);
            if (! $course) {
                throw CourseManagementServiceException::courseNotFound($courseId);
            }

            return DB::transaction(function () use ($courseId, $adminId) {
                // Update course status
                $this->courseRepository->updateCourseById($courseId, [
                    'status' => CourseStatus::ARCHIVED->value,
                    'archived_at' => now(),
                    'archived_by' => $adminId,
                ]);

                // Archive related assignments
                $assignments = $this->assignmentRepository->getAssignmentsByCourse($courseId);
                foreach ($assignments as $assignment) {
                    $this->assignmentRepository->updateAssignmentById($assignment->id, [
                        'status' => AssignmentStatus::ARCHIVED->value,
                    ]);
                }

                // Archive related quizzes
                $quizzes = $this->quizRepository->getQuizzesByCourse($courseId);
                foreach ($quizzes as $quiz) {
                    $this->quizRepository->updateQuizById($quiz->id, [
                        'status' => QuizStatus::ARCHIVED->value,
                    ]);
                }

                Log::info('Course archived', [
                    'course_id' => $courseId,
                    'admin_id' => $adminId,
                ]);

                return true;
            });
        } catch (\Exception $e) {
            if ($e instanceof CourseManagementServiceException) {
                throw $e;
            }
            throw CourseManagementServiceException::operationFailed('archiveCourse', $e->getMessage());
        }
    }

    /**
     * Get course completion report.
     */
    public function getCourseCompletionReport(string $courseId): array
    {
        try {
            $course = $this->courseRepository->findById($courseId);
            if (! $course) {
                throw CourseManagementServiceException::courseNotFound($courseId);
            }

            // Get completion statistics
            $completionStats = $this->courseRepository->getCourseCompletionStats($courseId);
            $progressDistribution = $this->courseRepository->getCourseProgressDistribution($courseId);

            // Get assignment completion rates
            $assignmentStats = $this->assignmentRepository->getAssignmentCompletionStats($courseId);

            // Get quiz performance statistics
            $quizStats = $this->quizRepository->getQuizPerformanceStats($courseId);

            return [
                'course_info' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'total_students' => $this->courseRepository->getEnrollmentCount($courseId),
                ],
                'completion_overview' => $completionStats,
                'progress_distribution' => $progressDistribution,
                'assignment_performance' => $assignmentStats,
                'quiz_performance' => $quizStats,
                'generated_at' => now()->toISOString(),
            ];
        } catch (\Exception $e) {
            if ($e instanceof CourseManagementServiceException) {
                throw $e;
            }
            throw CourseManagementServiceException::operationFailed('getCourseCompletionReport', $e->getMessage());
        }
    }

    /**
     * Bulk enroll students in a course.
     */
    public function bulkEnrollStudents(array $studentIds, string $courseId): array
    {
        try {
            $course = $this->courseRepository->findById($courseId);
            if (! $course) {
                throw CourseManagementServiceException::courseNotFound($courseId);
            }

            if ($course->status !== 'active') {
                throw CourseManagementServiceException::courseNotActive($courseId);
            }

            $results = [
                'successful' => [],
                'failed' => [],
                'already_enrolled' => [],
                'summary' => [
                    'total_processed' => count($studentIds),
                    'successful_count' => 0,
                    'failed_count' => 0,
                    'already_enrolled_count' => 0,
                ],
            ];

            foreach ($studentIds as $studentId) {
                try {
                    // Check if student exists and has correct role
                    $student = $this->userRepository->findById($studentId);
                    if (! $student || ! $this->userRepository->hasRole($studentId, 'STUDENT')) {
                        $results['failed'][] = [
                            'student_id' => $studentId,
                            'reason' => 'Invalid student or incorrect role',
                        ];

                        continue;
                    }

                    // Check if already enrolled
                    if ($this->userRepository->isStudentEnrolledInCourse($studentId, $courseId)) {
                        $results['already_enrolled'][] = $studentId;

                        continue;
                    }

                    // Enroll student
                    DB::transaction(function () use ($studentId, $courseId) {
                        $course = $this->courseRepository->findById($courseId);
                        $course->students()->attach($studentId, [
                            'enrolled_at' => now(),
                            'status' => 'active',
                        ]);
                    });

                    $results['successful'][] = $studentId;
                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'student_id' => $studentId,
                        'reason' => $e->getMessage(),
                    ];
                }
            }

            // Update summary counts
            $results['summary']['successful_count'] = count($results['successful']);
            $results['summary']['failed_count'] = count($results['failed']);
            $results['summary']['already_enrolled_count'] = count($results['already_enrolled']);

            Log::info('Bulk enrollment completed', [
                'course_id' => $courseId,
                'summary' => $results['summary'],
            ]);

            return $results;
        } catch (\Exception $e) {
            if ($e instanceof CourseManagementServiceException) {
                throw $e;
            }
            throw CourseManagementServiceException::operationFailed('bulkEnrollStudents', $e->getMessage());
        }
    }
}
