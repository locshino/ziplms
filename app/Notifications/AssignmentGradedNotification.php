<?php

namespace App\Notifications;

use App\Filament\Pages\MyAssignmentsPage;
use App\Models\Submission;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AssignmentGradedNotification extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    public Submission $submission;

    /**
     * Create a new notification instance.
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
        // Tải trước các quan hệ cần thiết để tránh lỗi khi xử lý trong hàng đợi (queue)
        $this->submission->load('assignment', 'grader');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Sử dụng kênh database cho Filament
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $assignment = $this->submission->assignment;
        $grader = $this->submission->grader;
        $points = number_format($this->submission->points, 1);
        $maxPoints = number_format($assignment->max_points, 1);

        // Lấy tên giáo viên, nếu không có thì hiển thị 'N/A'
        $graderName = $grader ? $grader->name : 'N/A';

        // Sử dụng builder của Filament để tạo thông báo
        return FilamentNotification::make()
            ->title('Bài tập của bạn đã được chấm')
            ->icon('heroicon-o-academic-cap')
            ->body("Giáo viên '{$graderName}' đã chấm bài tập '{$assignment->title}'. Điểm của bạn là: {$points} / {$maxPoints}.")
            ->actions([
                Action::make('view')
                    ->label('Xem chi tiết')
                    ->url(MyAssignmentsPage::getUrl(), shouldOpenInNewTab: true) // Dẫn đến trang "My Assignments" và mở trong tab mới
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
