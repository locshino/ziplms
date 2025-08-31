<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Auth;
use App\Models\Course;

class SendLoginNotification
{
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Nếu session đã đánh dấu 'login_notification_sent', không gửi thông báo nữa
        if (session()->pull('login_notification_sent', false)) {
            return;
        }
        // Đánh dấu session đã gửi thông báo lần này
        session()->put('login_notification_sent', true);
        // Lấy user hiện tại
        $user = Auth::user();
        if (!$user) {

            return;
        }
        // thời điểm hiện tại

        $now = now();
        // Lấy danh sách khóa học tùy role
        if ($user->hasRole('teacher')) {
            // Nếu là giáo viên: lấy tất cả course mà họ là teacher
            $courses = Course::where('teacher_id', $user->id)
                ->with([
                    'quizzes' => function ($query) use ($now) {
                        // Chỉ lấy quiz chưa hết hạn
                        $query->wherePivot('end_at', '>=', $now);
                    },
                    'assignments' => function ($query) use ($now) {
                        // Chỉ lấy assignment chưa hết hạn
                        $query->wherePivot('end_at', '>=', $now);
                    },
                ])
                ->get();
        } else {
            // Nếu là student: lấy tất cả course mà student đang tham gia
            $courses = $user->courses()
                ->with([
                    'quizzes' => function ($query) use ($now) {
                        $query->wherePivot('end_at', '>=', $now);
                    },
                    'assignments' => function ($query) use ($now) {
                        $query->wherePivot('end_at', '>=', $now);
                    },
                ])
                ->get();
            ;
        }
        // Chỉ xử lý thông báo cho teacher hoặc student
        if ($user->hasRole('teacher') || $user->hasRole('student'))
            foreach ($courses as $course) {
                // lưu các thông báo deadline sắp tới
                $messages = [];


                // Kiểm tra quiz gần hết hạn
                foreach ($course->quizzes as $quiz) {
                    // Kiểm tra xem student đã attempt quiz chưa
                    $hasAttempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
                        ->where('student_id', $user->id)
                        ->exists();

                    // Nếu chưa attempt và quiz có end_at trong 7 ngày tới
                    if (
                        !$hasAttempt &&
                        $quiz->pivot->end_at &&
                        $quiz->pivot->end_at->between(
                            Carbon::now(),
                            Carbon::now()->addWeek()
                        )
                    ) {
                        $messages[] = "Quiz **{$quiz->title}** (hạn: {$quiz->pivot->end_at->format('d/m/Y')})";
                    }
                }

                // Kiểm tra assignment gần hết hạn
                foreach ($course->assignments as $assignment) {
                    // Kiểm tra student đã submit chưa
                    $hasSubmission = \App\Models\Submission::query()
                        ->where('assignment_id', $assignment->id)
                        ->where('student_id', $user->id)
                        ->exists();
                    // Nếu chưa submit và end_at trong 7 ngày tới
                    if (
                        !$hasSubmission &&
                        $assignment->pivot->end_at &&
                        $assignment->pivot->end_at->between(
                            Carbon::now(),
                            Carbon::now()->addWeek()
                        )
                    ) {
                        $messages[] = "Assignment **{$assignment->title}** (hạn: {$assignment->pivot->end_at->format('d/m/Y')})";
                    }
                }


                // Nếu có bất kỳ deadline nào sắp tới, gửi thông báo duy nhất
                if (!empty($messages)) {
                    $body = implode("\n", $messages);

                    Notification::make()
                        ->title('📌 Các deadline sắp tới trong 7 ngày')
                        ->body($body)
                        ->success()
                        ->send()// gửi notification trực tiếp
                        ->sendToDatabase($user);// lưu vào database cho user
                }
            }


    }
}
