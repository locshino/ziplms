<?php

namespace App\Livewire;

use App\Enums\Status\QuizAttemptStatus;
use App\Models\QuizAttempt;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizInProgressAlert extends Component
{
    public bool $hasSentThisPageLoad = false;

    public function mount(): void
    {
        $this->loadInProgressQuizzes();
    }

    public function loadInProgressQuizzes(): void
    {
        if (!Auth::check()) {
            return;
        }

        if (session()->get('dismissed_quiz_notification_' . Auth::id())) {
            return;
        }

        $attempts = QuizAttempt::with('quiz')
            ->where('student_id', Auth::id())
            ->where('status', QuizAttemptStatus::IN_PROGRESS)
            ->get();

        $orphanedAttempts = $attempts->whereNull('quiz');
        if ($orphanedAttempts->isNotEmpty()) {
            QuizAttempt::whereIn('id', $orphanedAttempts->pluck('id'))
                ->update(['status' => QuizAttemptStatus::ABANDONED]);
        }

        $validAttempts = $attempts->whereNotNull('quiz');

        if ($validAttempts->isNotEmpty() && $this->shouldShowNotification() && !$this->hasSentThisPageLoad) {
            $this->showFilamentNotification($validAttempts);
            $this->hasSentThisPageLoad = true;
        }
    }

    public function dismissNotification(): void
    {
        session()->put('dismissed_quiz_notification_' . Auth::id(), true);
    }

    public function showFilamentNotification($attempts): void
    {
        $count = $attempts->count();
        $notificationId = 'quiz_in_progress_' . Auth::id();

        $notificationBuilder = Notification::make($notificationId)
            ->warning()
            ->persistent();

        if ($count === 1) {
            $attempt = $attempts->first();
            $startedAt = \Carbon\Carbon::parse($attempt->start_at)->format('d/m/Y H:i');

            $notificationBuilder
                ->title('Bạn đang làm dở quiz')
                ->body("Quiz: {$attempt->quiz->title}\nBắt đầu: {$startedAt}")
                ->actions([
                    Action::make('continue')
                        ->label('Tiếp tục làm bài')
                        ->button()
                        ->url(route('filament.app.pages.quiz-taking', ['quiz' => $attempt->quiz_id])),

                ]);
        } else {
            $quizTitles = $attempts->pluck('quiz.title')->take(3)->implode(', ');
            $moreText = $count > 3 ? "... và " . ($count - 3) . " quiz khác" : "";

            $notificationBuilder
                ->title("Bạn đang làm dở {$count} quiz")
                ->body("Các quiz: {$quizTitles}{$moreText}")
                ->actions([
                    Action::make('view_all')
                        ->label('Xem tất cả')
                        ->button()
                        ->url(route('filament.app.pages.my-quiz')),

                ]);
        }

        $notificationBuilder->send();
    }

    public function shouldShowNotification(): bool
    {
        $currentUrl = request()->url();
        return !str_contains($currentUrl, '/quiz-taking') && !str_contains($currentUrl, '/my-quiz');
    }

    public function render()
    {
        return <<<'HTML'
            <div></div>
        HTML;
    }
}
