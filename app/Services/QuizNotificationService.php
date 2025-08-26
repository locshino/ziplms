<?php

namespace App\Services;

use App\Enums\Status\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Notifications\QuizInProgressNotification;

class QuizNotificationService
{
    /**
     * Kiểm tra và gửi notification cho user có quiz đang IN_PROGRESS
     */
    public function checkAndNotifyInProgressQuizzes(User $user): void
    {
        // Lấy tất cả quiz attempts đang IN_PROGRESS của user
        $inProgressAttempts = QuizAttempt::with('quiz')
            ->where('student_id', $user->id)
            ->where('status', QuizAttemptStatus::IN_PROGRESS)
            ->get();

        // Gửi notification cho từng quiz attempt
        foreach ($inProgressAttempts as $attempt) {
            // Kiểm tra xem đã có notification cho quiz attempt này chưa
            $existingNotification = $user->notifications()
                ->where('type', QuizInProgressNotification::class)
                ->where('data->quiz_attempt_id', $attempt->id)
                ->first();

            // Nếu chưa có notification thì gửi mới
            if (!$existingNotification) {
                $user->notify(new QuizInProgressNotification($attempt));
            }
        }
    }

    /**
     * Xóa tất cả notification quiz in progress của user
     */
    public function clearInProgressNotifications(User $user): void
    {
        $user->notifications()
            ->where('type', QuizInProgressNotification::class)
            ->delete();
    }

    /**
     * Xóa notification cho một quiz attempt cụ thể
     */
    public function clearNotificationForAttempt(User $user, string $attemptId): void
    {
        $user->notifications()
            ->where('type', QuizInProgressNotification::class)
            ->where('data->quiz_attempt_id', $attemptId)
            ->delete();
    }

    /**
     * Kiểm tra user có quiz đang IN_PROGRESS không
     */
    public function hasInProgressQuizzes(User $user): bool
    {
        return QuizAttempt::where('student_id', $user->id)
            ->where('status', QuizAttemptStatus::IN_PROGRESS)
            ->exists();
    }

    /**
     * Lấy danh sách quiz đang IN_PROGRESS của user
     */
    public function getInProgressQuizzes(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return QuizAttempt::with('quiz')
            ->where('student_id', $user->id)
            ->where('status', QuizAttemptStatus::IN_PROGRESS)
            ->get();
    }
}