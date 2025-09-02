<?php

namespace App\Services;

use App\Enums\Status\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\Interfaces\QuizNotificationServiceInterface;
use Illuminate\Support\Facades\Auth;

class QuizNotificationService implements QuizNotificationServiceInterface
{
    /**
     * Kiểm tra và gửi notification cho user có quiz đang IN_PROGRESS
     */
    public function checkAndNotifyInProgressQuizzes(User $user): void
    {
        // Chỉ gửi notification cho user hiện tại đang đăng nhập
        if (Auth::check() && Auth::id() === $user->id) {
            $notificationService = app(QuizFilamentNotificationService::class);
            $notificationService->sendInProgressNotifications();
        }
    }

    /**
     * Xóa tất cả notification quiz in progress của user
     */
    public function clearInProgressNotifications(User $user): void
    {
        // Với Filament notifications, chỉ cần clear session dismiss flag
        if (Auth::check() && Auth::id() === $user->id) {
            $notificationService = app(QuizFilamentNotificationService::class);
            $notificationService->clearDismissedNotifications();
        }
    }

    /**
     * Xóa notification cho một quiz attempt cụ thể
     */
    public function clearNotificationForAttempt(User $user, string $attemptId): void
    {
        // Với Filament notifications, không cần xóa thủ công
        // Notifications sẽ tự động biến mất hoặc được dismiss bởi user
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

    /**
     * Gửi notification khi quiz được assign
     */
    public function notifyQuizAssigned(\App\Models\User $user, \App\Models\Quiz $quiz): void
    {
        // Implementation for quiz assigned notification
        // You can create a specific notification class for this
    }

    /**
     * Gửi notification khi quiz sắp hết hạn
     */
    public function notifyQuizDeadlineApproaching(\App\Models\User $user, \App\Models\Quiz $quiz, int $hoursRemaining): void
    {
        // Implementation for deadline approaching notification
    }

    /**
     * Gửi notification khi quiz đã quá hạn
     */
    public function notifyQuizOverdue(\App\Models\User $user, \App\Models\Quiz $quiz): void
    {
        // Implementation for quiz overdue notification
    }

    /**
     * Gửi notification khi quiz được hoàn thành
     */
    public function notifyQuizCompleted(\App\Models\User $user, \App\Models\QuizAttempt $attempt): void
    {
        // Implementation for quiz completed notification
    }

    /**
     * Gửi notification khi quiz được chấm điểm
     */
    public function notifyQuizGraded(\App\Models\User $user, \App\Models\QuizAttempt $attempt): void
    {
        // Implementation for quiz graded notification
    }

    /**
     * Gửi notification nhắc nhở làm quiz
     */
    public function notifyQuizReminder(\App\Models\User $user, \App\Models\Quiz $quiz): void
    {
        // Implementation for quiz reminder notification
    }

    /**
     * Gửi notification khi quiz được cập nhật
     */
    public function notifyQuizUpdated(\Illuminate\Database\Eloquent\Collection $users, \App\Models\Quiz $quiz): void
    {
        // Implementation for quiz updated notification
        foreach ($users as $user) {
            // Send notification to each user
        }
    }

    /**
     * Gửi notification khi quiz bị hủy
     */
    public function notifyQuizCancelled(\Illuminate\Database\Eloquent\Collection $users, \App\Models\Quiz $quiz): void
    {
        // Implementation for quiz cancelled notification
        foreach ($users as $user) {
            // Send notification to each user
        }
    }

    /**
     * Gửi notification hàng loạt
     */
    public function sendBulkNotifications(\Illuminate\Database\Eloquent\Collection $users, string $notificationType, array $data): void
    {
        // Implementation for bulk notifications
        foreach ($users as $user) {
            // Send notification based on type and data
        }
    }

    /**
     * Lấy danh sách notification chưa đọc của user
     */
    public function getUnreadNotifications(\App\Models\User $user): \Illuminate\Database\Eloquent\Collection
    {
        return $user->unreadNotifications;
    }

    /**
     * Đánh dấu notification đã đọc
     */
    public function markNotificationAsRead(\App\Models\User $user, string $notificationId): void
    {
        $notification = $user->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    /**
     * Đánh dấu tất cả notification đã đọc
     */
    public function markAllNotificationsAsRead(\App\Models\User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }
}
