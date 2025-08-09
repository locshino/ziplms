<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::role('student')->get();
        $assignments = Assignment::all();

        if ($students->isEmpty()) {
            $this->command->warn('No students found. Please run UserSeeder first.');

            return;
        }

        if ($assignments->isEmpty()) {
            $this->command->warn('No assignments found. Please run AssignmentSeeder first.');

            return;
        }

        // Create submissions for some assignments
        foreach ($students as $student) {
            // Get assignments from courses the student is enrolled in
            $enrolledCourseIds = $student->enrollments()->pluck('course_id');
            $availableAssignments = $assignments->whereIn('course_id', $enrolledCourseIds);

            if ($availableAssignments->isEmpty()) {
                continue;
            }

            // Submit 30-70% of available assignments
            $submissionCount = rand(
                (int) ($availableAssignments->count() * 0.3),
                (int) ($availableAssignments->count() * 0.7)
            );

            $assignmentsToSubmit = $availableAssignments->random(min($submissionCount, $availableAssignments->count()));

            foreach ($assignmentsToSubmit as $assignment) {
                // Check if submission already exists
                if (Submission::where('assignment_id', $assignment->id)
                    ->where('student_id', $student->id)
                    ->exists()) {
                    continue;
                }

                // Ensure we have valid date range
                $startDate = max($assignment->start_at, now()->subMonths(3));
                $endDate = min($assignment->due_at, now());

                // Skip if dates are invalid
                if ($startDate >= $endDate) {
                    continue;
                }

                $submittedAt = fake()->dateTimeBetween($startDate, $endDate);
                $isGraded = fake()->boolean(60); // 60% chance of being graded

                $submissionData = [
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                    'submitted_at' => $submittedAt,
                    'version' => fake()->numberBetween(1, 3),
                ];

                if ($isGraded) {
                    $teachers = User::role('teacher')->get();
                    if ($teachers->isNotEmpty()) {
                        $submissionData['grade'] = fake()->randomFloat(2, 60, 100);
                        $submissionData['feedback'] = fake()->optional(0.8)->paragraph();
                        $submissionData['graded_by'] = $teachers->random()->id;
                        $submissionData['graded_at'] = fake()->dateTimeBetween($submittedAt, now());
                    }
                }

                Submission::create($submissionData);
            }
        }

        // Create some additional random submissions using factory
        Submission::factory(30)->create();
    }
}
