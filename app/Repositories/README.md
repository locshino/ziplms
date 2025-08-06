# Repository Pattern Implementation

Hệ thống ZipLMS sử dụng Repository Pattern để tách biệt logic truy cập dữ liệu khỏi business logic.

## Cấu trúc

```
app/Repositories/
├── Interfaces/
│   ├── EloquentRepositoryInterface.php     # Base interface
│   ├── UserRepositoryInterface.php
│   ├── CourseRepositoryInterface.php
│   ├── AssignmentRepositoryInterface.php
│   ├── QuizRepositoryInterface.php
│   └── EnrollmentRepositoryInterface.php
└── Eloquent/
    ├── EloquentRepository.php              # Base repository
    ├── UserRepository.php
    ├── CourseRepository.php
    ├── AssignmentRepository.php
    ├── QuizRepository.php
    └── EnrollmentRepository.php
```

## Các Repository có sẵn

### 1. UserRepository

- `findByEmail(string $email)`: Tìm user theo email
- `getUsersByRole(string $role)`: Lấy users theo role
- `getActiveUsers()`: Lấy users đang hoạt động
- `searchUsers(string $search)`: Tìm kiếm users theo tên hoặc email

### 2. CourseRepository

- `getCoursesByInstructor(string $instructorId)`: Lấy khóa học theo giảng viên
- `getPublishedCourses()`: Lấy khóa học đã xuất bản
- `getCoursesWithEnrollmentsCount()`: Lấy khóa học kèm số lượng đăng ký
- `searchCourses(string $search)`: Tìm kiếm khóa học
- `getCoursesByCategory(string $category)`: Lấy khóa học theo danh mục
- `getCourseWithDetails(string $courseId)`: Lấy khóa học với thông tin chi tiết

### 3. AssignmentRepository

- `getAssignmentsByCourse(string $courseId)`: Lấy bài tập theo khóa học
- `getUpcomingAssignments()`: Lấy bài tập sắp tới
- `getOverdueAssignments()`: Lấy bài tập quá hạn
- `getAssignmentsByStudent(string $studentId)`: Lấy bài tập theo học sinh
- `getAssignmentWithSubmissions(string $assignmentId)`: Lấy bài tập kèm bài nộp
- `getAssignmentsRequiringGrading()`: Lấy bài tập cần chấm điểm

### 4. QuizRepository

- `getQuizzesByCourse(string $courseId)`: Lấy quiz theo khóa học
- `getActiveQuizzes()`: Lấy quiz đang hoạt động
- `getQuizWithQuestions(string $quizId)`: Lấy quiz kèm câu hỏi
- `getQuizzesByStudent(string $studentId)`: Lấy quiz theo học sinh
- `getQuizAttemptsByStudent(string $quizId, string $studentId)`: Lấy lần thử quiz
- `getQuizWithAttempts(string $quizId)`: Lấy quiz kèm kết quả
- `getUpcomingQuizzes()`: Lấy quiz sắp tới

### 5. EnrollmentRepository

- `getEnrollmentsByCourse(string $courseId)`: Lấy đăng ký theo khóa học
- `getEnrollmentsByStudent(string $studentId)`: Lấy đăng ký theo học sinh
- `isStudentEnrolled(string $studentId, string $courseId)`: Kiểm tra đăng ký
- `getRecentEnrollments(int $days)`: Lấy đăng ký gần đây
- `getEnrollmentCountByCourse(string $courseId)`: Đếm số đăng ký
- `getEnrollmentWithDetails(string $enrollmentId)`: Lấy đăng ký chi tiết
- `enrollStudent(string $studentId, string $courseId)`: Đăng ký học sinh

## Cách sử dụng

### Trong Controller

```php
<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class CourseController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CourseRepositoryInterface $courseRepository
    ) {}

    public function index()
    {
        $courses = $this->courseRepository->getPublishedCourses();
        return view('courses.index', compact('courses'));
    }

    public function show(string $id)
    {
        $course = $this->courseRepository->getCourseWithDetails($id);
        return view('courses.show', compact('course'));
    }
}
```

### Trong Service

```php
<?php

namespace App\Services;

use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class EnrollmentService
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollmentRepository,
        private CourseRepositoryInterface $courseRepository
    ) {}

    public function enrollStudent(string $studentId, string $courseId): bool
    {
        // Kiểm tra xem học sinh đã đăng ký chưa
        if ($this->enrollmentRepository->isStudentEnrolled($studentId, $courseId)) {
            return false;
        }

        // Đăng ký học sinh
        $this->enrollmentRepository->enrollStudent($studentId, $courseId);
        return true;
    }
}
```

## Tự động đăng ký

Các repository được tự động đăng ký thông qua `RepositoryRegisterProvider`. Provider này sẽ:

1. Quét thư mục `app/Repositories/Eloquent`
2. Tìm các class repository tương ứng
3. Bind interface với implementation trong Laravel container

## Mở rộng

Để tạo repository mới:

1. Tạo interface trong `app/Repositories/Interfaces/`
2. Tạo implementation trong `app/Repositories/Eloquent/`
3. Repository sẽ được tự động đăng ký

```php
// Interface
interface NewRepositoryInterface extends EloquentRepositoryInterface
{
    public function customMethod(): Collection;
}

// Implementation
class NewRepository extends EloquentRepository implements NewRepositoryInterface
{
    protected function model(): string
    {
        return NewModel::class;
    }

    public function customMethod(): Collection
    {
        return $this->model->where('condition', 'value')->get();
    }
}
```
