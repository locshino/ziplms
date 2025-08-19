<?php

namespace Database\Seeders;

use App\Enums\Status\AssignmentStatus;
use App\Enums\Status\SubmissionStatus;
use App\Enums\System\RoleSystem;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use App\Models\User;
use Database\Seeders\Contracts\HasCacheSeeder;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    use HasCacheSeeder;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if assignments already exist and cache is valid
        if ($this->shouldSkipSeeding('assignments', 'assignments')) {
            return;
        }

        // Get or create assignments with caching
        $this->getCachedData('assignments', function () {
            // Get all courses
            $courses = Course::all();
            $teachers = User::role(RoleSystem::TEACHER->value)->get();

            foreach ($courses as $course) {
                // Get students enrolled in this course
                $enrolledStudents = $course->users->filter(function ($user) {
                    return $user->hasRole(RoleSystem::STUDENT->value);
                });
                ;

                // Create 10 assignments for each child course
                for ($i = 1; $i <= 10; $i++) {
                    $assignment = Assignment::factory()->create([
                        'status' => AssignmentStatus::PUBLISHED->value,
                    ]);

                    // Create pivot record in course_assignments
                    $startAt = fake()->dateTimeBetween('-2 months', '-1 month');
                    $endSubmissionAt = fake()->dateTimeBetween($startAt, 'now');

                    $course->assignments()->attach($assignment->id, [
                        'start_at' => $startAt,
                        'end_submission_at' => $endSubmissionAt,
                    ]);

                    // Create submissions for this assignment
                    $this->createSubmissions($assignment, $enrolledStudents, $startAt, $endSubmissionAt, $teachers);
                }
            }

            return true;
        });
    }

    /**
     * Create submissions for an assignment.
     */
    private function createSubmissions(
        Assignment $assignment,
        $enrolledStudents,
        $startAt,
        $endSubmissionAt,
        $teachers
    ): void {
        // Randomly select 30 students from enrolled students
        $selectedStudents = $enrolledStudents->random(min(30, $enrolledStudents->count()));

        // Split students into groups
        $onTimeStudents = $selectedStudents->take(15);
        $lateStudents = $selectedStudents->skip(15)->take(5);
        // Remaining 10 students don't submit (no submission records)

        // Create submissions for on-time students (15 students)
        foreach ($onTimeStudents as $student) {
            $submittedAt = fake()->dateTimeBetween(
                $startAt,
                $endSubmissionAt
            );

            $submission = Submission::factory()->create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'status' => SubmissionStatus::SUBMITTED->value,
                'submitted_at' => $submittedAt,
            ]);

            // 70% chance of being graded
            if (fake()->boolean(70)) {
                $gradedAt = fake()->dateTimeBetween($submittedAt, 'now');
                $submission->update([
                    'status' => SubmissionStatus::GRADED->value,
                    'points' => fake()->randomFloat(2, 0, $assignment->max_points),
                    'graded_by' => $teachers->random()->id,
                    'graded_at' => $gradedAt,
                    'feedback' => fake()->paragraph(2),
                ]);
            }
        }

        // Create submissions for late students (5 students)
        foreach ($lateStudents as $student) {
            $submittedAt = fake()->dateTimeBetween(
                $endSubmissionAt,
                now()
            );

            $submission = Submission::factory()->create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'status' => SubmissionStatus::LATE->value,
                'submitted_at' => $submittedAt,
            ]);

            // 50% chance of being graded (lower than on-time submissions)
            if (fake()->boolean(50)) {
                $gradedAt = fake()->dateTimeBetween($submittedAt, 'now');
                $submission->update([
                    'status' => SubmissionStatus::GRADED->value,
                    'points' => fake()->randomFloat(2, 0, $assignment->max_points * 0.8), // Penalty for late submission
                    'graded_by' => $teachers->random()->id,
                    'graded_at' => $gradedAt,
                    'feedback' => fake()->paragraph(2).' (Late submission penalty applied)',
                ]);
            }
        }
    }
}
