<?php

namespace App\Notifications;

use App\Models\QuizAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class QuizInProgressNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public QuizAttempt $quizAttempt
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'type' => 'quiz_in_progress',
            'title' => 'Bạn đang làm dở quiz',
            'message' => "Bạn đang làm dở quiz '{$this->quizAttempt->quiz->title}'. Hãy tiếp tục hoàn thành bài quiz.",
            'quiz_attempt_id' => $this->quizAttempt->id,
            'quiz_id' => $this->quizAttempt->quiz_id,
            'quiz_title' => $this->quizAttempt->quiz->title,
            'started_at' => $this->quizAttempt->start_at,
            'action_url' => route('filament.app.pages.quiz-taking', ['quiz' => $this->quizAttempt->quiz_id]),
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'quiz_in_progress',
            'title' => 'Bạn đang làm dở quiz',
            'message' => "Bạn đang làm dở quiz '{$this->quizAttempt->quiz->title}'. Hãy tiếp tục hoàn thành bài quiz.",
            'quiz_attempt_id' => $this->quizAttempt->id,
            'quiz_id' => $this->quizAttempt->quiz_id,
            'quiz_title' => $this->quizAttempt->quiz->title,
            'started_at' => $this->quizAttempt->start_at,
            'action_url' => route('filament.app.pages.quiz-taking', ['quiz' => $this->quizAttempt->quiz_id]),
        ];
    }
}
