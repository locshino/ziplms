<?php

namespace Database\Seeders;

use App\Enums\Status\AssignmentStatus;
use App\Enums\Status\SubmissionStatus;
use App\Enums\System\RoleSystem;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AssignmentSeeder extends Seeder
{
    private array $assignmentsData = [];

    public function __construct()
    {
        $this->initializeData();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::with('tags')->get();
        $teachers = User::role(RoleSystem::TEACHER->value)->get();

        foreach ($courses as $course) {
            $enrolledStudents = $course->students;
            $courseTags = $course->tags->pluck('name')->toArray();

            // Find relevant assignments based on course tags
            $relevantAssignmentKeys = collect($this->assignmentsData)
                ->filter(fn ($assignment) => ! empty(array_intersect($courseTags, $assignment['tags'])))
                ->keys()
                ->toArray();

            // Create 1-2 assignments for each course
            $selectedAssignmentKeys = Arr::random($relevantAssignmentKeys, min(count($relevantAssignmentKeys), rand(1, 2)));

            foreach ($selectedAssignmentKeys as $key) {
                $assignmentData = $this->assignmentsData[$key];
                $assignment = Assignment::factory()->create([
                    'title' => $assignmentData['title'],
                    'description' => $assignmentData['description'],
                    'status' => AssignmentStatus::PUBLISHED->value,
                ]);

                // Generate valid and sequential date ranges
                $startAt = fake()->dateTimeBetween('-2 months', 'now -1 week');
                $endSubmissionAt = fake()->dateTimeBetween($startAt, (clone $startAt)->add(new \DateInterval('P3M')));
                $startGradingAt = fake()->dateTimeBetween($endSubmissionAt, (clone $endSubmissionAt)->add(new \DateInterval('P1D')));
                $endAt = fake()->dateTimeBetween($startGradingAt, (clone $startGradingAt)->add(new \DateInterval('P2W')));

                // Attach assignment to the course with all pivot data
                $course->assignments()->attach($assignment->id, [
                    'start_at' => $startAt,
                    'end_submission_at' => $endSubmissionAt,
                    'start_grading_at' => $startGradingAt,
                    'end_at' => $endAt,
                ]);

                // Create submissions
                if ($enrolledStudents->isNotEmpty()) {
                    $this->createSubmissions($assignment, $enrolledStudents, $startAt, $endSubmissionAt, $teachers);
                }
            }
        }
    }

    /**
     * Create submissions for an assignment.
     */
    private function createSubmissions(Assignment $assignment, $enrolledStudents, $startAt, $endSubmissionAt, $teachers): void
    {
        // Only create submissions if the assignment has started
        if ($startAt > now()) {
            return;
        }

        $studentsToSubmit = $enrolledStudents->random(min(20, $enrolledStudents->count()));

        foreach ($studentsToSubmit as $student) {
            // 80% chance to submit
            if (! fake()->boolean(80)) {
                continue;
            }

            $isLate = fake()->boolean(20); // 20% chance of being late
            $isEndedSubmission = $endSubmissionAt < now();

            if ($isLate && $isEndedSubmission) {
                // If it's a late submission and the end date has passed
                $submittedAt = fake()->dateTimeBetween($endSubmissionAt, (clone $endSubmissionAt)->modify('+5 days'));
            } else {
                // Otherwise, the submission is on time
                $submittedAt = fake()->dateTimeBetween($startAt, min($endSubmissionAt, now()));
            }

            // Ensure submittedAt is not before startAt
            if ($submittedAt < $startAt) {
                $submittedAt = $startAt;
            }

            $status = $isLate ? SubmissionStatus::LATE : SubmissionStatus::SUBMITTED;

            $submission = Submission::factory()->create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'status' => $status->value,
                'submitted_at' => $submittedAt,
            ]);

            // 60% chance of being graded
            if (fake()->boolean(60)) {
                $points = $isLate
                    ? fake()->randomFloat(2, 0, $assignment->max_points * 0.8) // Penalty
                    : fake()->randomFloat(2, 5, $assignment->max_points);

                // Ensure gradedAt is after submittedAt and not in the future
                $startForGrading = min($submittedAt, now());
                $gradedAt = fake()->dateTimeBetween($startForGrading, 'now');

                $submission->update([
                    'status' => SubmissionStatus::GRADED->value,
                    'points' => $points,
                    'graded_by' => $teachers->random()->id,
                    'graded_at' => $gradedAt,
                    'feedback' => fake()->paragraph(2).($isLate ? ' (Đã trừ điểm nộp muộn)' : ''),
                ]);
            }
        }
    }

    /**
     * Initialize realistic assignment data.
     */
    private function initializeData(): void
    {
        $this->assignmentsData = [
            'php_blog' => [
                'title' => 'Xây dựng một Blog đơn giản bằng PHP thuần',
                'description' => 'Yêu cầu: Tạo một ứng dụng web blog cho phép người dùng xem, thêm, sửa, xóa bài viết. Sử dụng PHP thuần và kết nối cơ sở dữ liệu MySQL. Nộp mã nguồn và file SQL.',
                'tags' => ['PHP', 'Web Development', 'MySQL'],
            ],
            'laravel_api' => [
                'title' => 'Phát triển RESTful API cho ứng dụng E-commerce với Laravel',
                'description' => 'Thiết kế và xây dựng các endpoint API cho sản phẩm, đơn hàng và người dùng. Yêu cầu có xác thực (authentication) và phân quyền (authorization). Viết tài liệu API bằng Postman hoặc Swagger.',
                'tags' => ['Laravel', 'API', 'Web Development'],
            ],
            'react_todo' => [
                'title' => 'Xây dựng ứng dụng To-Do List bằng ReactJS',
                'description' => 'Tạo một giao diện người dùng cho phép thêm, xóa, đánh dấu hoàn thành công việc. Sử dụng React Hooks (useState, useEffect) để quản lý state. Nộp mã nguồn React.',
                'tags' => ['ReactJS', 'JavaScript', 'UI/UX Design'],
            ],
            'data_analysis_pandas' => [
                'title' => 'Phân tích dữ liệu bán hàng với Pandas',
                'description' => 'Sử dụng thư viện Pandas trong Python để làm sạch, phân tích và trực quan hóa một bộ dữ liệu bán hàng (cung cấp sẵn). Trả lời các câu hỏi kinh doanh và trình bày kết quả bằng biểu đồ. Nộp file Jupyter Notebook.',
                'tags' => ['Data Science', 'Python', 'Pandas'],
            ],
            'dockerize_app' => [
                'title' => 'Docker hóa một ứng dụng web',
                'description' => 'Viết một Dockerfile để đóng gói một ứng dụng web (tùy chọn ngôn ngữ: PHP, Node.js, Python). Sử dụng Docker Compose để quản lý môi trường phát triển bao gồm web server và database. Nộp file Dockerfile và docker-compose.yml.',
                'tags' => ['DevOps', 'Docker', 'Cloud Computing'],
            ],
        ];
    }
}
