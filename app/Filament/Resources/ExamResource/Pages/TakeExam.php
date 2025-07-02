<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Enums\QuestionType;
use App\Filament\Resources\ExamResource;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\Question;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;

class TakeExam extends Page
{
    protected static string $resource = ExamResource::class;

    protected static string $view = 'filament.resources.exam-resource.pages.take-exam';

    protected static string $routePath = '/{record}/take';

    protected static bool $shouldRegisterNavigation = false;

    #[Locked]
    public Exam $record;

    #[Locked]
    public ?ExamAttempt $attempt = null;

    public Collection $questions;

    public int $currentQuestionIndex = 0;

    public array $questionMeta = [];

    public array $mcqAnswers = [];

    public array $essayAnswers = [];

    public ?int $timeLeft = null;

    public bool $examStarted = false;

    public function mount(): void
    {
        //
    }

    public function getTitle(): string
    {
        return $this->examStarted ? 'Đang làm bài: '.$this->record->title : 'Bắt đầu: '.$this->record->title;
    }

    public function startExam(): void
    {
        $this->attempt = ExamAttempt::create([
            'exam_id' => $this->record->id,
            'user_id' => Auth::id(),
            'status' => 'started',
            'started_at' => now(),
        ]);

        $query = $this->record->questions()
            ->with('choices')
            ->withPivot('id', 'points', 'question_order');

        // SỬA LỖI TẠI ĐÂY:
        // Sắp xếp theo cột `question_order` trong bảng trung gian `exam_questions`
        $this->questions = $this->record->shuffle_questions
            ? $query->inRandomOrder()->get()
            : $query->orderBy('exam_questions.question_order')->get();

        foreach ($this->questions as $question) {
            if ($this->getQuestionType($question) === QuestionType::SingleChoice) {
                $this->mcqAnswers[$question->id] = null;
            } else {
                $this->essayAnswers[$question->id] = null;
            }
            $this->questionMeta[$question->id] = [
                'exam_question_id' => $question->pivot->id,
                'points' => $question->pivot->points,
            ];
        }

        $this->timeLeft = $this->record->duration_minutes * 60;
        $this->examStarted = true;
    }

    public function nextQuestion(): void
    {
        if ($this->currentQuestionIndex < $this->questions->count() - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function goToQuestion(int $index): void
    {
        $this->currentQuestionIndex = $index;
    }

    public function getQuestionType(Question $question): ?QuestionType
    {
        $tag = $question->tagsWithType(QuestionType::key())->first();

        return $tag ? QuestionType::tryFrom($tag->name) : null;
    }

    public function submitExam(): void
    {
        // Thêm kiểm tra để ngăn việc nộp bài nhiều lần
        if (! $this->attempt || $this->attempt->completed_at) {
            return;
        }

        $completedAt = now();

        $allAnswers = $this->mcqAnswers + $this->essayAnswers;

        $totalScore = 0;

        foreach ($this->questions as $question) {
            $studentAnswerData = $allAnswers[$question->id] ?? null;
            $isCorrect = null;
            $pointsEarned = 0;
            $selectedChoiceId = null;
            $answerText = null;

            if ($this->getQuestionType($question) === QuestionType::SingleChoice) {
                $selectedChoiceId = $studentAnswerData;
                $correctChoice = $question->choices->where('is_correct', true)->first();
                if ($correctChoice && $selectedChoiceId == $correctChoice->id) {
                    $isCorrect = true;
                    $pointsEarned = $this->questionMeta[$question->id]['points'] ?? 1;
                    $totalScore += $pointsEarned;
                } else {
                    $isCorrect = false;
                }
            } else {
                $answerText = $studentAnswerData;
                $pointsEarned = null;
            }

            ExamAnswer::create([
                'exam_attempt_id' => $this->attempt->id,
                'exam_question_id' => $this->questionMeta[$question->id]['exam_question_id'],
                'question_id' => $question->id,
                'selected_choice_id' => $selectedChoiceId,
                'answer_text' => ['vi' => $answerText],
                'is_correct' => $isCorrect,
                'points_earned' => $pointsEarned,
            ]);
        }

        $timeSpent = $this->attempt->started_at->diffInSeconds($completedAt);

        $this->attempt->update([
            'score' => $totalScore,
            'completed_at' => $completedAt,
            'status' => 'completed',
            'time_spent_seconds' => $timeSpent,
        ]);

        \Filament\Notifications\Notification::make()
            ->title('Nộp bài thành công!')
            ->success()
            ->send();

        $this->examStarted = false;
    }
}
