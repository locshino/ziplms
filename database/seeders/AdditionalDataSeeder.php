<?php

namespace Database\Seeders;

use App\Enums\Status\SubmissionStatus;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\StudentQuizAnswer;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdditionalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This seeder should run after all main data has been created.
        $this->command->info('Running additional data seeder...');

        $this->seedDefaultUserExperience();
        $this->createStudentQuizAnswers();

        $this->command->info('Additional data seeding completed.');
    }

    /**
     * Create a realistic testing scenario for default user accounts.
     * This method enrolls default users into an active course and creates
     * submissions/attempts for them, making it easy to test role-specific workflows.
     */
    private function seedDefaultUserExperience(): void
    {
        $this->command->info('Seeding default user experience...');

        $student = User::where('email', 'student@example.com')->first();
        $teacher = User::where('email', 'teacher@example.com')->first();
        $manager = User::where('email', 'manager@example.com')->first();

        if (! $student || ! $teacher || ! $manager) {
            $this->command->warn('Default users not found. Skipping user experience seeding.');

            return;
        }

        // Find an ongoing course to use as a testing ground
        $testCourse = Course::where('start_at', '<', now())
            ->where('end_at', '>', now())
            ->has('assignments')
            ->has('quizzes')
            ->first();

        if (! $testCourse) {
            $this->command->warn('No suitable ongoing course found for default users.');

            return;
        }

        // 1. Assign the default teacher to the course
        $testCourse->update(['teacher_id' => $teacher->id]);

        // 2. Enroll the default student and manager
        $enrollmentData = [
            'start_at' => $testCourse->start_at,
            'end_at' => $testCourse->end_at,
        ];
        $testCourse->users()->syncWithoutDetaching([
            $student->id => $enrollmentData,
            $manager->id => $enrollmentData,
        ]);

        // 3. Create a submission for the default student that needs grading
        $assignment = $testCourse->assignments()->wherePivot('start_at', '<', now())->first();
        if ($assignment) {
            Submission::factory()->create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'status' => SubmissionStatus::SUBMITTED->value,
                'submitted_at' => now()->subDay(),
                'content' => 'Đây là bài nộp của học sinh mặc định để giáo viên kiểm tra.',
            ]);
        }

        // 4. Create a completed quiz attempt for the default student
        $quiz = $testCourse->quizzes()->wherePivot('start_at', '<', now())->first();
        if ($quiz) {
            QuizAttempt::factory()->create([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'start_at' => now()->subHours(2),
                'end_at' => now()->subHours(1), // Completed
                'points' => null, // Not yet graded
            ]);
        }

        $this->command->info("Default users have been assigned to course '{$testCourse->title}'.");
    }

    /**
     * Create student quiz answers for completed quiz attempts.
     * This populates the details of what a student answered in a quiz.
     */
    private function createStudentQuizAnswers(): void
    {
        $this->command->info('Creating student answers for quiz attempts...');

        // Get completed attempts that don't have answers yet
        $attempts = QuizAttempt::whereNotNull('end_at')
            ->whereDoesntHave('studentAnswers')
            ->with('quiz.questions.answerChoices')
            ->get();

        foreach ($attempts as $attempt) {
            foreach ($attempt->quiz->questions as $question) {
                // 75% chance of answering correctly
                $isCorrect = fake()->boolean(75);
                $choices = $question->answerChoices;

                if ($choices->isEmpty()) {
                    continue;
                }

                $chosenAnswer = $choices->where('is_correct', $isCorrect)->first()
                    ?? $choices->where('is_correct', ! $isCorrect)->first();

                if ($chosenAnswer) {
                    // Create each answer individually to allow the model to generate UUIDs
                    StudentQuizAnswer::create([
                        'quiz_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'answer_choice_id' => $chosenAnswer->id,
                    ]);
                }
            }
        }
    }
}
