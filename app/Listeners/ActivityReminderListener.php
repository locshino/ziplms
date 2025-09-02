<?php

namespace App\Listeners;

use App\Enums\Status\CourseStatus; // Thêm dòng này
use App\Models\Course;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ActivityReminderListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        Log::info('ActivityReminderListener triggered.');
        if (session()->pull('login_notification_sent', false)) {
            return;
        }
        session()->put('login_notification_sent', true);

        $user = $event->user;
        if (!$user) {
            Log::warning('No authenticated user found in Login event.');
            return;
        }

        Log::info('Authenticated user found.', ['user_id' => $user->id, 'roles' => $user->getRoleNames()]);

        $now = now();
        $courses = collect(); // Khởi tạo collection rỗng

        // Lấy danh sách khóa học tùy role
        if ($user->hasRole('teacher')) {
            Log::info('User is a teacher. Fetching courses.');
            $courses = Course::where('teacher_id', $user->id)
                // *** FIX: THÊM ĐIỀU KIỆN LỌC KHÓA HỌC ***
                ->where('status', CourseStatus::PUBLISHED)
                ->where(function ($query) use ($now) {
                    $query->whereNull('end_at')->orWhere('end_at', '>=', $now);
                })
                ->with([
                    'quizzes' => function ($query) use ($now) {
                        $query->wherePivot('end_at', '>=', $now);
                    },
                    'assignments' => function ($query) use ($now) {
                        $query->wherePivot('end_submission_at', '>=', $now);
                    },
                ])
                ->get();
        } elseif ($user->hasRole('student')) { // Sử dụng elseif để rõ ràng hơn
            Log::info('User is a student. Fetching courses.');
            $courses = $user->courses()
                // *** FIX: THÊM ĐIỀU KIỆN LỌC KHÓA HỌC ***
                ->where('courses.status', CourseStatus::PUBLISHED)
                ->where(function ($query) use ($now) {
                    $query->whereNull('courses.end_at')->orWhere('courses.end_at', '>=', $now);
                })
                ->with([
                    'quizzes' => function ($query) use ($now) {
                        $query->wherePivot('end_at', '>=', $now);
                    },
                    'assignments' => function ($query) use ($now) {
                        $query->wherePivot('end_submission_at', '>=', $now);
                    },
                ])
                ->get();
        }

        Log::info('Courses fetched.', ['course_count' => $courses->count()]);

        // Phần còn lại của logic không cần thay đổi vì đã có kiểm tra hasAttempt/hasSubmission
        if ($user->hasRole('teacher') || $user->hasRole('student')) {
            $messages = [];
            foreach ($courses as $course) {
                // Kiểm tra quiz gần hết hạn (logic "chưa làm" đã có sẵn)
                foreach ($course->quizzes as $quiz) {
                    $hasAttempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
                        ->where('student_id', $user->id)
                        ->exists();

                    if (
                        !$hasAttempt &&
                        $quiz->pivot->end_at &&
                        $quiz->pivot->end_at->between(Carbon::now(), Carbon::now()->addWeek())
                    ) {
                        $endAt = $quiz->pivot->end_at;
                        $diffInDays = round(now()->diffInDays($endAt, false));
                        $diffInHours = round(now()->diffInHours($endAt, false));
                        $timeLeft = $diffInDays < 1 ? "còn {$diffInHours} giờ" : "còn {$diffInDays} ngày";
                        $messages[] = "<strong>Quiz:</strong> {$quiz->title} (hạn: {$endAt->format('d/m/Y')} - {$timeLeft})";
                    }
                }

                // Kiểm tra assignment gần hết hạn (logic "chưa làm" đã có sẵn)
                foreach ($course->assignments as $assignment) {
                    $hasSubmission = \App\Models\Submission::query()
                        ->where('assignment_id', $assignment->id)
                        ->where('student_id', $user->id)
                        ->exists();

                    $endAt = $assignment->pivot->end_submission_at;

                    if (
                        !$hasSubmission &&
                        $endAt &&
                        $endAt->between(Carbon::now(), Carbon::now()->addWeek())
                    ) {
                        $diffInDays = round(now()->diffInDays($endAt, false));
                        $diffInHours = round(now()->diffInHours($endAt, false));
                        $timeLeft = $diffInDays < 1 ? "còn {$diffInHours} giờ" : "còn {$diffInDays} ngày";
                        $messages[] = "<strong>Assignment:</strong> {$assignment->title} (hạn: {$endAt->format('d/m/Y')} - {$timeLeft})";
                    }
                }
            }

            if (!empty($messages)) {
                $body = implode('<br>', $messages);
                Notification::make()
                    ->title('📌 Các deadline sắp tới trong 7 ngày')
                    ->body($body)
                    ->success()
                    ->send()
                    ->sendToDatabase($user);
            }
        }
    }
}