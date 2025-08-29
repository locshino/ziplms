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

            $tagNames = [
                'Programming', 'Web Development', 'Mobile Development', 'Data Science', 'Machine Learning',
                'Artificial Intelligence', 'Database', 'DevOps', 'Cloud Computing', 'Cybersecurity',
                'UI/UX Design', 'Project Management', 'Business Analysis', 'Digital Marketing', 'E-commerce',
                'Blockchain', 'IoT', 'Software Testing', 'System Administration',
            ];

            for ($i = 1; $i <= 10; $i++) {
                $parentCourse = Course::factory()->create([
                    'teacher_id' => $teachers->random()->id,
                    'status' => CourseStatus::PUBLISHED->value,
                    'tags' => collect($tagNames)->random(rand(3, 5))->values()->all(),
                ]);

                // Assign 2 managers to each parent course
                $courseManagers = $managers->random(2);
                foreach ($courseManagers as $manager) {
                    $parentCourse->users()->attach($manager->id);
                }

                // Gán học sinh vào khóa học
                $courseStudents = $students->random(30);
                foreach ($courseStudents as $student) {
                    $parentCourse->users()->attach($student->id);
                }
            }

            return true;
        });
    }
}
