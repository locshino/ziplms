<?php

namespace Database\Seeders;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\QuestionChoice;
use Illuminate\Database\Seeder;
use Database\Factories\Concerns\HasFakesTranslations;

class QuestionSeeder extends Seeder
{
    use Concerns\HasEnumTags,
        HasFakesTranslations;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Question::factory()
            ->count(20)
            ->create()
            ->each(function (Question $question) {
                $this->assignRandomTagFromEnum(QuestionType::class, $question);

                $questionTypeTag = $question->tagsWithType(QuestionType::key())->first();

                if (!$questionTypeTag) {
                    throw new \Exception("Missing QuestionType tag for question {$question->id}");
                }

                $questionType = QuestionType::tryFrom($questionTypeTag->name);

                match ($questionType) {
                    QuestionType::SingleChoice, QuestionType::MultipleChoice => function () use ($question) {
                        $choices = QuestionChoice::factory()
                            ->count(4)
                            ->create(['question_id' => $question->id]);

                        $choices->random()->update(['is_correct' => true]);
                    },

                    QuestionType::TrueFalse => function () use ($question) {
                        $correctIsTrue = rand(0, 1) === 1;

                        QuestionChoice::create([
                            'question_id' => $question->id,
                            'choice_text' => $this->staticTranslations([
                                'en' => 'True',
                                'vi' => 'Đúng',
                            ]),
                            'is_correct' => $correctIsTrue,
                        ]);

                        QuestionChoice::create([
                            'question_id' => $question->id,
                            'choice_text' => $this->staticTranslations([
                                'en' => 'False',
                                'vi' => 'Sai',
                            ]),
                            'is_correct' => !$correctIsTrue,
                        ]);
                    },

                    default => null, // Những loại khác không xử lý ở đây (ví dụ essay)
                };
            });
    }
}
