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
        $courses = Course::with(['tags', 'students'])->where('end_at', '>', now()->subMonth())->get(); // Only seed for recent/active courses
        $teachers = User::role(RoleSystem::TEACHER->value)->get();

        if ($teachers->isEmpty()) {
            return;
        }

        foreach ($courses as $course) {
            $this->createAssignmentsForCourse($course, $teachers);
        }
    }

    /**
     * Create assignments for a specific course.
     */
    private function createAssignmentsForCourse(Course $course, $teachers): void
    {
        $courseTags = $course->tags->pluck('name')->toArray();
        $relevantKeys = $this->getRelevantDataKeys($this->assignmentsData, $courseTags);

        // Ensure every course has at least 1-2 assignments
        if (empty($relevantKeys)) {
            $relevantKeys = array_keys($this->assignmentsData); // Fallback to all assignments
        }
        $selectedKeys = (count($relevantKeys) > 1) ? Arr::random($relevantKeys, rand(1, min(count($relevantKeys), 2))) : $relevantKeys;

        foreach ($selectedKeys as $key) {
            $assignmentData = $this->assignmentsData[$key];
            $assignment = Assignment::factory()->create([
                'title' => $assignmentData['title'],
                'description' => $assignmentData['description'],
                'status' => AssignmentStatus::PUBLISHED->value,
            ]);

            // Create logical, sequential dates within the course duration
            $courseStart = $course->start_at;
            $courseEnd = $course->end_at;

            $startAt = fake()->dateTimeBetween($courseStart, $courseEnd);
            $endSubmissionAt = fake()->dateTimeBetween($startAt, $courseEnd);
            $startGradingAt = $endSubmissionAt;
            $endAt = fake()->dateTimeBetween($startGradingAt, $courseEnd);

            $course->assignments()->attach($assignment->id, [
                'start_at' => $startAt,
                'end_submission_at' => $endSubmissionAt,
                'start_grading_at' => $startGradingAt,
                'end_at' => $endAt,
            ]);

            if ($course->students->isNotEmpty() && $startAt < now()) {
                $this->createSubmissions($assignment, $course->students, $startAt, $endSubmissionAt, $teachers);
            }
        }
    }

    /**
     * Create submissions for an assignment.
     */
    private function createSubmissions(Assignment $assignment, $students, $startAt, $endSubmissionAt, $teachers): void
    {
        // Select a subset of students to create submissions
        $studentsToSubmit = $students->random(min(20, $students->count()));

        foreach ($studentsToSubmit as $student) {
            if (! fake()->boolean(85)) { // 85% chance to submit
                continue;
            }

            $isLate = now() > $endSubmissionAt && fake()->boolean(20);
            $submittedAt = fake()->dateTimeBetween($startAt, now());
            if ($submittedAt > $endSubmissionAt) {
                $status = SubmissionStatus::LATE;
            } else {
                $status = SubmissionStatus::SUBMITTED;
            }

            $submission = Submission::factory()->create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'status' => $status->value,
                'submitted_at' => $submittedAt,
            ]);

            // 60% chance of being graded, if submission period is over
            if (now() > $endSubmissionAt && fake()->boolean(60)) {
                $points = $isLate
                    ? fake()->randomFloat(2, 0, $assignment->max_points * 0.8) // Late penalty
                    : fake()->randomFloat(2, 5, $assignment->max_points);

                $submission->update([
                    'status' => SubmissionStatus::GRADED->value,
                    'points' => $points,
                    'graded_by' => $teachers->random()->id,
                    'graded_at' => fake()->dateTimeBetween($submittedAt, now()),
                    'feedback' => fake()->paragraph(2),
                ]);
            }
        }
    }

    /**
     * Filter data keys based on tags.
     */
    private function getRelevantDataKeys(array $data, array $tags): array
    {
        return collect($data)
            ->filter(fn ($item) => ! empty(array_intersect($tags, $item['tags'])))
            ->keys()
            ->toArray();
    }

    /**
     * Initialize realistic assignment data.
     */
    private function initializeData(): void
    {
        $this->assignmentsData = [
            'php_blog' => [
                'title' => 'Xây dựng một Blog đơn giản bằng PHP thuần',
                'description' => 'Yêu cầu: Tạo một ứng dụng web blog cho phép người dùng xem, thêm, sửa, xóa bài viết. Sử dụng PHP thuần và MySQL.',
                'tags' => ['PHP', 'Web Development', 'MySQL'],
            ],
            'laravel_api' => [
                'title' => 'Phát triển RESTful API cho E-commerce với Laravel',
                'description' => 'Thiết kế và xây dựng các endpoint API cho sản phẩm, đơn hàng và người dùng. Yêu cầu có xác thực và phân quyền.',
                'tags' => ['Laravel', 'API', 'Web Development'],
            ],
            'react_todo' => [
                'title' => 'Xây dựng ứng dụng To-Do List bằng ReactJS',
                'description' => 'Tạo một giao diện người dùng cho phép thêm, xóa, đánh dấu hoàn thành công việc. Sử dụng React Hooks (useState, useEffect).',
                'tags' => ['ReactJS', 'JavaScript', 'UI/UX Design'],
            ],
            'data_analysis_pandas' => [
                'title' => 'Phân tích dữ liệu bán hàng với Pandas',
                'description' => 'Sử dụng thư viện Pandas trong Python để làm sạch, phân tích và trực quan hóa một bộ dữ liệu bán hàng.',
                'tags' => ['Data Science', 'Python', 'Pandas'],
            ],
            'dockerize_app' => [
                'title' => 'Docker hóa một ứng dụng web',
                'description' => 'Viết một Dockerfile và Docker Compose để đóng gói một ứng dụng web (PHP, Node.js, hoặc Python) và database.',
                'tags' => ['DevOps', 'Docker', 'Cloud Computing'],
            ],
        ];
    }
}
