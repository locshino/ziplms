<?php

namespace Database\Seeders;

use App\Models\AnswerChoice;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
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

        // Create 1-3 quizzes for each course
        foreach ($courses as $course) {
            $numQuizzes = rand(1, 3);
            
            for ($i = 1; $i <= $numQuizzes; $i++) {
                $quiz = Quiz::create([
                    'course_id' => $course->id,
                    'title' => "Quiz {$i} - {$course->title}",
                    'description' => fake()->paragraph(),
                    'max_points' => 100.00,
                    'max_attempts' => rand(0, 1) ? rand(1, 3) : null,
                    'is_single_session' => fake()->boolean(30),
                    'time_limit_minutes' => rand(0, 1) ? rand(30, 120) : null,
                    'start_at' => now()->addDays(rand(1, 30)),
                    'end_at' => now()->addDays(rand(31, 90)),
                ]);

                // Create 5-10 questions for each quiz
                $numQuestions = rand(5, 10);
                
                for ($j = 1; $j <= $numQuestions; $j++) {
                    $question = Question::create([
                        'quiz_id' => $quiz->id,
                        'title' => "Question {$j}: " . fake()->sentence() . '?',
                        'points' => fake()->randomFloat(2, 1, 10),
                        'is_multiple_response' => fake()->boolean(20),
                    ]);

                    // Create 4 answer choices for each question
                    $correctAnswerIndex = rand(0, 3);
                    
                    for ($k = 0; $k < 4; $k++) {
                        AnswerChoice::create([
                            'question_id' => $question->id,
                            'title' => fake()->sentence(4),
                            'is_correct' => $k === $correctAnswerIndex,
                        ]);
                    }
                    
                    // For multiple response questions, make 1-2 additional answers correct
                    if ($question->is_multiple_response && rand(0, 1)) {
                        $additionalCorrectIndex = ($correctAnswerIndex + rand(1, 3)) % 4;
                        AnswerChoice::where('question_id', $question->id)
                                   ->skip($additionalCorrectIndex)
                                   ->first()
                                   ->update(['is_correct' => true]);
                    }
                }
            }
        }

        // Create additional random quizzes with questions and answers
        Quiz::factory(10)->create()->each(function ($quiz) {
            Question::factory(rand(3, 8))->create([
                'quiz_id' => $quiz->id,
            ])->each(function ($question) {
                AnswerChoice::factory(4)->create([
                    'question_id' => $question->id,
                ])->each(function ($choice, $index) {
                    if ($index === 0) {
                        $choice->update(['is_correct' => true]);
                    }
                });
            });
        });
    }
}