<?php

namespace App\Services;

use App\Exceptions\Repositories\RepositoryException;
use App\Exceptions\Services\ServiceException;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Services\Interfaces\CourseServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Course service implementation.
 * 
 * Handles course-related business logic operations.
 * 
 * @throws ServiceException When course service operations fail
 * @throws RepositoryException When repository operations fail
 */
class CourseService extends BaseService implements CourseServiceInterface
{
    /**
     * CourseService constructor.
     *
     * @param CourseRepositoryInterface $courseRepository
     * @param EnrollmentRepositoryInterface $enrollmentRepository
     */
    public function __construct(
        private CourseRepositoryInterface $courseRepository,
        private EnrollmentRepositoryInterface $enrollmentRepository
    ) {
        parent::__construct($courseRepository);
    }

    /**
     * Create a new course.
     *
     * @param array $payload
     * @return Model
     * @throws ServiceException When course validation fails
     * @throws RepositoryException When course creation fails
     * @throws Exception When transaction fails
     */
    public function createCourse(array $payload): Model
    {
        try {
            return DB::transaction(function () use ($payload) {
                // Set default values
                $payload['is_published'] = $payload['is_published'] ?? false;
                $payload['created_at'] = now();
                
                return $this->courseRepository->create($payload);
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (ServiceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to create course: ' . $e->getMessage());
        }
    }

    /**
     * Get courses by instructor.
     *
     * @param string $instructorId
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getCoursesByInstructor(string $instructorId): Collection
    {
        try {
            return $this->courseRepository->getCoursesByInstructor($instructorId);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get courses by instructor: ' . $e->getMessage());
        }
    }

    /**
     * Get all published courses.
     *
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getPublishedCourses(): Collection
    {
        try {
            return $this->courseRepository->getPublishedCourses();
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get published courses: ' . $e->getMessage());
        }
    }

    /**
     * Get courses with enrollment statistics.
     *
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getCoursesWithStats(): Collection
    {
        try {
            return $this->courseRepository->getCoursesWithEnrollmentsCount();
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get courses with stats: ' . $e->getMessage());
        }
    }

    /**
     * Search courses by title or description.
     *
     * @param string $search
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function searchCourses(string $search): Collection
    {
        try {
            return $this->courseRepository->searchCourses($search);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to search courses: ' . $e->getMessage());
        }
    }

    /**
     * Get courses by category.
     *
     * @param string $category
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getCoursesByCategory(string $category): Collection
    {
        try {
            return $this->courseRepository->getCoursesByCategory($category);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get courses by category: ' . $e->getMessage());
        }
    }

    /**
     * Get course with full details including assignments and quizzes.
     *
     * @param string $courseId
     * @return Model|null
     * @throws RepositoryException When database error occurs
     */
    public function getCourseWithDetails(string $courseId): ?Model
    {
        try {
            return $this->courseRepository->getCourseWithDetails($courseId);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get course with details: ' . $e->getMessage());
        }
    }

    /**
     * Publish or unpublish a course.
     *
     * @param string $courseId
     * @param bool $isPublished
     * @return bool
     * @throws RepositoryException When update fails or course not found
     */
    public function togglePublishStatus(string $courseId, bool $isPublished): bool
    {
        try {
            return $this->courseRepository->updateById($courseId, [
                'is_published' => $isPublished,
                'published_at' => $isPublished ? now() : null
            ]);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to toggle publish status: ' . $e->getMessage());
        }
    }

    /**
     * Enroll a student in a course.
     *
     * @param string $courseId
     * @param string $studentId
     * @return bool
     * @throws ServiceException When course not found, not published, or student already enrolled
     * @throws RepositoryException When enrollment fails
     * @throws Exception When transaction fails
     */
    public function enrollStudent(string $courseId, string $studentId): bool
    {
        try {
            // Check if course exists and is published
            $course = $this->courseRepository->findById($courseId);
            if (!$course || !$course->is_published) {
                throw new Exception('Course not found or not published.');
            }

            // Check if student is already enrolled
            if ($this->enrollmentRepository->isStudentEnrolled($studentId, $courseId)) {
                throw new Exception('Student is already enrolled in this course.');
            }

            return DB::transaction(function () use ($studentId, $courseId) {
                $this->enrollmentRepository->enrollStudent($studentId, $courseId);
                return true;
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (ServiceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to enroll student: ' . $e->getMessage());
        }
    }

    /**
     * Get enrollment count for a course.
     *
     * @param string $courseId
     * @return int
     * @throws RepositoryException When database error occurs
     */
    public function getEnrollmentCount(string $courseId): int
    {
        try {
            return $this->enrollmentRepository->getEnrollmentCountByCourse($courseId);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get enrollment count: ' . $e->getMessage());
        }
    }

    /**
     * Get enrolled students for a course.
     *
     * @param string $courseId
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getEnrolledStudents(string $courseId): Collection
    {
        try {
            return $this->enrollmentRepository->getEnrollmentsByCourse($courseId);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get enrolled students: ' . $e->getMessage());
        }
    }

    /**
     * Check if a student can access a course.
     *
     * @param string $courseId
     * @param string $studentId
     * @return bool
     * @throws RepositoryException When database error occurs
     */
    public function canStudentAccessCourse(string $courseId, string $studentId): bool
    {
        try {
            $course = $this->courseRepository->findById($courseId);
            
            if (!$course || !$course->is_published) {
                return false;
            }

            return $this->enrollmentRepository->isStudentEnrolled($studentId, $courseId);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to check student course access: ' . $e->getMessage());
        }
    }

    /**
     * Update course information.
     *
     * @param string $courseId
     * @param array $payload
     * @return bool
     * @throws RepositoryException When update fails or course not found
     */
    public function updateCourse(string $courseId, array $payload): bool
    {
        try {
            // Remove sensitive fields that shouldn't be updated directly
            unset($payload['id'], $payload['created_at']);
            
            $payload['updated_at'] = now();
            
            return DB::transaction(function () use ($courseId, $payload) {
                return $this->courseRepository->updateById($courseId, $payload);
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to update course: ' . $e->getMessage());
        }
    }

    /**
     * Delete a course (soft delete).
     *
     * @param string $courseId
     * @return bool
     * @throws ServiceException When course has active enrollments
     * @throws RepositoryException When deletion fails or course not found
     * @throws Exception When business logic validation fails
     */
    public function deleteCourse(string $courseId): bool
    {
        try {
            // Check if course has enrollments
            $enrollmentCount = $this->getEnrollmentCount($courseId);
            if ($enrollmentCount > 0) {
                throw new Exception('Cannot delete course with active enrollments.');
            }

            return DB::transaction(function () use ($courseId) {
                return $this->courseRepository->deleteById($courseId);
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (ServiceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to delete course: ' . $e->getMessage());
        }
    }
}