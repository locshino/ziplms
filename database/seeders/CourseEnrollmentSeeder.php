<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Models\CourseEnrollment;
use Illuminate\Database\Seeder;

class CourseEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id');
        $courseIds = Course::pluck('id');

        if ($userIds->isEmpty() || $courseIds->isEmpty()) {
            $this->command->info('Skipping CourseEnrollmentSeeder: No users or courses found.');
            return;
        }

        $possibleEnrollments = new Collection();
        foreach ($userIds as $userId) {
            foreach ($courseIds as $courseId) {
                $possibleEnrollments->push(['user_id' => $userId, 'course_id' => $courseId]);
            }
        }

        // Xáo trộn và lấy một số lượng mẫu, ví dụ 30 hoặc ít hơn nếu không đủ cặp duy nhất
        $enrollmentsToCreate = $possibleEnrollments->shuffle()->take(30);

        if ($enrollmentsToCreate->isEmpty()) {
            $this->command->info('No unique course enrollments to create.');
            return;
        }

        $enrollmentsToCreate->each(function ($enrollmentData) {
            CourseEnrollment::factory()->create([
                'user_id' => $enrollmentData['user_id'],
                'course_id' => $enrollmentData['course_id'],
                // Các trường khác sẽ được factory tự động điền
            ]);
        });

        $this->command->info('Created ' . $enrollmentsToCreate->count() . ' unique course enrollments.');
    }
}
