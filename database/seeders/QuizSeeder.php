<?php

namespace Database\Seeders;

use App\Enums\Status\QuestionStatus;
use App\Enums\Status\QuizAttemptStatus;
use App\Enums\Status\QuizStatus;
use App\Enums\System\RoleSystem;
use App\Models\AnswerChoice;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Database\Seeders\Contracts\HasCacheSeeder;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    use HasCacheSeeder;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if quizzes already exist and cache is valid
        if ($this->shouldSkipSeeding('quizzes', 'quizzes')) {
            return;
        }

        // Get or create quizzes with caching
        $this->getCachedData('quizzes', function () {
            // Get child courses (courses with parent_id not null)
            $childCourses = Course::all();
            $teachers = User::role(RoleSystem::TEACHER->value)->get();

            foreach ($childCourses as $course) {
                // Get students enrolled in this course
                $enrolledStudents = $course->students;

                // Create 12 quizzes for each child course
                for ($i = 1; $i <= 12; $i++) {
                    $quiz = Quiz::factory()->create([
                        'status' => $i <= 10 ? QuizStatus::PUBLISHED->value : QuizStatus::DRAFT->value,
                    ]);

                    // Create pivot record in course_quizzes
                    $startAt = fake()->dateTimeBetween('-2 months', '+1 month');
                    $endAt = fake()->dateTimeBetween($startAt, '+2 months');

                    // Special case: 1 quiz should be closed (ended)
                    if ($i === 1) {
                        $startAt = fake()->dateTimeBetween('-3 months', '-2 months');
                        $endAt = fake()->dateTimeBetween($startAt, '-1 month');
                        $quiz->update(['status' => QuizStatus::CLOSED->value]);
                    }

                    $course->quizzes()->attach($quiz->id, [
                        'start_at' => $startAt,
                        'end_at' => $endAt,
                    ]);

                    // Create questions and answer choices for this quiz
                    $this->createQuestionsAndAnswers($quiz);

                    // Create quiz attempts only for published quizzes that are not closed
                    if ($quiz->status === QuizStatus::PUBLISHED->value && $i !== 1) {
                        $this->createQuizAttempts($quiz, $enrolledStudents, $teachers);
                    }
                }
            }

            return true;
        });
    }

    /**
     * Create questions and answer choices for a quiz.
     */
    private function createQuestionsAndAnswers(Quiz $quiz): void
    {
        $questionCount = fake()->numberBetween(5, 10);

        for ($i = 1; $i <= $questionCount; $i++) {
            // Create question without quiz_id since it's a many-to-many relationship
            $question = Question::factory()->create([
                'status' => QuestionStatus::PUBLISHED->value,
            ]);

            // Attach question to quiz with pivot data (points)
            $points = fake()->randomFloat(2, 1, 10);
            $quiz->questions()->attach($question->id, [
                'points' => $points,
            ]);

            // Create answer choices for each question
            $choiceCount = fake()->numberBetween(3, 4);
            $correctChoiceIndex = fake()->numberBetween(0, $choiceCount - 1);

            for ($j = 0; $j < $choiceCount; $j++) {
                AnswerChoice::factory()->create([
                    'question_id' => $question->id,
                    'is_correct' => $j === $correctChoiceIndex,
                ]);
            }
        }
    }

    /**
     * Create quiz attempts for a quiz.
     */
    private function createQuizAttempts(Quiz $quiz, $enrolledStudents, $teachers): void
    {
        // Select 15 random students to attempt this quiz
        $attemptingStudents = $enrolledStudents->random(min(15, $enrolledStudents->count()));

        foreach ($attemptingStudents as $index => $student) {
            $startAt = fake()->dateTimeBetween('-1 month', 'now');

            // Determine attempt status
            if ($index < 10) {
                // 10 attempts completed, waiting for grading
                $attempt = QuizAttempt::factory()->completed()->create([
                    'quiz_id' => $quiz->id,
                    'student_id' => $student->id,
                    'start_at' => $startAt,
                ]);
            } else {
                // 5 attempts graded
                $attempt = QuizAttempt::factory()->graded()->create([
                    'quiz_id' => $quiz->id,
                    'student_id' => $student->id,
                    'start_at' => $startAt,
                    'points' => fake()->randomFloat(2, 0, $quiz->max_points),
                ]);
            }
        }
    }
}
