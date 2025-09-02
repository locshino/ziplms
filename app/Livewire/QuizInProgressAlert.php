<?php

namespace App\Livewire;

use App\Enums\Status\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Services\QuizFilamentNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

    /**
     * Kiểm tra xem có thay đổi về trạng thái quiz in-progress không
     */
    private function hasInProgressQuizStatusChanged(): bool
    {
        $user = Auth::user();
        $cacheKey = 'quiz_in_progress_status_' . $user->id;
        
        // Lấy trạng thái hiện tại của các quiz in-progress
        $currentStatus = $this->getCurrentInProgressQuizStatus();
        
        // Lấy trạng thái từ cache
        $cachedStatus = Cache::get($cacheKey);
        
        // So sánh trạng thái hiện tại với cache
        return $currentStatus !== $cachedStatus;
    }

    /**
     * Cập nhật cache với trạng thái quiz in-progress hiện tại
     */
    private function updateInProgressQuizCache(): void
    {
        $user = Auth::user();
        $cacheKey = 'quiz_in_progress_status_' . $user->id;
        
        $currentStatus = $this->getCurrentInProgressQuizStatus();
        
        // Cache trong 5 phút để tránh gửi thông báo quá thường xuyên
        Cache::put($cacheKey, $currentStatus, now()->addMinutes(5));
    }

    /**
     * Lấy trạng thái hiện tại của các quiz in-progress
     * Trả về một chuỗi hash đại diện cho trạng thái hiện tại
     */
    private function getCurrentInProgressQuizStatus(): string
    {
        $user = Auth::user();
        
        // Lấy tất cả các quiz attempt đang in-progress
        $inProgressAttempts = QuizAttempt::with(['quiz', 'quiz.courses'])
            ->where('student_id', $user->id)
            ->where('status', QuizAttemptStatus::IN_PROGRESS)
            ->whereHas('quiz')
            ->get()
            ->filter(function ($attempt) use ($user) {
                // Áp dụng cùng logic filter như trong QuizFilamentNotificationService
                $quiz = $attempt->quiz;
                $now = \Carbon\Carbon::now();
                
                $userCourses = $quiz->courses()->whereHas('users', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                })->get();
                
                foreach ($userCourses as $course) {
                    $courseQuiz = $course->pivot;
                    $endAt = $courseQuiz->end_at;
                    
                    if (!$endAt || $now->lte($endAt)) {
                        return true;
                    }
                }
                
                return false;
            });
        
        // Tạo hash từ ID và updated_at của các attempt
        $statusData = $inProgressAttempts->map(function ($attempt) {
            return $attempt->id . '_' . $attempt->updated_at->timestamp;
        })->sort()->implode('|');
        
        return md5($statusData);
    }

    public function render()
    {
        return <<<'HTML'
            <div></div>
        HTML;
    }
}