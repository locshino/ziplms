<?php

namespace App\Listeners;

use App\Services\Interfaces\QuizNotificationServiceInterface;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckInProgressQuizzesOnLogin implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private QuizNotificationServiceInterface $quizNotificationService
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Kiểm tra và gửi notification cho user có quiz đang IN_PROGRESS
        $this->quizNotificationService->checkAndNotifyInProgressQuizzes($event->user);
    }
}