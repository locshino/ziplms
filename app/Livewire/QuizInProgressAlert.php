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

    public function mount()
    {
        $this->loadInProgressQuizzes();
        $this->checkCurrentPage();
    }

    public function loadInProgressQuizzes()
    {
        if (!Auth::check()) {
            return;
        }

        $this->inProgressQuizzes = QuizAttempt::with('quiz')
            ->where('student_id', Auth::id())
            ->where('status', QuizAttemptStatus::IN_PROGRESS)
            ->get()
            ->map(function ($attempt) {
                return [
                    'id' => $attempt->id,
                    'quiz_id' => $attempt->quiz_id,
                    'quiz_title' => $attempt->quiz->title,
                    'started_at' => $attempt->start_at,
                    'url' => route('filament.app.pages.quiz-taking', ['quiz' => $attempt->quiz_id])
                ];
            })
            ->toArray();

        $this->showAlert = count($this->inProgressQuizzes) > 0;
        $this->checkCurrentPage();
    }

    public function dismissAlert()
    {
        $this->showAlert = false;
    }

    public function checkCurrentPage()
    {
        // Ẩn thông báo nếu đang ở trang quiz-taking
        $currentUrl = request()->url();
        if (str_contains($currentUrl, '/quiz-taking')) {
            $this->showAlert = false;
        }
    }

    public function render()
    {
        return view('livewire.quiz-in-progress-alert');
    }
}