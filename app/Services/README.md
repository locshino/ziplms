# ZipLMS Services Layer

This directory contains the Service layer implementation for the ZipLMS application. Services act as the business logic layer between Controllers and Repositories, providing a clean separation of concerns and encapsulating complex business operations.

## Architecture Overview

The Service layer follows these principles:

- **Business Logic Encapsulation**: All business rules and complex operations are handled in services
- **Repository Abstraction**: Services interact with repositories through interfaces
- **Transaction Management**: Database transactions are managed at the service level
- **Validation**: Business validation logic is implemented in services
- **Error Handling**: Comprehensive exception handling for business operations

## Directory Structure

```
app/Services/
├── BaseService.php                    # Abstract base service class
├── Interfaces/
│   ├── BaseServiceInterface.php       # Base service interface
│   ├── UserServiceInterface.php       # User service contract
│   ├── CourseServiceInterface.php     # Course service contract
│   ├── AssignmentServiceInterface.php # Assignment service contract
│   ├── QuizServiceInterface.php       # Quiz service contract
│   └── EnrollmentServiceInterface.php # Enrollment service contract
├── UserService.php                    # User business logic
├── CourseService.php                  # Course business logic
├── AssignmentService.php              # Assignment business logic
├── QuizService.php                    # Quiz business logic
└── EnrollmentService.php              # Enrollment business logic
```

## Available Services

### 1. UserService

Handles user-related business operations:

**Key Methods:**

- `createUser(array $payload)` - Create user with password hashing and validation
- `updatePassword(string $userId, string $newPassword)` - Secure password updates
- `findByEmail(string $email)` - Find user by email address
- `getUsersByRole(string $role)` - Get users by specific role
- `getTeachers()` / `getStudents()` - Get users by role shortcuts
- `searchUsers(string $query)` - Search users by name or email
- `toggleUserStatus(string $userId)` - Activate/deactivate users
- `assignRole(string $userId, string $role)` - Role management

### 2. CourseService

Manages course-related business logic:

**Key Methods:**

- `createCourse(array $payload)` - Create course with validation and defaults
- `getCoursesByTeacher(string $teacherId)` - Get teacher's courses
- `getPublishedCourses()` - Get all published courses
- `getCoursesWithStats()` - Get courses with enrollment statistics
- `searchCourses(string $query)` - Search courses by title/description
- `getCoursesByCategory(string $category)` - Filter by category
- `enrollStudent(string $courseId, string $studentId)` - Handle enrollment
- `togglePublishStatus(string $courseId)` - Publish/unpublish courses
- `canStudentAccessCourse(string $studentId, string $courseId)` - Access control

### 3. AssignmentService

Handles assignment business operations:

**Key Methods:**

- `createAssignment(array $payload)` - Create with date validation
- `getAssignmentsByCourse(string $courseId)` - Get course assignments
- `getAssignmentsForStudent(string $studentId)` - Get student's assignments
- `getUpcomingAssignments()` / `getOverdueAssignments()` - Filter by status
- `getAssignmentsRequiringGrading()` - Get ungraded submissions
- `canStudentSubmit(string $assignmentId, string $studentId)` - Submission validation
- `isOverdue(string $assignmentId)` / `isAvailable(string $assignmentId)` - Status checks
- `getAssignmentStats(string $assignmentId)` - Submission statistics

### 4. QuizService

Manages quiz-related business logic:

**Key Methods:**

- `createQuiz(array $payload)` - Create with date and duration validation
- `getQuizzesByCourse(string $courseId)` - Get course quizzes
- `getActiveQuizzes()` / `getUpcomingQuizzes()` - Filter by status
- `getQuizzesForStudent(string $studentId)` - Get student's available quizzes
- `canStudentTakeQuiz(string $quizId, string $studentId)` - Attempt validation
- `isQuizActive(string $quizId)` - Check if quiz is currently active
- `getStudentAttempts(string $quizId, string $studentId)` - Get attempt history
- `getRemainingAttempts(string $quizId, string $studentId)` - Calculate remaining attempts
- `getQuizStats(string $quizId)` - Quiz performance statistics
- `getStudentBestScore(string $quizId, string $studentId)` - Best score calculation

### 5. EnrollmentService

Handles enrollment business operations:

**Key Methods:**

