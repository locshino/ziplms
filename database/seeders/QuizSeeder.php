<?php

namespace Database\Seeders;

use App\Enums\Status\QuestionStatus;
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
use Illuminate\Support\Arr;

class QuizSeeder extends Seeder
{
    use HasCacheSeeder;

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
        // Skip if quizzes already exist and cache is valid
        if ($this->shouldSkipSeeding('quizzes', 'quizzes')) {
            return;
        }

        // Get or create quizzes with caching
        $this->getCachedData('quizzes', function () {
            $courses = Course::with('tags')->get();
            $teachers = User::role(RoleSystem::TEACHER->value)->get();

            foreach ($courses as $course) {
                $enrolledStudents = $course->students;
                $courseTags = $course->tags->pluck('name')->toArray();

                // Find relevant quizzes based on course tags
                $relevantQuizKeys = collect($this->quizzesData)
                    ->filter(fn($quiz) => ! empty(array_intersect($courseTags, $quiz['tags'])))
                    ->keys()
                    ->toArray();

                // Create 2-3 quizzes for each course
                $selectedQuizKeys = Arr::random($relevantQuizKeys, min(count($relevantQuizKeys), rand(2, 3)));

                foreach ($selectedQuizKeys as $key) {
                    $quizData = $this->quizzesData[$key];
                    $quiz = Quiz::factory()->create([
                        'title' => $quizData['title'],
                        'description' => $quizData['description'],
                        'status' => QuizStatus::PUBLISHED->value,
                    ]);

                    // Attach quiz to the course with random start/end dates
                    $startAt = fake()->dateTimeBetween('-1 month', '+1 week');
                    $endAt = fake()->dateTimeInInterval($startAt->format('Y-m-d H:i:s'), '+2 months');
                    $course->quizzes()->attach($quiz->id, [
                        'start_at' => $startAt,
                        'end_at' => $endAt,
                    ]);

                    // Create questions and answers for this quiz
                    $this->createQuestionsAndAnswers($quiz, $quizData['tags']);

                    // Create quiz attempts
                    if ($enrolledStudents->isNotEmpty()) {
                        $this->createQuizAttempts($quiz, $enrolledStudents);
                    }
                }
            }

            return true;
        });
    }

    /**
     * Create questions and answer choices for a quiz.
     */
    private function createQuestionsAndAnswers(Quiz $quiz, array $tags): void
    {
        $relevantQuestions = collect($this->questionsData)
            ->filter(fn($question) => ! empty(array_intersect($tags, $question['tags'])))
            ->toArray();

        $selectedQuestions = Arr::random($relevantQuestions, min(count($relevantQuestions), rand(5, 10)));

        foreach ($selectedQuestions as $questionData) {
            $question = Question::factory()->create([
                'title' => $questionData['title'],
                'status' => QuestionStatus::PUBLISHED->value,
            ]);

            // Attach question to quiz
            $quiz->questions()->attach($question->id, [
                'points' => fake()->randomElement([5, 10, 15]),
            ]);

            // Create answer choices
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
    private function createQuizAttempts(Quiz $quiz, $enrolledStudents): void
    {
        $attemptingStudents = $enrolledStudents->random(min(15, $enrolledStudents->count()));

        foreach ($attemptingStudents as $student) {
            $isCompleted = fake()->boolean(80); // 80% of attempts are completed
            $startAt = fake()->dateTimeBetween('-1 month', 'now');
            $endAt = $isCompleted ? fake()->dateTimeInInterval($startAt->format('Y-m-d H:i:s'), '+1 month') : null;

            $attempt = QuizAttempt::factory()->create([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'start_at' => $startAt,
                'end_at' => $endAt,
            ]);

            if ($isCompleted) {
                // Simulate grading for 50% of completed attempts
                if (fake()->boolean(50)) {
                    $attempt->update([
                        'points' => fake()->randomFloat(2, 0, $quiz->max_points),
                    ]);
                }
            }
        }
    }

    /**
     * Initialize realistic quiz and question data.
     */
    private function initializeData(): void
    {
        $this->quizzesData = [
            'php_basics' => [
                'title' => 'Kiểm tra kiến thức PHP cơ bản',
                'description' => 'Bài kiểm tra này bao gồm các câu hỏi về biến, kiểu dữ liệu, toán tử và các cấu trúc điều khiển cơ bản trong PHP.',
                'tags' => ['PHP', 'Programming'],
            ],
            'laravel_routing' => [
                'title' => 'Routing và Controller trong Laravel',
                'description' => 'Đánh giá hiểu biết của bạn về hệ thống routing và cách sử dụng controller để xử lý request trong Laravel.',
                'tags' => ['Laravel', 'PHP', 'Web Development'],
            ],
            'js_es6' => [
                'title' => 'Các tính năng mới trong ES6',
                'description' => 'Kiểm tra kiến thức về các tính năng quan trọng của ES6 như arrow functions, let/const, template literals, và destructuring.',
                'tags' => ['JavaScript', 'ES6', 'Web Development'],
            ],
            'react_components' => [
                'title' => 'Components, Props và State trong React',
                'description' => 'Bài kiểm tra tập trung vào các khái niệm cốt lõi của React: components, cách truyền dữ liệu qua props và quản lý trạng thái (state).',
                'tags' => ['ReactJS', 'JavaScript', 'UI/UX Design'],
            ],
            'sql_joins' => [
                'title' => 'Thực hành các loại JOIN trong SQL',
                'description' => 'Bài tập về các loại JOIN khác nhau (INNER, LEFT, RIGHT, FULL) để kết hợp dữ liệu từ nhiều bảng.',
                'tags' => ['SQL', 'Database', 'MySQL'],
            ],
            'python_data_types' => [
                'title' => 'Các kiểu dữ liệu trong Python',
                'description' => 'Kiểm tra hiểu biết về các kiểu dữ liệu cơ bản và cấu trúc dữ liệu trong Python như list, tuple, dictionary, set.',
                'tags' => ['Python', 'Data Science', 'Programming'],
            ],
            'ml_concepts' => [
                'title' => 'Các khái niệm cơ bản về Học máy',
                'description' => 'Đánh giá kiến thức tổng quan về các khái niệm trong học máy như học có giám sát, không giám sát, và các thuật toán phổ biến.',
                'tags' => ['Machine Learning', 'AI'],
            ],
            'docker_basics' => [
                'title' => 'Nhập môn Docker',
                'description' => 'Kiểm tra các khái niệm cơ bản về Docker, bao gồm images, containers, và Dockerfile.',
                'tags' => ['DevOps', 'Docker', 'Cloud Computing'],
            ],
        ];

        $this->questionsData = [
            // PHP Questions
            [
                'title' => 'Đâu là cách khai báo một biến hợp lệ trong PHP?',
                'tags' => ['PHP'],
                'answers' => [
                    ['title' => '$my-var', 'is_correct' => false],
                    ['title' => '$my_var', 'is_correct' => true],
                    ['title' => 'let my_var', 'is_correct' => false],
                    ['title' => 'const $my_var', 'is_correct' => false],
                ],
            ],
            [
                'title' => 'Hàm nào dùng để in một chuỗi ra màn hình trong PHP?',
                'tags' => ['PHP'],
                'answers' => [
                    ['title' => 'console.log()', 'is_correct' => false],
                    ['title' => 'print()', 'is_correct' => false],
                    ['title' => 'echo', 'is_correct' => true],
                    ['title' => 'document.write()', 'is_correct' => false],
                ],
            ],
            // Laravel Questions
            [
                'title' => 'Lệnh artisan nào dùng để tạo một controller mới?',
                'tags' => ['Laravel'],
                'answers' => [
                    ['title' => 'php artisan make:controller MyController', 'is_correct' => true],
                    ['title' => 'php artisan create:controller MyController', 'is_correct' => false],
                    ['title' => 'php artisan controller:make MyController', 'is_correct' => false],
                    ['title' => 'php artisan new:controller MyController', 'is_correct' => false],
                ],
            ],
            // JavaScript Questions
            [
                'title' => '`let` và `const` trong JavaScript ES6 khác nhau ở điểm nào?',
                'tags' => ['JavaScript', 'ES6'],
                'answers' => [
                    ['title' => '`let` có thể được gán lại giá trị, `const` thì không.', 'is_correct' => true],
                    ['title' => '`const` có thể được gán lại giá trị, `let` thì không.', 'is_correct' => false],
                    ['title' => 'Cả hai đều không thể gán lại giá trị.', 'is_correct' => false],
                    ['title' => 'Không có sự khác biệt nào.', 'is_correct' => false],
                ],
            ],
            // SQL Questions
            [
                'title' => 'Loại JOIN nào sẽ trả về tất cả các bản ghi từ bảng bên trái và các bản ghi khớp từ bảng bên phải?',
                'tags' => ['SQL', 'Database'],
                'answers' => [
                    ['title' => 'INNER JOIN', 'is_correct' => false],
                    ['title' => 'RIGHT JOIN', 'is_correct' => false],
                    ['title' => 'LEFT JOIN', 'is_correct' => true],
                    ['title' => 'FULL OUTER JOIN', 'is_correct' => false],
                ],
            ],
        ];
    }
}
