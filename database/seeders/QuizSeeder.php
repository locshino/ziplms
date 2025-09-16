<?php

namespace Database\Seeders;

use App\Enums\Status\QuestionStatus;
use App\Enums\Status\QuizStatus;
use App\Models\AnswerChoice;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class QuizSeeder extends Seeder
{
    private array $quizzesData = [];
    private array $questionsData = [];

    public function __construct()
    {
        $this->initializeData();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::with(['tags', 'students'])->where('end_at', '>', now()->subMonth())->get();

        foreach ($courses as $course) {
            $this->createQuizzesForCourse($course);
        }
    }

    /**
     * Create quizzes for a specific course.
     */
    private function createQuizzesForCourse(Course $course): void
    {
        $courseTags = $course->tags->pluck('name')->toArray();
        $relevantQuizKeys = $this->getRelevantDataKeys($this->quizzesData, $courseTags);

        if (empty($relevantQuizKeys)) {
            $relevantQuizKeys = array_keys($this->quizzesData); // Fallback
        }
        $selectedQuizKeys = (count($relevantQuizKeys) > 1) ? Arr::random($relevantQuizKeys, rand(1, min(count($relevantQuizKeys), 2))) : $relevantQuizKeys;

        foreach ($selectedQuizKeys as $key) {
            $quizData = $this->quizzesData[$key];
            $quiz = Quiz::factory()->create([
                'title' => $quizData['title'],
                'description' => $quizData['description'],
                'status' => QuizStatus::PUBLISHED->value,
            ]);

            // Set quiz dates within the course duration
            $startAt = fake()->dateTimeBetween($course->start_at, $course->end_at);
            $endAt = fake()->dateTimeBetween($startAt, $course->end_at);

            $course->quizzes()->attach($quiz->id, ['start_at' => $startAt, 'end_at' => $endAt]);

            $this->createQuestionsAndAnswers($quiz, $quizData['tags']);

            if ($course->students->isNotEmpty() && $startAt < now()) {
                $this->createQuizAttempts($quiz, $course->students, $startAt, $endAt);
            }
        }
    }

    /**
     * Create questions and answer choices for a quiz.
     */
    private function createQuestionsAndAnswers(Quiz $quiz, array $tags): void
    {
        $relevantQuestions = $this->getRelevantDataKeys($this->questionsData, $tags, true);

        if (empty($relevantQuestions)) {
            $relevantQuestions = $this->questionsData; // Fallback
        }
        $selectedQuestions = Arr::random($relevantQuestions, min(count($relevantQuestions), rand(8, 15)));

        foreach ($selectedQuestions as $questionData) {
            $question = Question::factory()->create([
                'title' => $questionData['title'],
                'status' => QuestionStatus::PUBLISHED->value,
            ]);

            $quiz->questions()->attach($question->id, ['points' => fake()->randomElement([5, 10, 15])]);

            foreach ($questionData['answers'] as $answer) {
                AnswerChoice::factory()->create([
                    'question_id' => $question->id,
                    'title' => $answer['title'],
                    'is_correct' => $answer['is_correct'],
                ]);
            }
        }
    }

    /**
     * Create quiz attempts for a quiz.
     */
    private function createQuizAttempts(Quiz $quiz, $students, $startAt, $endAt): void
    {
        $attemptingStudents = $students->random(min(15, $students->count()));

        foreach ($attemptingStudents as $student) {
            if (! fake()->boolean(90)) { // 90% attempt rate
                continue;
            }

            $isCompleted = fake()->boolean(80);
            $attemptStart = fake()->dateTimeBetween($startAt, min(now(), $endAt));
            $attemptEnd = $isCompleted ? fake()->dateTimeInInterval($attemptStart, '+1 hour') : null;

            if ($attemptEnd > $endAt) {
                $attemptEnd = $endAt;
            }

            $attempt = QuizAttempt::factory()->create([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'start_at' => $attemptStart,
                'end_at' => $attemptEnd,
            ]);

            if ($isCompleted) {
                // Simulate grading for 50% of completed attempts
                if (fake()->boolean(50)) {
                    $attempt->update(['points' => fake()->randomFloat(2, 0, $quiz->questions->sum('pivot.points'))]);
                }
            }
        }
    }

    /**
     * Filter data keys based on tags.
     */
    private function getRelevantDataKeys(array $data, array $tags, bool $returnFullData = false): array
    {
        $filtered = collect($data)->filter(fn ($item) => ! empty(array_intersect($tags, $item['tags'])));

        return $returnFullData ? $filtered->values()->toArray() : $filtered->keys()->toArray();
    }

    /**
     * Initialize realistic quiz and question data.
     */
    private function initializeData(): void
    {
        $this->quizzesData = [
            'php_basics' => ['title' => 'Kiểm tra kiến thức PHP cơ bản', 'description' => 'Bao gồm các câu hỏi về biến, kiểu dữ liệu, toán tử và các cấu trúc điều khiển cơ bản trong PHP.', 'tags' => ['PHP', 'Programming']],
            'laravel_routing' => ['title' => 'Routing và Controller trong Laravel', 'description' => 'Đánh giá hiểu biết về hệ thống routing và cách sử dụng controller để xử lý request trong Laravel.', 'tags' => ['Laravel', 'PHP', 'Web Development']],
            'js_es6' => ['title' => 'Các tính năng mới trong ES6', 'description' => 'Kiểm tra kiến thức về các tính năng quan trọng của ES6 như arrow functions, let/const, và destructuring.', 'tags' => ['JavaScript', 'ES6', 'Web Development']],
            'react_components' => ['title' => 'Components, Props và State trong React', 'description' => 'Bài kiểm tra tập trung vào các khái niệm cốt lõi của React: components, props và state.', 'tags' => ['ReactJS', 'JavaScript', 'UI/UX Design']],
            'sql_joins' => ['title' => 'Thực hành các loại JOIN trong SQL', 'description' => 'Bài tập về các loại JOIN khác nhau (INNER, LEFT, RIGHT, FULL) để kết hợp dữ liệu từ nhiều bảng.', 'tags' => ['SQL', 'Database', 'MySQL']],
        ];

        $this->questionsData = [
            ['title' => 'Đâu là cách khai báo một biến hợp lệ trong PHP?', 'tags' => ['PHP'], 'answers' => [['title' => '$my-var', 'is_correct' => false], ['title' => '$my_var', 'is_correct' => true], ['title' => 'let my_var', 'is_correct' => false], ['title' => 'const $my_var', 'is_correct' => false]]],
            ['title' => 'Hàm nào dùng để in một chuỗi ra màn hình trong PHP?', 'tags' => ['PHP'], 'answers' => [['title' => 'console.log()', 'is_correct' => false], ['title' => 'print()', 'is_correct' => false], ['title' => 'echo', 'is_correct' => true], ['title' => 'document.write()', 'is_correct' => false]]],
            ['title' => 'Lệnh artisan nào dùng để tạo một controller mới?', 'tags' => ['Laravel'], 'answers' => [['title' => 'php artisan make:controller MyController', 'is_correct' => true], ['title' => 'php artisan create:controller MyController', 'is_correct' => false], ['title' => 'php artisan controller:make MyController', 'is_correct' => false], ['title' => 'php artisan new:controller MyController', 'is_correct' => false]]],
            ['title' => '`let` và `const` trong JavaScript ES6 khác nhau ở điểm nào?', 'tags' => ['JavaScript', 'ES6'], 'answers' => [['title' => '`let` có thể được gán lại giá trị, `const` thì không.', 'is_correct' => true], ['title' => '`const` có thể được gán lại giá trị, `let` thì không.', 'is_correct' => false], ['title' => 'Cả hai đều không thể gán lại giá trị.', 'is_correct' => false], ['title' => 'Không có sự khác biệt nào.', 'is_correct' => false]]],
            ['title' => 'Loại JOIN nào sẽ trả về tất cả các bản ghi từ bảng bên trái và các bản ghi khớp từ bảng bên phải?', 'tags' => ['SQL', 'Database'], 'answers' => [['title' => 'INNER JOIN', 'is_correct' => false], ['title' => 'RIGHT JOIN', 'is_correct' => false], ['title' => 'LEFT JOIN', 'is_correct' => true], ['title' => 'FULL OUTER JOIN', 'is_correct' => false]]],
        ];
    }
}
