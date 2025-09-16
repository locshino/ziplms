<?php

namespace Database\Seeders;

use App\Enums\Status\CourseStatus;
use App\Enums\System\RoleSystem;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users by role
        $teachers = User::role(RoleSystem::TEACHER->value)->get();
        $managers = User::role(RoleSystem::MANAGER->value)->get();
        $students = User::role(RoleSystem::STUDENT->value)->get();

        if ($teachers->isEmpty() || $students->isEmpty()) {
            $this->command->warn('Cannot seed courses without teachers and students.');

            return;
        }

        $coursesData = $this->getCoursesData();

        foreach ($coursesData as $index => $courseData) {
            // Create courses with varied and logical time periods
            if ($index < 3) {
                // 3 Past courses (already finished)
                $startDate = now()->subMonths(rand(4, 12));
                $endDate = $startDate->copy()->addWeeks(rand(8, 12));
            } elseif ($index < 7) {
                // 4 Ongoing courses (currently active, centered around now)
                $startDate = now()->subWeeks(rand(2, 4));
                $endDate = now()->addWeeks(rand(4, 8));
            } else {
                // 3 Future courses (not started yet)
                $startDate = now()->addDays(rand(14, 90));
                $endDate = $startDate->copy()->addWeeks(rand(8, 12));
            }

            $course = Course::factory()->create([
                'title' => $courseData['title'],
                'description' => $courseData['description'],
                'teacher_id' => $teachers->random()->id,
                'status' => CourseStatus::PUBLISHED->value,
                'start_at' => $startDate,
                'end_at' => $endDate,
            ]);

            // Attach tags to the course
            $course->syncTags($courseData['tags']);

            // Enroll managers and students
            $this->enrollUsers($course, $managers, 2, $startDate, $endDate);
            $this->enrollUsers($course, $students, 30, $startDate, $endDate);
        }
    }

    /**
     * Enroll a random set of users into a course.
     */
    private function enrollUsers(Course $course, Collection $users, int $count, \DateTime $courseStart, \DateTime $courseEnd): void
    {
        $enrolledUsers = $users->random(min($count, $users->count()));

        foreach ($enrolledUsers as $user) {
            $enrollmentStart = $courseStart->copy()->subDays(rand(1, 14));
            $enrollmentEnd = $courseEnd;

            $course->users()->attach($user->id, [
                'start_at' => $enrollmentStart,
                'end_at' => $enrollmentEnd,
            ]);
        }
    }

    /**
     * Get the course data.
     */
    private function getCoursesData(): array
    {
        return [
            [
                'title' => 'PHP cơ bản cho người mới bắt đầu',
                'description' => 'Khóa học này cung cấp kiến thức nền tảng về ngôn ngữ lập trình PHP, giúp bạn xây dựng các trang web động đầu tiên.',
                'tags' => ['Programming', 'Web Development', 'PHP', 'MySQL'],
            ],
            [
                'title' => 'Laravel Framework từ A-Z',
                'description' => 'Trở thành chuyên gia Laravel qua khóa học toàn diện này. Bạn sẽ học cách xây dựng một ứng dụng web hoàn chỉnh từ đầu.',
                'tags' => ['Web Development', 'Laravel', 'PHP', 'Framework'],
            ],
            [
                'title' => 'JavaScript nâng cao và ES6+',
                'description' => 'Đi sâu vào các khái niệm nâng cao của JavaScript như closures, prototypes, async/await, và các tính năng mới trong ES6+.',
                'tags' => ['Programming', 'Web Development', 'JavaScript', 'ES6'],
            ],
            [
                'title' => 'Xây dựng giao diện với ReactJS và Redux',
                'description' => 'Học cách xây dựng giao diện người dùng hiện đại và có khả năng mở rộng với ReactJS, bao gồm hooks và Redux.',
                'tags' => ['Web Development', 'JavaScript', 'ReactJS', 'UI/UX Design'],
            ],
            [
                'title' => 'Lập trình Backend với Node.js và Express',
                'description' => 'Xây dựng các API RESTful mạnh mẽ và nhanh chóng bằng Node.js và framework Express.',
                'tags' => ['Programming', 'Web Development', 'Node.js', 'API'],
            ],
            [
                'title' => 'Quản trị Cơ sở dữ liệu MySQL',
                'description' => 'Khóa học này dạy bạn cách thiết kế, triển khai và quản trị cơ sở dữ liệu MySQL cho các ứng dụng lớn.',
                'tags' => ['Database', 'MySQL', 'SQL', 'System Administration'],
            ],
            [
                'title' => 'Nhập môn Khoa học Dữ liệu với Python',
                'description' => 'Bắt đầu hành trình vào thế giới khoa học dữ liệu. Bạn sẽ học cách sử dụng Pandas, NumPy, và Matplotlib.',
                'tags' => ['Data Science', 'Python', 'Pandas', 'NumPy'],
            ],
            [
                'title' => 'Học máy cho người bắt đầu',
                'description' => 'Tìm hiểu các khái niệm cơ bản của học máy và xây dựng các mô hình dự đoán đầu tiên bằng Scikit-learn.',
                'tags' => ['Machine Learning', 'AI', 'Python', 'Scikit-learn'],
            ],
            [
                'title' => 'Thiết kế UI/UX cho ứng dụng Web',
                'description' => 'Học các nguyên tắc cơ bản của thiết kế giao diện và trải nghiệm người dùng với công cụ Figma.',
                'tags' => ['UI/UX Design', 'Design', 'Figma'],
            ],
            [
                'title' => 'DevOps Essentials: CI/CD với Docker và Jenkins',
                'description' => 'Tìm hiểu về văn hóa DevOps và cách tự động hóa quy trình phát triển và triển khai phần mềm.',
                'tags' => ['DevOps', 'Cloud Computing', 'Docker', 'CI/CD'],
            ],
        ];
    }
}
