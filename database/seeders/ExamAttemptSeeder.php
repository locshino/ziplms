<?php

namespace Database\Seeders;

use App\Models\ExamAnswer;
use App\Models\ExamQuestion;
use App\Models\ExamAttempt;
use Illuminate\Database\Seeder;

class ExamAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExamAttempt::factory()
            ->count(20) // Số lượng lượt làm bài muốn tạo
            ->create()
            ->each(function (ExamAttempt $attempt) {
                $exam = $attempt->exam;
                if (!$exam) {
                    $this->command->warn("ExamAttempt ID {$attempt->id} does not have an associated Exam. Skipping answer generation.");
                    return;
                }

                // Với mỗi câu hỏi trong bài thi, tạo một câu trả lời cho lượt làm bài này
                $exam->examQuestions->each(function (ExamQuestion $examQuestion) use ($attempt) {
                    ExamAnswer::factory()->create([
                        'exam_attempt_id' => $attempt->id,
                        'exam_question_id' => $examQuestion->id,
                        'question_id' => $examQuestion->question_id, // Truyền question_id rõ ràng
                    ]);
                });
            });
    }
}
