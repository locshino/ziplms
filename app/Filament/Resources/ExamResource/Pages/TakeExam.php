<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Enums\QuestionType;
use App\Filament\Resources\ExamResource;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\States\Exam\Active; // <-- THÊM DÒNG NÀY
use App\States\Exam\Cancelled;
use App\States\Exam\Completed;
use App\States\Exam\InProgress;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
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

    public ?Collection $questions = null;

    public int $currentQuestionIndex = 0;

    public array $questionMeta = [];

    // Mảng lưu câu trả lời của người dùng
    public array $singleChoiceAnswers = [];
    public array $multipleChoiceAnswers = [];
    public array $trueFalseAnswers = [];
    public array $shortAnswers = [];
    public array $essayAnswers = [];
    public array $fillBlankAnswers = [];

    public ?int $timeLeft = null;

    public bool $examStarted = false;

    public function mount(): void
    {
        // =================================================================
        // == BẮT ĐẦU: KIỂM TRA TRẠNG THÁI BÀI THI                    ==
        // =================================================================

        // Kiểm tra xem trạng thái của bài thi có phải là một instance của class 'Active' hay không.
        if (! $this->record->status instanceof Active) {
            Notification::make()
                ->title('Không thể làm bài thi này')
                ->body('Bài thi này hiện không hoạt động, đang được soạn thảo hoặc đã kết thúc.')
                ->danger()
                ->send();

            // Chuyển hướng người dùng về trang danh sách bài thi
            $this->redirect(static::$resource::getUrl('index'));

            // Dừng thực thi các mã tiếp theo
            return;
        }

        // =================================================================
        // == KẾT THÚC: KIỂM TRA TRẠNG THÁI BÀI THI                     ==
        // =================================================================
    }

    #[Computed(persist: true)]
    public function incompleteAttempt(): ?ExamAttempt
    {
        return ExamAttempt::where('exam_id', $this->record->id)
            ->where('user_id', Auth::id())
            ->whereState('status', InProgress::class)
            ->latest('started_at')
            ->first();
    }

    public function getTitle(): string
    {
        return $this->examStarted ? 'Đang làm bài: ' . $this->record->title : 'Bắt đầu: ' . $this->record->title;
    }

    public function continueExam(): void
    {
        $this->attempt = $this->incompleteAttempt();

        if (! $this->attempt) {
            Notification::make()->title('Không tìm thấy bài làm dang dở!')->danger()->send();
            return;
        }

        $rawFeedback = $this->attempt->getRawOriginal('feedback');
        $cleanJsonString = null;
        if (is_string($rawFeedback)) {
            $firstBrace = strpos($rawFeedback, '{');
            $lastBrace = strrpos($rawFeedback, '}');
            if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
                $cleanJsonString = substr($rawFeedback, $firstBrace, $lastBrace - $firstBrace + 1);
            }
        }

        $feedbackData = $cleanJsonString ? json_decode($cleanJsonString, true) : null;

        if ($feedbackData === null) {
            $this->invalidateOldAttempt('Dữ liệu bài làm cũ bị lỗi hoặc không hợp lệ.');
            return;
        }

        $questionOrderIds = $feedbackData['question_order_ids'] ?? [];

        if (empty($questionOrderIds)) {
            $this->invalidateOldAttempt('Dữ liệu bài làm cũ bị thiếu thông tin câu hỏi.');
            return;
        }

        $this->loadStateFromAttempt($this->attempt, $feedbackData);

        if ($this->questions->isEmpty()) {
            $this->invalidateOldAttempt('Tất cả câu hỏi trong bài thi đã bị xóa. Không thể tiếp tục.');
            return;
        }

        $this->examStarted = true;
    }

    protected function invalidateOldAttempt(string $reason): void
    {
        if ($this->attempt) {
            $this->attempt->feedback = ['error' => $reason, 'invalidated_at' => now()];
            $this->attempt->status->transitionTo(Cancelled::class);
        }
        Notification::make()->title('Không thể tiếp tục bài làm!')->body($reason)->warning()->send();
        unset($this->incompleteAttempt);
        $this->attempt = null;
        $this->examStarted = false;
    }

    public function startExam(): void
    {
        $oldAttempts = ExamAttempt::where('exam_id', $this->record->id)
            ->where('user_id', Auth::id())
            ->whereState('status', InProgress::class)
            ->get();

        foreach ($oldAttempts as $oldAttempt) {
            $oldAttempt->feedback = ['error' => 'Bị hủy bởi một lượt làm bài mới.', 'invalidated_at' => now()];
            $oldAttempt->status->transitionTo(Cancelled::class);
        }
        unset($this->incompleteAttempt);

        $query = $this->record->questions()->with('choices')->withPivot('id', 'points');

        $this->questions = $this->record->shuffle_questions
            ? $query->inRandomOrder()->get()
            : $query->orderBy('exam_questions.question_order')->get();

        if ($this->questions->isEmpty()) {
            Notification::make()->title('Bài thi này không có câu hỏi nào.')->warning()->send();
            return;
        }

        $questionOrderIds = $this->questions->pluck('id')->toArray();

        $feedbackData = [
            'question_order_ids' => $questionOrderIds,
            'answers' => [],
            'current_question_index' => 0,
        ];

        $this->attempt = ExamAttempt::create([
            'exam_id' => $this->record->id,
            'user_id' => Auth::id(),
            'status' => InProgress::class,
            'started_at' => now(),
            'feedback' => $feedbackData,
        ]);

        $this->initializeAnswers();
        $this->currentQuestionIndex = 0;
        $this->timeLeft = $this->record->duration_minutes * 60;
        $this->examStarted = true;
    }

    protected function loadStateFromAttempt(ExamAttempt $attempt, array $feedbackData): void
    {
        $questionOrderIds = $feedbackData['question_order_ids'] ?? [];
        $savedAnswers = $feedbackData['answers'] ?? [];
        $this->currentQuestionIndex = $feedbackData['current_question_index'] ?? 0;

        if (empty($questionOrderIds)) {
            $this->questions = collect();
        } else {
            $query = $this->record->questions()
                ->withoutGlobalScopes()
                ->with('choices')->withPivot('id', 'points')
                ->whereIn('questions.id', $questionOrderIds);

            if (! $this->record->shuffle_questions) {
                $query->orderBy('exam_questions.question_order');
            } else {
                $questionOrderIdsString = implode(',', array_map('intval', $questionOrderIds));
                if (!empty($questionOrderIdsString)) {
                    $query->orderByRaw("FIELD(questions.id, $questionOrderIdsString)");
                }
            }
            $this->questions = $query->get();
        }

        if ($this->questions->isNotEmpty()) {
            $this->currentQuestionIndex = min($this->currentQuestionIndex, $this->questions->count() - 1);
        } else {
            $this->currentQuestionIndex = 0;
        }

        $this->initializeAnswers($savedAnswers);

        $duration = $this->record->duration_minutes * 60;
        $elapsed = now()->diffInSeconds($attempt->started_at);
        $this->timeLeft = max(0, $duration - $elapsed);
    }

    protected function initializeAnswers(array $savedAnswers = []): void
    {
        $this->singleChoiceAnswers = $savedAnswers['single_choice'] ?? [];
        $this->multipleChoiceAnswers = $savedAnswers['multiple_choice'] ?? [];
        $this->trueFalseAnswers = $savedAnswers['true_false'] ?? [];
        $this->shortAnswers = $savedAnswers['short_answer'] ?? [];
        $this->essayAnswers = $savedAnswers['essay'] ?? [];
        $this->fillBlankAnswers = $savedAnswers['fill_blank'] ?? [];

        foreach ($this->questions as $question) {
            $qId = $question->id;
            $type = $this->getQuestionType($question)?->value;

            match ($type) {
                'single_choice' => $this->singleChoiceAnswers[$qId] = $this->singleChoiceAnswers[$qId] ?? null,
                'multiple_choice' => $this->multipleChoiceAnswers[$qId] = $this->multipleChoiceAnswers[$qId] ?? [],
                'true_false' => $this->trueFalseAnswers[$qId] = $this->trueFalseAnswers[$qId] ?? null,
                'short_answer' => $this->shortAnswers[$qId] = $this->shortAnswers[$qId] ?? null,
                'essay' => $this->essayAnswers[$qId] = $this->essayAnswers[$qId] ?? null,
                'fill_blank' => $this->fillBlankAnswers[$qId] = $this->fillBlankAnswers[$qId] ?? [],
                default => null,
            };

            $this->questionMeta[$qId] = [
                'exam_question_id' => $question->pivot->id,
                'points' => $question->pivot->points,
            ];
        }
    }

    public function updated($property): void
    {
        if (
            str_starts_with($property, 'singleChoiceAnswers.') ||
            str_starts_with($property, 'multipleChoiceAnswers.') ||
            str_starts_with($property, 'trueFalseAnswers.') ||
            str_starts_with($property, 'shortAnswers.') ||
            str_starts_with($property, 'essayAnswers.') ||
            str_starts_with($property, 'fillBlankAnswers.')
        ) {
            $this->saveStateToFeedback();
        }
    }

    protected function saveStateToFeedback(): void
    {
        if ($this->attempt && $this->questions?->isNotEmpty()) {
            $feedbackData = [
                'question_order_ids' => $this->questions->pluck('id')->toArray(),
                'current_question_index' => $this->currentQuestionIndex,
                'answers' => [
                    'single_choice' => $this->singleChoiceAnswers,
                    'multiple_choice' => $this->multipleChoiceAnswers,
                    'true_false' => $this->trueFalseAnswers,
                    'short_answer' => $this->shortAnswers,
                    'essay' => $this->essayAnswers,
                    'fill_blank' => $this->fillBlankAnswers,
                ],
            ];
            $this->attempt->update(['feedback' => $feedbackData]);
        }
    }

    public function submitExam(): void
    {
        if (! $this->attempt) {
            return;
        }
        $this->attempt = ExamAttempt::find($this->attempt->id);
        if (! $this->attempt || get_class($this->attempt->status) !== InProgress::class) {
            return;
        }
        $this->saveStateToFeedback();

        $completedAt = now();
        $totalScore = 0;

        foreach ($this->questions as $question) {
            $type = $this->getQuestionType($question)?->value;
            $qId = $question->id;
            $answerData = [
                'exam_attempt_id' => $this->attempt->id,
                'exam_question_id' => $this->questionMeta[$qId]['exam_question_id'],
                'question_id' => $qId,
                'selected_choice_id' => null,
                'chosen_option_ids' => null,
                'answer_text' => null,
                'is_correct' => false,
                'points_earned' => 0,
            ];

            switch ($type) {
                case 'single_choice':
                case 'true_false':
                    $selectedChoiceId = $this->singleChoiceAnswers[$qId] ?? ($this->trueFalseAnswers[$qId] ?? null);
                    $answerData['selected_choice_id'] = $selectedChoiceId;
                    $correctChoice = $question->choices->where('is_correct', true)->first();
                    if ($correctChoice && $selectedChoiceId == $correctChoice->id) {
                        $answerData['is_correct'] = true;
                        $answerData['points_earned'] = $this->questionMeta[$qId]['points'] ?? 1;
                        $totalScore += $answerData['points_earned'];
                    }
                    break;
                case 'multiple_choice':
                    $selectedChoices = array_filter($this->multipleChoiceAnswers[$qId] ?? []);
                    sort($selectedChoices);
                    $answerData['chosen_option_ids'] = $selectedChoices;
                    $correctChoices = $question->choices->where('is_correct', true)->pluck('id')->sort()->values()->all();
                    if (! empty($selectedChoices) && $selectedChoices === $correctChoices) {
                        $answerData['is_correct'] = true;
                        $answerData['points_earned'] = $this->questionMeta[$qId]['points'] ?? 1;
                        $totalScore += $answerData['points_earned'];
                    }
                    break;
                case 'short_answer':
                case 'essay':
                    $answerText = ($type === 'short_answer' ? $this->shortAnswers[$qId] : $this->essayAnswers[$qId]) ?? null;
                    if ($answerText) {
                        $answerData['answer_text'] = ['vi' => $answerText];
                    }
                    $answerData['points_earned'] = null; // Needs manual grading
                    break;
                case 'fill_blank':
                    $userAnswers = array_map('trim', $this->fillBlankAnswers[$qId] ?? []);
                    $answerData['answer_text'] = ['vi' => implode('|', $userAnswers)];
                    $correctAnswers = $question->choices->pluck('choice_text')->map(fn($text) => trim($text))->all();
                    if (! empty($userAnswers) && $userAnswers === $correctAnswers) {
                        $answerData['is_correct'] = true;
                        $answerData['points_earned'] = $this->questionMeta[$qId]['points'] ?? 1;
                        $totalScore += $answerData['points_earned'];
                    }
                    break;
            }
            ExamAnswer::create($answerData);
        }

        $timeSpent = $this->attempt->started_at->diffInSeconds($completedAt);
        $this->attempt->score = $totalScore;
        $this->attempt->completed_at = $completedAt;
        $this->attempt->time_spent_seconds = $timeSpent;
        $this->attempt->status->transitionTo(Completed::class);

        Notification::make()->title('Nộp bài thành công!')->success()->send();
        $this->redirect(static::$resource::getUrl('index'));
    }

    public function nextQuestion(): void
    {
        if ($this->currentQuestionIndex < $this->questions->count() - 1) {
            $this->currentQuestionIndex++;
            $this->saveStateToFeedback();
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->saveStateToFeedback();
        }
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < $this->questions->count()) {
            $this->currentQuestionIndex = $index;
            $this->saveStateToFeedback();
        }
    }

    public function getQuestionType(Question $question): ?QuestionType
    {
        $tag = $question->tagsWithType(QuestionType::key())->first();
        return $tag ? QuestionType::tryFrom($tag->name) : null;
    }

    protected function getHeaderActions(): array
    {
        if ($this->examStarted) {
            return [];
        }

        return [
            Action::make('startExam')
                ->label('Bắt đầu bài thi mới')
                ->action('startExam')
                ->requiresConfirmation()
                ->modalHeading('Bắt đầu bài thi mới?')
                ->modalDescription('Bắt đầu một lượt làm bài mới sẽ xoá bài làm dang dở trước đó (nếu có). Bạn có chắc chắn không?')
                ->modalSubmitActionLabel('Vâng, bắt đầu'),
        ];
    }

    public function getAnsweredQuestionsCount(): int
    {
        $answeredCount = 0;
        if (empty($this->questions)) {
            return 0;
        }
        foreach ($this->questions as $question) {
            $qId = $question->id;
            $type = $this->getQuestionType($question)?->value;

            $isAnswered = match ($type) {
                'single_choice', 'true_false' => !is_null($this->singleChoiceAnswers[$qId] ?? null) || !is_null($this->trueFalseAnswers[$qId] ?? null),
                'multiple_choice' => !empty($this->multipleChoiceAnswers[$qId] ?? []),
                'fill_blank' => !empty(array_filter($this->fillBlankAnswers[$qId] ?? [])),
                'short_answer' => !blank($this->shortAnswers[$qId] ?? null),
                'essay' => !blank($this->essayAnswers[$qId] ?? null),
                default => false,
            };

            if ($isAnswered) {
                $answeredCount++;
            }
        }
        return $answeredCount;
    }

    protected function getFooterActions(): array
    {
        if (!$this->examStarted) {
            return [];
        }

        return [
            Action::make('submitModal')
                ->label('Nộp Bài')
                ->color('primary')
                ->modalContent(fn(Action $action): View => view(
                    'filament.resources.exam-resource.pages.actions.submit-modal-content',
                    [
                        'action' => $action,
                        'answeredCount' => $this->getAnsweredQuestionsCount(),
                        'totalQuestions' => $this->questions->count(),
                    ]
                ))
                ->registerModalActions([
                    Action::make('confirmSubmit')
                        ->label('Xác nhận & Nộp bài')
                        ->action(fn() => $this->submitExam()),
                    Action::make('cancel')
                        ->label('Quay lại làm bài')
                        ->color('gray')
                        ->close(),
                ])
                ->modalActions([]),
        ];
    }
}
