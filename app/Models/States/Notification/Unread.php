<?php

namespace App\Models\States\Notification;

class Unread extends NotificationState
{
    public static string $name = 'unread';

    public function color(): string
    {
        return 'warning';
    }

    public function label(): string
    {
        return __('Chưa đọc');
    }
}
