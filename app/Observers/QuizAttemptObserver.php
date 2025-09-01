<?php

namespace App\Observers;

use App\Enums\Status\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Services\QuizFilamentNotificationService;

class QuizAttemptObserver
{
    public function __construct()
    {
        \Log::info('QuizAttemptObserver instantiated');
    }

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
        // Debug logging
        \Log::info('QuizAttemptObserver updated called', [
            'attempt_id' => $quizAttempt->id,
            'old_status' => $quizAttempt->getOriginal('status'),
            'new_status' => $quizAttempt->status->value,
            'is_dirty' => $quizAttempt->isDirty('status')
        ]);
        // Kiểm tra nếu status thay đổi thành IN_PROGRESS
        if ($quizAttempt->isDirty('status') && $quizAttempt->status === QuizAttemptStatus::IN_PROGRESS) {
            \Log::info('Sending IN_PROGRESS notification', ['attempt_id' => $quizAttempt->id]);
            $this->sendInProgressNotification($quizAttempt);
        }

        // Xóa notification khi quiz hoàn thành hoặc bị hủy
        if ($quizAttempt->isDirty('status') &&
            in_array($quizAttempt->status, [QuizAttemptStatus::COMPLETED, QuizAttemptStatus::ABANDONED, QuizAttemptStatus::GRADED])) {
            \Log::info('Removing IN_PROGRESS notification', ['attempt_id' => $quizAttempt->id, 'status' => $quizAttempt->status->value]);
            $this->removeInProgressNotification($quizAttempt);
        }
    }

    /**
     * Gửi notification khi quiz đang trong trạng thái IN_PROGRESS
     */
    private function sendInProgressNotification(QuizAttempt $quizAttempt): void
    {
        $notificationService = app(QuizFilamentNotificationService::class);
        $notificationService->sendInProgressNotification($quizAttempt);
    }

    /**
     * Xóa notification quiz in progress khi quiz hoàn thành
     */
    private function removeInProgressNotification(QuizAttempt $quizAttempt): void
    {
        $notificationService = app(QuizFilamentNotificationService::class);
        $notificationService->clearNotificationForAttempt((int) $quizAttempt->id);
    }
}
