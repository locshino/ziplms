<?php

namespace App\Livewire;

use App\Models\AnswerChoice;
use App\Models\Question;
use App\Models\QuizAttempt;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache; // Import the Cache facade
use Livewire\Component;
use Livewire\WithPagination;

class ShowQuizAnswers extends Component
{
    use WithPagination;

    public QuizAttempt $record;

    public int $perPage = 5;

    public array $perPageOptions = [5, 10, 15, 25, 50];

    // Properties to hold the processed data and statistics
    public array $allProcessedAnswers = [];

    public int $correctCount = 0;

    public int $partiallyCorrectCount = 0;

    public int $incorrectCount = 0;

    // Property to hold the current filter status
    public ?string $filterStatus = null;

    /**
     * The mount method is called only once. It now uses caching to
     * process and store the answer data efficiently.
     */
    public function mount(): void
    {
        $cacheKey = 'quiz-attempt-answers-'.$this->record->id;

        // Retrieve data from cache or compute and store it for 5 minutes.
        $cachedData = Cache::remember($cacheKey, now()->addMinutes(5), function () {
            return $this->processAllAnswers();
        });

        $this->allProcessedAnswers = $cachedData['processed_answers'];
        $this->correctCount = $cachedData['correct_count'];
        $this->partiallyCorrectCount = $cachedData['partially_correct_count'];
        $this->incorrectCount = $cachedData['incorrect_count'];
    }

    /**
     * Livewire lifecycle hook that runs when the $perPage property is updated.
     */
    public function updatedPerPage($value): void
    {
        $this->resetPage($this->getPageName());
    }

    /**
     * Toggles the filter based on the clicked widget's status.
     */
    public function toggleFilter(string $status): void
    {
        $this->filterStatus = $this->filterStatus === $status ? null : $status;
        $this->resetPage($this->getPageName());
    }

    /**
     * The render method now handles filtering and pagination of the pre-processed data.
     */
    public function render()
    {
        $answersToDisplay = $this->allProcessedAnswers;

        if ($this->filterStatus) {
            $answersToDisplay = array_filter($answersToDisplay, function ($answer) {
                return $answer['status'] === $this->filterStatus;
            });
        }

        $currentPage = $this->getPage($this->getPageName());
        $totalItems = count($answersToDisplay);

        $currentPageItems = array_slice(array_values($answersToDisplay), ($currentPage - 1) * $this->perPage, $this->perPage);

        $paginatedAnswers = new LengthAwarePaginator(
            $currentPageItems,
            $totalItems,
            $this->perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => $this->getPageName(),
            ]
        );

        return view('livewire.show-quiz-answers', [
            'processedAnswers' => $paginatedAnswers,
        ]);
    }

    /**
     * Process all answers and return the data and statistics.
     * This method is now called only when the cache is empty.
     */
    protected function processAllAnswers(): array
    {
        $answers = $this->record->answers ?? [];
        if (empty($answers)) {
            return [
                'processed_answers' => [],
                'correct_count' => 0,
                'partially_correct_count' => 0,
                'incorrect_count' => 0,
            ];
        }

        $questionIds = array_column($answers, 'question_id');

        $allChoicesForQuestions = AnswerChoice::whereIn('question_id', $questionIds)
            ->get(['id', 'question_id', 'title', 'is_correct']);

        $questions = Question::whereIn('id', $questionIds)->pluck('title', 'id');

        $choiceModels = $allChoicesForQuestions->keyBy('id');
        $correctChoicesByQuestion = $allChoicesForQuestions->where('is_correct', true)
            ->groupBy('question_id')
            ->map(fn ($choices) => $choices->pluck('id')->sort()->values());

        $correct = 0;
        $partial = 0;
        $incorrect = 0;

        $processed = array_map(function ($answer) use ($questions, $choiceModels, $correctChoicesByQuestion, &$correct, &$partial, &$incorrect) {
            $questionId = $answer['question_id'];
            $studentChoiceIds = collect(Arr::wrap($answer['answer_choice_id'] ?? []))->sort()->values();
            $actualCorrectIds = $correctChoicesByQuestion->get($questionId, collect());

            $status = 'incorrect';
            if ($studentChoiceIds->isNotEmpty()) {
                if ($studentChoiceIds->all() === $actualCorrectIds->all()) {
                    $status = 'correct';
                } else {
                    $hasIncorrectSelection = $studentChoiceIds->contains(fn ($id) => ! $choiceModels->get($id)?->is_correct);
                    $status = $hasIncorrectSelection ? 'incorrect' : 'partially_correct';
                }
            }

            match ($status) {
                'correct' => $correct++,
                'partially_correct' => $partial++,
                'incorrect' => $incorrect++,
            };

            $answerTexts = $studentChoiceIds->map(fn ($id) => $choiceModels->get($id)?->title ?? 'Answer not found');

            return [
                'question_text' => $questions[$questionId] ?? 'Question not found',
                'answer_text' => $answerTexts->count() === 1 ? $answerTexts->first() : $answerTexts->all(),
                'status' => $status,
            ];
        }, $answers);

        return [
            'processed_answers' => $processed,
            'correct_count' => $correct,
            'partially_correct_count' => $partial,
            'incorrect_count' => $incorrect,
        ];
    }

    /**
     * Get the page name for pagination.
     */
    private function getPageName(): string
    {
        return 'show-quiz-answers';
    }
}
