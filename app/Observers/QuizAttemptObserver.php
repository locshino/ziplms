<?php

namespace App\Observers;

use App\Enums\Status\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Notifications\QuizInProgressNotification;

class QuizAttemptObserver
{
    /**
     * Handle the QuizAttempt "created" event.
     */
    public function created(QuizAttempt $quizAttempt): void
    {
        // Gửi notification khi quiz attempt được tạo với status IN_PROGRESS
        if ($quizAttempt->status === QuizAttemptStatus::IN_PROGRESS) {
            $this->sendInProgressNotification($quizAttempt);
        }
    }

    /**
     * Handle the QuizAttempt "updated" event.
     */
    public function updated(QuizAttempt $quizAttempt): void
    {
        // Kiểm tra nếu status thay đổi thành IN_PROGRESS
        if ($quizAttempt->isDirty('status') && $quizAttempt->status === QuizAttemptStatus::IN_PROGRESS) {
            $this->sendInProgressNotification($quizAttempt);
        }
        
        // Xóa notification khi quiz hoàn thành hoặc bị hủy
        if ($quizAttempt->isDirty('status') && 
            in_array($quizAttempt->status, [QuizAttemptStatus::COMPLETED, QuizAttemptStatus::ABANDONED, QuizAttemptStatus::GRADED])) {
            $this->removeInProgressNotification($quizAttempt);
        }
    }

    /**
     * Gửi notification khi quiz đang trong trạng thái IN_PROGRESS
     */
    private function sendInProgressNotification(QuizAttempt $quizAttempt): void
    {
        // Xóa notification cũ trước khi gửi notification mới
        $this->removeInProgressNotification($quizAttempt);
        
        // Gửi notification mới
        $quizAttempt->student->notify(new QuizInProgressNotification($quizAttempt));
    }

    /**
     * Xóa notification quiz in progress
     */
    private function removeInProgressNotification(QuizAttempt $quizAttempt): void
    {
        $quizAttempt->student->notifications()
            ->where('type', QuizInProgressNotification::class)
            ->where('data->quiz_attempt_id', $quizAttempt->id)
            ->delete();
    }
}