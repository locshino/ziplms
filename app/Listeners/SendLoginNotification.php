<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Filament\Notifications\Notification;

class SendLoginNotification
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
    public function handle(Login $event): void
    {
        $user = auth()->user();
        Notification::make()
            ->title('Đăng nhập thành công')
            ->body('Xin chào ' . $user->name . '!')
            ->success()
            ->send();
    }
}
