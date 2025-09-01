<?php

namespace App\Listeners;

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

        // Lấy user từ sự kiện Login
        $user = $event->user;
        if (! $user) {
            Log::warning('No authenticated user found in Login event.');

            return;
        }

        Log::info('Authenticated user found.', ['user_id' => $user->id, 'roles' => $user->getRoleNames()]);

        // thời điểm hiện tại
        $now = now();
        Log::info('Current time:', ['now' => $now]);

        // Lấy danh sách khóa học tùy role
        if ($user->hasRole('teacher')) {
            Log::info('User is a teacher. Fetching courses.');
            $courses = Course::where('teacher_id', $user->id)
                ->with([
                    'quizzes' => function ($query) use ($now) {
                        $query->wherePivot('end_at', '>=', $now);
                    },
                    'assignments' => function ($query) use ($now) {
                        $query->wherePivot('end_at', '>=', $now);
                    },
                ])
                ->get();
        } else {
            Log::info('User is a student. Fetching courses.');
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
        }

        Log::info('Courses fetched.', ['course_count' => $courses->count()]);

        // Chỉ xử lý thông báo cho teacher hoặc student
        if ($user->hasRole('teacher') || $user->hasRole('student')) {
            Log::info('User has a valid role for notifications.');

            $messages = [];
            foreach ($courses as $course) {
                Log::info('Processing course.', ['course_id' => $course->id]);

                // Kiểm tra quiz gần hết hạn
                foreach ($course->quizzes as $quiz) {
                    $hasAttempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
                        ->where('student_id', $user->id)
                        ->exists();

                    if (
                        ! $hasAttempt &&
                        $quiz->pivot->start_at &&
                        $quiz->pivot->end_at &&
                        $quiz->pivot->end_at->between(
                            Carbon::now(),
                            Carbon::now()->addWeek()
                        )
                    ) {
                        Log::info('Upcoming quiz deadline.', ['quiz_id' => $quiz->id]);

                        $endAt = $quiz->pivot->end_at;
                        $diffInDays = round(now()->diffInDays($endAt, false));
                        $diffInHours = round(now()->diffInHours($endAt, false));

                        $timeLeft = $diffInDays < 1 ? "còn {$diffInHours} giờ" : "còn {$diffInDays} ngày";
                        $messages[] = "<strong>Quiz:</strong> {$quiz->title} (hạn: {$endAt->format('d/m/Y')} - {$timeLeft})";
                    }
                }

                // Kiểm tra assignment gần hết hạn
                foreach ($course->assignments as $assignment) {
                    $hasSubmission = \App\Models\Submission::query()
                        ->where('assignment_id', $assignment->id)
                        ->where('student_id', $user->id)
                        ->exists();

                    $endAt = $assignment->pivot->end_at;
                    Log::info('Checking assignment.', ['assignment_id' => $assignment->id, 'end_at' => $endAt]);

                    if (
                        ! $hasSubmission &&
                        $assignment->pivot->end_at &&
                        $assignment->pivot->end_at->between(
                            Carbon::now(),
                            Carbon::now()->addWeek()
                        )
                    ) {
                        Log::info('Upcoming assignment deadline.', ['assignment_id' => $assignment->id]);

                        $diffInDays = round(now()->diffInDays($endAt, false));
                        $diffInHours = round(now()->diffInHours($endAt, false));

                        $timeLeft = $diffInDays < 1 ? "còn {$diffInHours} giờ" : "còn {$diffInDays} ngày";
                        $messages[] = "<strong>Assignment:</strong> {$assignment->title} (hạn: {$endAt->format('d/m/Y')} - {$timeLeft})";
                    }
                }
            }

            Log::info('Messages to notify:', ['messages' => $messages]);

            if (! empty($messages)) {
                Log::info('Sending notifications.', ['message_count' => count($messages)]);

                $body = implode('<br>', $messages);

                Notification::make()
                    ->title('📌 Các deadline sắp tới trong 7 ngày')
                    ->body($body)
                    ->success()
                    ->send()
                    ->sendToDatabase($user);
            } else {
                Log::info('No upcoming deadlines found.');
            }
        } else {
            Log::info('User does not have a valid role for notifications.');
        }
    }
}
