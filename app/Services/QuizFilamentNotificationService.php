<?php

namespace App\Services;

use App\Enums\Status\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuizFilamentNotificationService
{
    /**
     * Gửi thông báo nhắc nhở về các bài quiz đang làm dở.
     * Đây là phương thức chính, tự động xử lý trường hợp 1 hoặc nhiều quiz.
     */
    public function sendInProgressNotifications(): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Lấy tất cả các lần làm bài đang dang dở của user
        // Eager load 'quiz' để tránh truy vấn N+1
        $inProgressAttempts = QuizAttempt::with('quiz')
            ->where('student_id', $user->id)
            ->where('status', QuizAttemptStatus::IN_PROGRESS)
            ->whereHas('quiz') // Chỉ lấy những attempt có quiz còn tồn tại
            ->get();

        // Dọn dẹp trước: Xóa tất cả các thông báo quiz cũ
        $this->clearAllInProgressNotificationsForUser($user);

        $count = $inProgressAttempts->count();

        if ($count === 0) {
            // Nếu không có bài nào, đảm bảo không còn thông báo nào sót lại
            return;
        }

        // Tự động gọi phương thức phù hợp dựa trên số lượng
        if ($count === 1) {
            $this->sendSingleNotification($user, $inProgressAttempts->first());
        } else {
            $this->sendMultipleNotification($user, $inProgressAttempts);
        }
    }

    /**
     * Gửi thông báo cho một QuizAttempt cụ thể (được gọi từ Observer).
     * Phương thức này sẽ gọi lại logic chính để đảm bảo thông báo được cập nhật đúng.
     */
    public function sendInProgressNotification(QuizAttempt $quizAttempt): void
    {
        // Gọi lại phương thức chính để xử lý tất cả các thông báo
        $this->sendInProgressNotifications();
    }

    /**
     * Xóa thông báo cho một lần làm bài cụ thể khi nó hoàn thành hoặc bị hủy.
     * Sau khi xóa, sẽ kiểm tra và gửi lại thông báo tổng hợp nếu cần.
     */
    public function clearNotificationForAttempt(int $attemptId): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();
        $notificationId = 'quiz_in_progress_'.$attemptId;

        // Xóa thông báo cho attempt cụ thể này
        $deletedCount = $this->deleteNotificationById($user, $notificationId);

        Log::info('Cleared notification for a specific attempt', [
            'user_id' => $user->id,
            'attempt_id' => $attemptId,
            'count' => $deletedCount,
        ]);

        // Sau khi xóa một thông báo, hãy chạy lại logic chính để đảm bảo
        // hệ thống hiển thị đúng thông báo tổng hợp cho các quiz còn lại.
        $this->sendInProgressNotifications();
    }

    // =========================================================================
    // Private Helper Methods (Các phương thức hỗ trợ)
    // =========================================================================

    /**
     * Gửi thông báo cho TRƯỜNG HỢP CÓ 1 QUIZ dang dở.
     */
    private function sendSingleNotification(User $user, QuizAttempt $attempt): void
    {
        $notificationId = 'quiz_in_progress_'.$attempt->id;
        $startedAt = Carbon::parse($attempt->start_at)->format('d/m/Y H:i');

        Notification::make($notificationId)
            ->warning()
            ->title('Bạn đang làm dở bài quiz')
            ->body("Quiz: {$attempt->quiz->title}\nBắt đầu lúc: {$startedAt}")
            ->actions([
                Action::make('continue')
                    ->label('Tiếp tục làm bài')
                    ->button()
                    ->url(route('filament.app.pages.quiz-taking', ['quiz' => $attempt->quiz_id])),
                Action::make('dismiss')->label('Bỏ qua')->button()->color('gray')->close(),
            ])
            ->persistent() // Thông báo sẽ không tự biến mất
            ->sendToDatabase($user);

        Log::info('Sent single in-progress notification', ['user_id' => $user->id, 'attempt_id' => $attempt->id]);
    }

    /**
     * Gửi thông báo cho TRƯỜNG HỢP CÓ NHIỀU QUIZ dang dở.
     */
    private function sendMultipleNotification(User $user, $attempts): void
    {
        $count = $attempts->count();
        $notificationId = 'quiz_in_progress_multiple_'.$user->id;

        // Lấy 3 tiêu đề đầu tiên để hiển thị
        $quizTitles = $attempts->pluck('quiz.title')->take(3)->implode(', ');
        $moreText = $count > 3 ? '... và '.($count - 3).' bài khác' : '';

        Notification::make($notificationId)
            ->warning()
            ->title("Bạn có {$count} bài quiz đang dang dở")
            ->body("Bao gồm: {$quizTitles}{$moreText}")
            ->actions([
                Action::make('view_all')
                    ->label('Xem các bài làm')
                    ->button()
                    ->url(route('filament.app.pages.my-quiz')), // Chuyển hướng đến trang danh sách
                Action::make('dismiss')->label('Bỏ qua')->button()->color('gray')->close(),
            ])
            ->persistent() // Thông báo sẽ không tự biến mất
            ->sendToDatabase($user);

        Log::info('Sent multiple in-progress notification', ['user_id' => $user->id, 'count' => $count]);
    }

    /**
     * Dọn dẹp TẤT CẢ các thông báo về quiz đang dang dở của một user.
     * Được gọi trước khi gửi thông báo mới để tránh trùng lặp.
     */
    private function clearAllInProgressNotificationsForUser(User $user): void
    {
        $deletedCount = $user->notifications()
            ->where('type', 'Filament\\Notifications\\DatabaseNotification')
            ->where(function ($query) {
                $query->where('data->id', 'like', 'quiz_in_progress_%');
            })
            ->delete();

        if ($deletedCount > 0) {
            Log::info('Cleared all in-progress notifications for user', [
                'user_id' => $user->id,
                'count' => $deletedCount,
            ]);
        }
    }

    /**
     * Xóa một notification cụ thể dựa trên ID của nó.
     */
    private function deleteNotificationById(User $user, string $notificationId): int
    {
        return $user->notifications()
            ->where('type', 'Filament\\Notifications\\DatabaseNotification')
            ->where('data->id', $notificationId)
            ->delete();
    }
}
