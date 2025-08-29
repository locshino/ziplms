<?php

namespace Database\Seeders;

use App\Enums\Status\CourseStatus;
use App\Enums\System\RoleSystem;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\Contracts\HasCacheSeeder;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    use HasCacheSeeder;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if courses already exist and cache is valid
        if ($this->shouldSkipSeeding('courses', 'courses')) {
            return;
        }

        // Get or create courses with caching
        $this->getCachedData('courses', function () {
            // Get teachers, managers, students
            $teachers = User::role(RoleSystem::TEACHER->value)->get();
            $managers = User::role(RoleSystem::MANAGER->value)->get();
            $students = User::role(RoleSystem::STUDENT->value)->get();

            $coursesData = [
                [
                    'title' => 'PHP cơ bản cho người mới bắt đầu',
                    'description' => 'Khóa học này cung cấp kiến thức nền tảng về ngôn ngữ lập trình PHP, giúp bạn xây dựng các trang web động đầu tiên. Chúng ta sẽ tìm hiểu về biến, kiểu dữ liệu, câu lệnh điều khiển, hàm, và cách tương tác với cơ sở dữ liệu MySQL.',
                    'tags' => ['Programming', 'Web Development', 'PHP', 'MySQL'],
                ],
                [
                    'title' => 'Laravel Framework từ A-Z',
                    'description' => 'Trở thành chuyên gia Laravel qua khóa học toàn diện này. Bạn sẽ học cách xây dựng một ứng dụng web hoàn chỉnh từ đầu, bao gồm routing, Eloquent ORM, Blade templating, authentication, và triển khai ứng dụng.',
                    'tags' => ['Web Development', 'Laravel', 'PHP', 'Framework'],
                ],
                [
                    'title' => 'JavaScript nâng cao và ES6+',
                    'description' => 'Đi sâu vào các khái niệm nâng cao của JavaScript như closures, prototypes, async/await, và các tính năng mới trong ES6+. Khóa học dành cho những ai muốn làm chủ JavaScript và viết code hiệu quả hơn.',
                    'tags' => ['Programming', 'Web Development', 'JavaScript', 'ES6'],
                ],
                [
                    'title' => 'Xây dựng giao diện với ReactJS và Redux',
                    'description' => 'Học cách xây dựng giao diện người dùng hiện đại và có khả năng mở rộng với ReactJS. Khóa học bao gồm component, state, props, hooks, và quản lý state phức tạp với Redux.',
                    'tags' => ['Web Development', 'JavaScript', 'ReactJS', 'UI/UX Design'],
                ],
                [
                    'title' => 'Lập trình Backend với Node.js và Express',
                    'description' => 'Xây dựng các API RESTful mạnh mẽ và nhanh chóng bằng Node.js và framework Express. Bạn sẽ học cách xử lý request, middleware, kết nối database, và xác thực người dùng.',
                    'tags' => ['Programming', 'Web Development', 'Node.js', 'API'],
                ],
                [
                    'title' => 'Quản trị Cơ sở dữ liệu MySQL',
                    'description' => 'Khóa học này dạy bạn cách thiết kế, triển khai và quản trị cơ sở dữ liệu MySQL. Nội dung bao gồm thiết kế lược đồ, viết truy vấn SQL phức tạp, tối ưu hóa hiệu năng và bảo mật.',
                    'tags' => ['Database', 'MySQL', 'SQL', 'System Administration'],
                ],
                [
                    'title' => 'Nhập môn Khoa học Dữ liệu với Python',
                    'description' => 'Bắt đầu hành trình vào thế giới khoa học dữ liệu. Bạn sẽ học cách sử dụng các thư viện Python phổ biến như Pandas, NumPy, và Matplotlib để phân tích, xử lý và trực quan hóa dữ liệu.',
                    'tags' => ['Data Science', 'Python', 'Pandas', 'NumPy'],
                ],
                [
                    'title' => 'Học máy cho người bắt đầu',
                    'description' => 'Tìm hiểu các khái niệm cơ bản của học máy và xây dựng các mô hình dự đoán đầu tiên của bạn. Khóa học bao gồm hồi quy, phân loại, và gom cụm bằng thư viện Scikit-learn.',
                    'tags' => ['Machine Learning', 'Artificial Intelligence', 'Python', 'Scikit-learn'],
                ],
                [
                    'title' => 'Thiết kế UI/UX cho ứng dụng Web',
                    'description' => 'Học các nguyên tắc cơ bản của thiết kế giao diện và trải nghiệm người dùng. Bạn sẽ thực hành với các công cụ như Figma để tạo ra các wireframe, mockup, và prototype cho dự án web.',
                    'tags' => ['UI/UX Design', 'Design', 'Figma'],
                ],
                [
                    'title' => 'DevOps Essentials: CI/CD với Docker và Jenkins',
                    'description' => 'Tìm hiểu về văn hóa DevOps và cách tự động hóa quy trình phát triển và triển khai phần mềm. Khóa học tập trung vào việc sử dụng Docker để container hóa ứng dụng và Jenkins để xây dựng pipeline CI/CD.',
                    'tags' => ['DevOps', 'Cloud Computing', 'Docker', 'CI/CD'],
                ],
            ];

            foreach ($coursesData as $courseData) {
                $course = Course::factory()->create([
                    'title' => $courseData['title'],
                    'description' => $courseData['description'],
                    'teacher_id' => $teachers->random()->id,
                    'status' => CourseStatus::PUBLISHED->value,
                ]);

                // Attach tags
                $course->syncTags($courseData['tags']);

                // Assign 2 managers to each course
                $courseManagers = $managers->random(min(2, $managers->count()));
                foreach ($courseManagers as $manager) {
                    $course->users()->attach($manager->id);
                }

                // Assign students to the course
                $courseStudents = $students->random(min(30, $students->count()));
                foreach ($courseStudents as $student) {
                    $course->users()->attach($student->id);
                }
            }

            return true;
        });
    }
}
