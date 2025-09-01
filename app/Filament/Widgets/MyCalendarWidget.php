<?php

namespace App\Filament\Widgets;

use App\Enums\Status\QuizStatus;
use App\Models\Course;
use App\Models\Quiz;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Actions\Action;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Enums\Status\AssignmentStatus;
class MyCalendarWidget extends CalendarWidget
{
    use HasWidgetShield;
    // Cho phép click vào event
    protected bool $eventClickEnabled = true;
    // Action mặc định khi click event
    protected ?string $defaultEventClickAction = 'viewAssignment';
    /**
     * Lấy danh sách events để hiển thị trên calendar
     */
    protected function getEvents(FetchInfo $info): Collection|array|Builder
    {
        $now = now();
        $twoMonthsLater = now()->addMonths(1); // khoảng thời gian hiển thị 2 tháng tới

        $user = auth()->user();
        $role = $user->getRoleNames()->first(); // lấy role đầu tiên của user

        $query = Course::query();
        // Nếu là student: chỉ lấy các khóa học mà user đã đăng ký
        if ($role === 'student') {
            $query->whereHas('users', fn($q) => $q->where('users.id', $user->id));
        } else {
            $query->where('teacher_id', $user->id);
        }

        $courses = $query->with('quizzes')->get();

        $events = collect();
        // Duyệt từng course
        foreach ($courses as $course) {
            // Duyệt quizzes
            foreach ($course->quizzes as $quiz) {
                $hasAttempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
                    ->where('student_id', $user->id)
                    ->exists();
                // Kiểm tra điều kiện hiển thị event
                if (!$hasAttempt && $quiz->status == QuizStatus::PUBLISHED && $quiz->pivot->end_at >= $now && $quiz->pivot->end_at->between($now, $twoMonthsLater)) {
                    $isUpcoming = $quiz->pivot->start_at > $now && $quiz->pivot->end_at > $now;
                    $key = $quiz->id . '-' . $course->id;
                    $events->push(
                        CalendarEvent::make($quiz)
                            ->title("Quiz:{$quiz->title} ")
                            ->start($quiz->pivot->start_at ?? $course->start_at)
                            ->end($quiz->pivot->end_at ?? $course->end_at)
                            ->backgroundColor('#ffffffff')
                            ->textColor('#1976d2')
                            ->allDay(true)
                            ->styles([
                                'border' => $isUpcoming ? '2px dashed #e50d0dff' : '2px solid #1976d2',
                                'border-radius' => '12px',
                                'box-shadow' => '0 4px 12px rgba(0,0,0,0.15)',
                                'padding' => '6px 12px',
                                'font-weight' => '600',
                                'font-size' => '14px',
                                'transition' => 'all 0.3s ease',
                                'border-radius' => '4px',
                                'padding' => '6px 10px',

                            ])
                            ->action('viewAssignment')
                            ->model(Quiz::class)
                            ->key($quiz->getKey())
                    );
                }

            }
            // Duyệt assignments
            foreach ($course->assignments as $assignment) {

                $hasSubmission = \App\Models\Submission::query()
                    ->where('assignment_id', $assignment->id)
                    ->where('student_id', $user->id)
                    ->exists();
                // Kiểm tra điều kiện hiển thị event
                if (!$hasSubmission && $assignment->status == AssignmentStatus::PUBLISHED && $assignment->pivot->end_at >= $now && $assignment->pivot->end_at->between($now, $twoMonthsLater)) {
                    $isUpcoming = $assignment->pivot->start_at > $now && $assignment->pivot->end_at > $now;

                    $events->push(
                        CalendarEvent::make($assignment)
                            ->title("Assignment: {$assignment->title}")
                            ->start($assignment->pivot->start_at)
                            ->end($assignment->pivot->end_at)
                            ->backgroundColor('#ffffff')
                            ->textColor('#4caf50')
                            ->allDay(true)
                            ->styles([
                                'border' => $isUpcoming ? '2px dashed #e50d0dff' : '2px solid #4caf50',
                                'border-radius' => '12px',
                                'box-shadow' => '0 4px 12px rgba(0,0,0,0.15)',
                                'padding' => '6px 12px',
                                'font-weight' => '600',
                                'font-size' => '14px',
                                'transition' => 'all 0.3s ease',
                                'border-radius' => '4px',
                                'padding' => '6px 10px',
                            ])
                            ->action('viewAssignment')
                            ->model(\App\Models\Assignment::class)
                            ->key($assignment->getKey())

                    );
                }

            }
        }

        return $events;

    }
    /**
     * Action khi click vào event: mở modal chi tiết
     */
    public function viewAssignment(): Action
    {
        return Action::make('viewAssignment')
            ->modalHeading('Chi tiết')
            ->modalContent(function (array $arguments) {
                $event = $arguments['data']['event'] ?? [];
                $props = $event['extendedProps'] ?? [];

                $modelClass = $props['model'] ?? null;
                $key = $props['key'] ?? null;

                $record = $modelClass && $key ? $modelClass::find($key) : null;

                if (!$record) {
                    return new \Illuminate\Support\HtmlString(
                        '<div class="p-4 text-center text-gray-500">Không tìm thấy dữ liệu</div>'
                    );
                }
                // Hiển thị chi tiết Assignment
                if ($record instanceof \App\Models\Assignment) {
                    $max_points = $record->max_points ? $record->max_points : 'Chưa xác định';
                    $statusLabel = $record->status ? $record->status->getDescription() : 'Chưa xác định';
                    $courseName = $record->courses->pluck('title')->first() ?? 'Chưa xác định';
                    return new \Illuminate\Support\HtmlString("
     <div class='p-6 bg-white rounded-lg shadow-md'>
    <h2 class='text-2xl font-bold text-gray-800 mb-2'>{$record->title}</h2>

    
    <p class='text-gray-700 mb-4'>{$record->description}</p>
     <div class='text-sm text-gray-500 mb-2'>
                <span class='font-semibold'>Môn học:</span> {$courseName}
            </div>
   <div class='text-sm text-gray-500 mb-4'>
        <span class='font-semibold'>Điểm tối đa:</span> {$max_points}
       
    </div>
      
        <!-- Nếu muốn thêm trạng thái -->
        <span class='inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium'>
           {$statusLabel}
        </span>
    </div>
</div>
    ");
                }
                // Hiển thị chi tiết Quiz
                if ($record instanceof \App\Models\Quiz) {
                    $statusLabel = $record->status ? $record->status->getDescription() : 'Chưa xác định';
                    $timeLimit = isset($record->time_limit_minutes) ? $record->time_limit_minutes . ' phút' : 'Chưa xác định';
                    $user = auth()->user();

                    // Lấy tất cả course liên quan đến Assignment hoặc Quiz
                    $courses = $record->courses;

                    // Lọc lại chỉ lấy các course mà user hiện tại tham gia (tồn tại trong course_user)
                    $userCourses = $courses->filter(function ($course) use ($user) {
                        return $course->users->contains($user->id);
                    });

                    $courseNames = $userCourses->pluck('title')->implode(', ');
                    $courseName = $courseNames ?: 'Chưa xác định';

                    return new \Illuminate\Support\HtmlString("
<div class='p-6 bg-white rounded-lg shadow-md'>
    <h2 class='text-2xl font-bold text-gray-800 mb-2'>{$record->title}</h2>

    
    <p class='text-gray-700 mb-4'>{$record->description}</p>
     <div class='text-sm text-gray-500 mb-2'>
                <span class='font-semibold'>Môn học:</span> {$courseName}
            </div>
   <div class='text-sm text-gray-500 mb-4'>
        <span class='font-semibold'>Time limit:</span>  {$timeLimit}
       
    </div>
    <div class='flex items-center space-x-2'>
      
        <span class='inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium'>
           {$statusLabel}
        </span>
    </div>
</div>

    ");
                }

                return null;
            })
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }
}
