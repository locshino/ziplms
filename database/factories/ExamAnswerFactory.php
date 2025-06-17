<?php

namespace Database\Factories;

use App\Enums\QuestionType;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamAnswer>
 */
class ExamAnswerFactory extends Factory
{
    use Concerns\HasAssignsRandomOrNewModel,
        Concerns\HasFakesTranslations;

    protected $model = ExamAnswer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Core IDs (exam_attempt_id, exam_question_id, question_id)
        // will be provided by the ExamAttemptSeeder.
        // Defaults here are for standalone factory usage, if any.
        return [
            'exam_attempt_id' => $this->assignRandomOrNewModel(ExamAttempt::class),
            'exam_question_id' => $this->assignRandomOrNewModel(ExamQuestion::class),
            'question_id' => function (array $attributes) {
                // Derive question_id from exam_question_id if available
                $eq = ExamQuestion::find($attributes['exam_question_id'] ?? null);
                return $eq ? $eq->question_id : $this->assignRandomOrNewModel(Question::class);
            },
            'answer_text' => null,
            'chosen_option_ids' => null, // Example: json_encode([QuestionChoice::factory()->create()->id])
            'selected_choice_id' => null, // Example: QuestionChoice::factory()
            'points_earned' => null, // Will be set based on correctness and question points
            'is_correct' => null, // Will be determined based on answer and choices
            'teacher_feedback' => $this->fakeSentenceTranslations(),
            'graded_at' => fake()->optional()->dateTime,
            'graded_by' => $this->assignRandomOrNewModel(User::class),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (ExamAnswer $examAnswer) {
            $examQuestion = $examAnswer->examQuestion;
            if (!$examQuestion) {
                $examQuestion = ExamQuestion::find($examAnswer->exam_question_id);
                if (!$examQuestion) return;
            }

            $question = $examQuestion->question;
            if (!$question) {
                $question = Question::find($examAnswer->question_id);
                if (!$question) return;
            }

            $questionTypeTag = $question->tagsWithType(QuestionType::key())->first();
            if (!$questionTypeTag) return;

            $questionType = QuestionType::tryFrom($questionTypeTag->name);

            $isCorrect = null;
            $pointsEarned = 0;
            $maxPoints = $examQuestion->points ?? 1;

            switch ($questionType) {
                case QuestionType::SingleChoice:
                case QuestionType::TrueFalse:
                    $choices = $question->choices;
                    if ($choices->isNotEmpty()) {
                        $selectedChoice = $choices->random();
                        $examAnswer->selected_choice_id = $selectedChoice->id;
                        $isCorrect = $selectedChoice->is_correct;
                        if ($isCorrect) $pointsEarned = $maxPoints;
                    }
                    break;

                case QuestionType::MultipleChoice:
                    $choices = $question->choices;
                    if ($choices->isNotEmpty()) {
                        $numToSelect = fake()->numberBetween(1, min(4, $choices->count())); // Select up to 4 or total choices
                        $selectedChoicesCollection = $choices->random($numToSelect);
                        $examAnswer->chosen_option_ids = $selectedChoicesCollection->pluck('id')->toArray();
                        $isCorrect = $selectedChoicesCollection->every(fn(QuestionChoice $choice) => $choice->is_correct) &&
                            $choices->where('is_correct', true)->count() === $selectedChoicesCollection->where('is_correct', true)->count();
                        if ($isCorrect) $pointsEarned = $maxPoints;
                    }
                    break;

                case QuestionType::Essay:
                case QuestionType::ShortAnswer:
                    $examAnswer->setTranslation('answer_text', 'en', fake()->paragraph);
                    $examAnswer->setTranslation('answer_text', 'vi', fake('vi_VN')->paragraph);
                    $isCorrect = null;
                    $pointsEarned = null; // Needs manual grading
                    break;
            }

            $examAnswer->is_correct = $isCorrect;
            if (!in_array($questionType, [QuestionType::Essay, QuestionType::ShortAnswer])) {
                $examAnswer->points_earned = $pointsEarned;
            }

            $examAnswer->saveQuietly();
        });
    }
}
