<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use App\Models\ExamQuestion;
use Illuminate\Support\Collection;
use Illuminate\Database\Seeder;

class ExamQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examIds = Exam::pluck('id');
        $questionIds = Question::pluck('id');

        if ($examIds->isEmpty() || $questionIds->isEmpty()) {
            $this->command->info('Skipping ExamQuestionSeeder: No exams or questions found.');
            return;
        }

        $possibleExamQuestions = new Collection();
        foreach ($examIds as $examId) {
            foreach ($questionIds as $questionId) {
                $possibleExamQuestions->push(['exam_id' => $examId, 'question_id' => $questionId]);
            }
        }

        // Xáo trộn và lấy một số lượng mẫu, ví dụ 30 hoặc ít hơn nếu không đủ cặp duy nhất
        $examQuestionsToCreate = $possibleExamQuestions->shuffle()->take(30);

        if ($examQuestionsToCreate->isEmpty()) {
            $this->command->info('No unique exam-question pairs to create.');
            return;
        }

        $examQuestionsToCreate->each(function ($examQuestionData) {
            ExamQuestion::factory()->create([
                'exam_id' => $examQuestionData['exam_id'],
                'question_id' => $examQuestionData['question_id'],
            ]);
        });

        $this->command->info('Created ' . $examQuestionsToCreate->count() . ' unique exam questions.');
    }
}
