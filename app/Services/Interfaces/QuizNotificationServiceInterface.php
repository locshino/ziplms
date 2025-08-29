<?php

namespace App\Services\Interfaces;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface QuizNotificationServiceInterface
{
    /**
     * Kiểm tra và gửi notification cho user có quiz đang IN_PROGRESS
     */
    public function checkAndNotifyInProgressQuizzes(User $user): void;

    /**
     * Xóa tất cả notification quiz in progress của user
     */
    public function clearInProgressNotifications(User $user): void;

    /**
     * Xóa notification cho một quiz attempt cụ thể
     */
    public function clearNotificationForAttempt(User $user, string $attemptId): void;

    /**
     * Gửi notification khi quiz được assign
     */
    public function notifyQuizAssigned(User $user, Quiz $quiz): void;

    /**
     * Gửi notification khi quiz sắp hết hạn
     */
    public function notifyQuizDeadlineApproaching(User $user, Quiz $quiz, int $hoursRemaining): void;

    /**
     * Gửi notification khi quiz đã quá hạn
     */
    public function notifyQuizOverdue(User $user, Quiz $quiz): void;

    /**
     * Gửi notification khi quiz được hoàn thành
     */
    public function notifyQuizCompleted(User $user, QuizAttempt $attempt): void;

    /**
     * Gửi notification khi quiz được chấm điểm
     */
    public function notifyQuizGraded(User $user, QuizAttempt $attempt): void;

    /**
     * Gửi notification nhắc nhở làm quiz
     */
    public function notifyQuizReminder(User $user, Quiz $quiz): void;

    /**
     * Gửi notification khi quiz được cập nhật
     */
    public function notifyQuizUpdated(Collection $users, Quiz $quiz): void;

    /**
     * Gửi notification khi quiz bị hủy
     */
    public function notifyQuizCancelled(Collection $users, Quiz $quiz): void;

    /**
     * Gửi notification hàng loạt
     */
    public function sendBulkNotifications(Collection $users, string $notificationType, array $data): void;

    /**
     * Lấy danh sách notification chưa đọc của user
     */
    public function getUnreadNotifications(User $user): Collection;

    /**
     * Đánh dấu notification đã đọc
     */
    public function markNotificationAsRead(User $user, string $notificationId): void;

    /**
     * Đánh dấu tất cả notification đã đọc
     */
    public function markAllNotificationsAsRead(User $user): void;
}
