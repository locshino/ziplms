<?php

namespace App\Livewire;

use App\Services\QuizFilamentNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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

        // Kiểm tra xem có đang ở trang quiz-taking không
        if ($this->isOnQuizTakingPage()) {
            return; // Không hiển thị notification khi đang làm bài
        }

        if (!$this->hasSentThisPageLoad) {
            $notificationService = app(QuizFilamentNotificationService::class);
            $notificationService->sendInProgressNotifications();
            $this->hasSentThisPageLoad = true;
        }
    }

    /**
     * Kiểm tra xem có đang ở trang quiz-taking không
     */
    private function isOnQuizTakingPage(): bool
    {
        $currentRoute = Route::currentRouteName();
        return $currentRoute === 'filament.app.pages.quiz-taking';
    }

    public function render()
    {
        return <<<'HTML'
            <div></div>
        HTML;
    }
}