<?php

namespace App\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Widget;
use \Guava\Calendar\Filament\CalendarWidget;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\FetchInfo;
use Guava\Calendar\Enums\CalendarViewType;
use App\Models\Quiz;
use App\Models\Course;
use Guava\Calendar\ValueObjects\CalendarEvent;
use App\Enums\Status\QuizStatus;
use Filament\Actions\Action;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Illuminate\Support\HtmlString;
use Filament\Tables\Concerns\InteractsWithTable;


class MyCalendarWidget extends CalendarWidget
{
    use HasWidgetShield;

    protected bool $eventClickEnabled = true;
    protected ?string $defaultEventClickAction = 'viewAssignment';





    protected function getEvents(FetchInfo $info): Collection|array|Builder
    {
        $now = now();
        $twoMonthsLater = now()->addMonths(1);



        $user = auth()->user();
        $role = $user->getRoleNames()->first();

        $query = Course::query();

        if ($role === 'student') {
            $query->whereHas('users', fn($q) => $q->where('users.id', $user->id));
        } else {
            $query->where('teacher_id', $user->id);
        }

        $courses = $query->with('quizzes')->get();



        $events = collect();

        foreach ($courses as $course) {
            foreach ($course->quizzes as $quiz) {
                if ($quiz->status !== QuizStatus::DRAFT && $quiz->pivot->end_at >= $now && $quiz->pivot->end_at <= $twoMonthsLater) {
                    $isUpcoming = $quiz->pivot->start_at > $now;
                    $key = $quiz->id . '-' . $course->id;
                    $events->push(
                        CalendarEvent::make($quiz)
                            ->title("Quiz:{$quiz->title} \n ({$course->title})")
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
                                "border-radius" => "4px",
                                "padding" => "6px 10px",

                            ])
                            ->action('viewAssignment')
                            ->model(Quiz::class)
                            ->key($quiz->getKey())
                    );
                }

            }
            foreach ($course->assignments as $assignment) {

                # code...
                if ($assignment->pivot->end_at >= $now && $assignment->pivot->end_at <= $twoMonthsLater) {
                    $isUpcoming = $assignment->pivot->start_at > $now;

                    $events->push(
                        CalendarEvent::make($assignment)
                            ->title("Assignment: {$assignment->title}\n({$course->title})")
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
                                "border-radius" => "4px",
                                "padding" => "6px 10px",
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

                if ($record instanceof \App\Models\Assignment) {
                    $max_points = $record->max_points ? $record->max_points : 'Chưa xác định';
                    $statusLabel = $record->status ? $record->status->getDescription() : 'Chưa xác định';
                    return new \Illuminate\Support\HtmlString("
     <div class='p-6 bg-white rounded-lg shadow-md'>
    <h2 class='text-2xl font-bold text-gray-800 mb-2'>{$record->title}</h2>

    
    <p class='text-gray-700 mb-4'>{$record->description}</p>
   <div class='text-sm text-gray-500 mb-4'>
        <span class='font-semibold'>Điểm tối đa:</span> {$max_points}
       
    </div>

    <div class='flex items-center space-x-2'>
      
        <!-- Nếu muốn thêm trạng thái -->
        <span class='inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium'>
           {$statusLabel}
        </span>
    </div>
</div>
    ");
                }

                if ($record instanceof \App\Models\Quiz) {
                    $statusLabel = $record->status ? $record->status->getDescription() : 'Chưa xác định';
                    $timeLimit = isset($record->time_limit_minutes) ? $record->time_limit_minutes . ' phút' : 'Chưa xác định';
                    return new \Illuminate\Support\HtmlString("
<div class='p-6 bg-white rounded-lg shadow-md'>
    <h2 class='text-2xl font-bold text-gray-800 mb-2'>{$record->title}</h2>

    
    <p class='text-gray-700 mb-4'>{$record->description}</p>
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
            ->modalCancelAction(false)

        ;
    }




}
