<?php

namespace App\Models\States\Notification;

class Read extends NotificationState
{
    public static string $name = 'read';

    public function color(): string
    {
        return 'success';
    }

    public function label(): string
    {
        return __('Đã đọc');
    }
}
