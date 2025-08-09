<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\StudentQuizAnswer;
use Filament\Actions\Action;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class QuizResult extends Page implements HasInfolists
{
    use InteractsWithInfolists;

    protected static string $resource = QuizResource::class;

    protected static string $view = 'filament.resources.quiz-resource.pages.quiz-result';

    protected static ?string $title = 'Kết quả quiz';

    public Quiz $record;

    public QuizAttempt $attempt;

    public $questions = [];

    public $answers = [];

    public $results = [];

    public $statistics = [];

    public function mount(Quiz $record, QuizAttempt $attempt): void
    {
        $this->record = $record;
        $this->attempt = $attempt;

        // Verify that this attempt belongs to the current user
        if ($this->attempt->user_id !== Auth::id()) {
            abort(403);
        }

        // Load questions with answer choices
        $this->questions = $this->record->questions()->with('answerChoices')->get();

        // Load answers
        $answers = StudentQuizAnswer::where('quiz_attempt_id', $this->attempt->id)
            ->with('answerChoice')
            ->get();
        foreach ($answers as $answer) {
            $this->answers[$answer->question_id] = $answer;
        }

        // Calculate statistics
        $this->calculateStatistics();

        // Prepare results data
        $this->prepareResults();
    }

    protected function prepareResults()
    {
        foreach ($this->questions as $question) {
            $answer = $this->answers[$question->id] ?? null;
            $selectedChoice = $answer ? $answer->answerChoice : null;
            $correctChoice = $question->answerChoices->where('is_correct', true)->first();

            $this->results[] = [
                'question' => $question,
                'selected_choice' => $selectedChoice,
                'correct_choice' => $correctChoice,
                'is_correct' => $selectedChoice && $selectedChoice->is_correct,
                'points_earned' => ($selectedChoice && $selectedChoice->is_correct) ? $question->points : 0,
            ];
        }
    }

    public function getScorePercentage(): float
    {
        return round($this->attempt->score ?? 0, 2);
    }

    protected function calculateStatistics()
    {
        $totalQuestions = $this->questions->count();
        $correctAnswers = collect($this->results)->where('is_correct', true)->count();
        $totalPoints = $this->questions->sum('points');
        $earnedPoints = collect($this->results)->sum('points_earned');

        $this->statistics = [
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $totalQuestions - $correctAnswers,
            'accuracy' => $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 1) : 0,
            'total_points' => $totalPoints,
            'earned_points' => $earnedPoints,
            'score_percentage' => $this->getScorePercentage(),
            'time_taken' => $this->getTimeTaken(),
        ];
    }

    protected function getTimeTaken(): ?string
    {
        if (! $this->attempt->started_at || ! $this->attempt->completed_at) {
            return null;
        }

        $minutes = $this->attempt->started_at->diffInMinutes($this->attempt->completed_at);
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0) {
            return sprintf('%d giờ %d phút', $hours, $remainingMinutes);
        }

        return sprintf('%d phút', $remainingMinutes);
    }

    public function getGradeColor(): string
    {
        $percentage = $this->getScorePercentage();

        if ($percentage >= 90) {
            return 'success';
        }
        if ($percentage >= 80) {
            return 'info';
        }
        if ($percentage >= 70) {
            return 'warning';
        }

        return 'danger';
    }

    public function getGradeText(): string
    {
        $percentage = $this->getScorePercentage();

        if ($percentage >= 90) {
            return 'Xuất sắc';
        }
        if ($percentage >= 80) {
            return 'Giỏi';
        }
        if ($percentage >= 70) {
            return 'Khá';
        }
        if ($percentage >= 60) {
            return 'Trung bình';
        }

        return 'Yếu';
    }

    protected function getActions(): array
    {
        return [
            Action::make('retake')
                ->label('Làm lại')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->url(fn () => QuizResource::getUrl('take-quiz', ['record' => $this->record->id]))
                ->visible($this->canRetakeQuiz()),

            Action::make('view_details')
                ->label('Xem chi tiết')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->action('toggleDetails'),

            Action::make('back_to_quizzes')
                ->label('Quay lại danh sách quiz')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => QuizResource::getUrl('quiz-list')),
        ];
    }

    protected function canRetakeQuiz(): bool
    {
        $user = Auth::user();

        // Super admin và admin có thể làm lại quiz bất kỳ lúc nào
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // Check if quiz is still active for students
        if (now() > $this->record->end_at) {
            return false;
        }

        // Check max attempts for students
        if ($this->record->max_attempts) {
            $completedAttempts = QuizAttempt::where('quiz_id', $this->record->id)
                ->where('user_id', Auth::id())
                ->whereNotNull('completed_at')
                ->count();

            return $completedAttempts < $this->record->max_attempts;
        }

        return true;
    }

    public $showDetails = false;

    public function toggleDetails()
    {
        $this->showDetails = ! $this->showDetails;
    }

    public function getAttemptHistory()
    {
        return QuizAttempt::where('quiz_id', $this->record->id)
            ->where('user_id', Auth::id())
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->get();
    }
}
