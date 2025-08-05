<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();
        
        if ($courses->isEmpty()) {
            $this->command->warn('No courses found. Please run CourseSeeder first.');
            return;
        }

        $assignmentTemplates = [
            [
                'title' => 'Project Setup and Planning',
                'instructions' => 'Set up your development environment and create a project plan with milestones and deliverables.',
                'max_points' => 50.00,
            ],
            [
                'title' => 'Code Implementation',
                'instructions' => 'Implement the core functionality according to the specifications provided in class.',
                'max_points' => 100.00,
            ],
            [
                'title' => 'Testing and Documentation',
                'instructions' => 'Write comprehensive tests and documentation for your implementation.',
                'max_points' => 75.00,
            ],
            [
                'title' => 'Final Presentation',
                'instructions' => 'Prepare and deliver a presentation showcasing your project and lessons learned.',
                'max_points' => 80.00,
            ],
        ];

        // Create 2-4 assignments for each course
        foreach ($courses as $course) {
            $numAssignments = rand(2, 4);
            $selectedTemplates = collect($assignmentTemplates)->random($numAssignments);
            
            foreach ($selectedTemplates as $index => $template) {
                $startAt = now()->addDays(7 * ($index + 1));
                $dueAt = $startAt->copy()->addDays(14);
                $gradingAt = $dueAt->copy()->addDays(7);
                $endAt = $gradingAt->copy()->addDays(7);
                
                Assignment::create(array_merge($template, [
                    'course_id' => $course->id,
                    'late_penalty_percentage' => rand(0, 1) ? rand(5, 25) : null,
                    'start_at' => $startAt,
                    'due_at' => $dueAt,
                    'grading_at' => $gradingAt,
                    'end_at' => $endAt,
                ]));
            }
        }

        // Create additional random assignments
        Assignment::factory(20)->create();
    }
}