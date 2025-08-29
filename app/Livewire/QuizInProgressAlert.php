<?php

namespace App\Livewire;

use App\Enums\Status\QuizAttemptStatus;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizInProgressAlert extends Component
{
    public $inProgressQuizzes = [];

    public $showAlert = false;

    protected $listeners = ['refreshAlert' => 'loadInProgressQuizzes'];

    public function mount()
    {
        $this->loadInProgressQuizzes();
        $this->checkCurrentPage();
    }

    public function loadInProgressQuizzes()
    {
        if (! Auth::check()) {
            return;
        }

        $attempts = QuizAttempt::with('quiz')
            ->where('student_id', Auth::id())
            ->where('status', QuizAttemptStatus::IN_PROGRESS)
            ->get();

        // Clean up orphaned attempts (where quiz has been deleted)
        $orphanedAttempts = $attempts->whereNull('quiz');
        if ($orphanedAttempts->count() > 0) {
            QuizAttempt::whereIn('id', $orphanedAttempts->pluck('id'))
                ->update(['status' => QuizAttemptStatus::ABANDONED]);
        }

        $this->inProgressQuizzes = $attempts
            ->whereNotNull('quiz')
            ->map(function ($attempt) {
                return [
                    'id' => $attempt->id,
                    'quiz_id' => $attempt->quiz_id,
                    'quiz_title' => $attempt->quiz->title,
                    'started_at' => $attempt->start_at,
                    'url' => route('filament.app.pages.quiz-taking', ['quiz' => $attempt->quiz_id]),
                ];
            })
            ->toArray();

        $this->showAlert = count($this->inProgressQuizzes) > 0;
        $this->checkCurrentPage();
    }

    public function refreshAlert()
    {
        $this->loadInProgressQuizzes();
    }

    public function dismissAlert()
    {
        $this->showAlert = false;
    }

    public function checkCurrentPage()
    {
        // Ẩn thông báo nếu đang ở trang quiz-taking hoặc my-quiz (lịch sử quiz)
        $currentUrl = request()->url();
        if (str_contains($currentUrl, '/quiz-taking') || str_contains($currentUrl, '/my-quiz')) {
            $this->showAlert = false;
        }
    }

    public function render()
    {
        return view('livewire.quiz-in-progress-alert');
    }
}