- `enrollStudent(string $studentId, string $courseId)` - Enroll with validation
- `getEnrollmentsByCourse(string $courseId)` - Get course enrollments
- `getEnrollmentsByStudent(string $studentId)` - Get student enrollments
- `isStudentEnrolled(string $studentId, string $courseId)` - Check enrollment status
- `hasActiveEnrollment(string $studentId, string $courseId)` - Check active status
- `updateEnrollmentStatus(string $enrollmentId, string $status)` - Status management
- `dropStudent(string $studentId, string $courseId)` - Handle course drops
- `completeEnrollment(string $studentId, string $courseId)` - Mark completion
- `getCourseEnrollmentStats(string $courseId)` - Course enrollment analytics
- `bulkEnrollStudents(array $studentIds, string $courseId)` - Bulk operations

## Usage Examples

### In Controllers

```php
<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\CourseServiceInterface;
use App\Services\Interfaces\EnrollmentServiceInterface;

class CourseController extends Controller
{
    public function __construct(
        private CourseServiceInterface $courseService,
        private EnrollmentServiceInterface $enrollmentService
    ) {}

    public function store(Request $request)
    {
        try {
            $course = $this->courseService->createCourse($request->validated());
            return response()->json($course, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function enroll(Request $request, string $courseId)
    {
        try {
            $enrollment = $this->enrollmentService->enrollStudent(
                $request->user()->id,
                $courseId
            );
            return response()->json($enrollment, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
```

### In Artisan Commands

```php
<?php

namespace App\Console\Commands;

use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    public function __construct(
        private UserServiceInterface $userService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $userData = [
            'name' => 'Admin User',
            'email' => 'admin@ziplms.com',
            'password' => 'secure-password',
            'role' => 'admin'
        ];

        $user = $this->userService->createUser($userData);
        $this->info("Admin user created: {$user->email}");
    }
}
```

## Service Registration

Services are automatically registered through Laravel's service container. The system uses interface binding to ensure proper dependency injection:

```php
// In a Service Provider
$this->app->bind(UserServiceInterface::class, UserService::class);
$this->app->bind(CourseServiceInterface::class, CourseService::class);
// ... other bindings
```

## Key Features

### 1. Transaction Management

Services handle database transactions automatically for complex operations:

```php
public function createCourse(array $payload): Model
{
    return DB::transaction(function () use ($payload) {
        // Multiple database operations
        return $this->courseRepository->create($payload);
    });
}
```

### 2. Business Validation

Services implement business rules and validation:

```php
public function enrollStudent(string $studentId, string $courseId): Model
{
    // Business validation
    if ($this->isStudentEnrolled($studentId, $courseId)) {
        throw new Exception('Student already enrolled');
    }
    
    // Proceed with enrollment
}
```

### 3. Error Handling

Comprehensive exception handling with meaningful messages:

```php
public function deleteAssignment(string $assignmentId): bool
{
    $assignment = $this->getAssignmentWithSubmissions($assignmentId);
    
    if ($assignment->submissions->count() > 0) {
        throw new Exception('Cannot delete assignment with submissions');
    }
    
    return $this->assignmentRepository->deleteById($assignmentId);
}
```

### 4. Statistics and Analytics

Services provide business intelligence methods:

```php
public function getCourseEnrollmentStats(string $courseId): array
{
    return [
        'total_enrollments' => $totalEnrollments,
        'completion_rate' => $completionRate,
        'dropout_rate' => $dropoutRate,
        // ... more statistics
    ];
}
```

## Best Practices

1. **Single Responsibility**: Each service handles one domain area
2. **Interface Segregation**: Use specific interfaces for each service
3. **Dependency Injection**: Inject repository interfaces, not concrete classes
4. **Exception Handling**: Throw meaningful exceptions for business rule violations
5. **Transaction Management**: Use database transactions for multi-step operations
6. **Validation**: Implement business validation in services, not repositories
7. **Documentation**: Document complex business logic and edge cases

## Extending Services

To create a new service:

1. Create the service interface in `Interfaces/`
2. Implement the service class extending `BaseService`
3. Register the binding in a service provider
4. Inject required repository interfaces
5. Implement business logic with proper validation and error handling

Example:

```php
<?php

namespace App\Services;

use App\Services\Interfaces\NotificationServiceInterface;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class NotificationService extends BaseService implements NotificationServiceInterface
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository
    ) {
        parent::__construct($notificationRepository);
    }

    public function sendNotification(array $payload): Model
    {
        // Business logic implementation
    }
}
```

This service layer provides a robust foundation for implementing complex business logic while maintaining clean architecture principles and ensuring code maintainability.
