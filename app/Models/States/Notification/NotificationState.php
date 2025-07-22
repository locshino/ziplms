<?php

namespace App\Models\States\Notification;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class NotificationState extends State
{
    abstract public function color(): string;

    abstract public function label(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Unread::class)
            ->allowTransition(Unread::class, Read::class)
            ->allowTransition(Read::class, Unread::class);
    }
}
