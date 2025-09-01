<?php

namespace App\Listeners;

use App\Events\AssignmentGraded;
use App\Notifications\AssignmentGradedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendGradingNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AssignmentGraded $event): void
    {
        $student = $event->submission->student;

        // Gửi notification nếu tìm thấy sinh viên
        if ($student) {
            $student->notify(new AssignmentGradedNotification($event->submission));
        }
    }
}
