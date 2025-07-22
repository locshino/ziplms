<?php

namespace App\Models;

use App\Models\States\Notification\NotificationState;
use App\Models\States\Notification\Read;
use App\Models\States\Notification\Unread;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStates\HasStates;

class UserNotification extends Model
{
    use HasFactory, HasStates;

    protected $table = 'user_notifications';

    protected $fillable = [
        'user_id',
        'status',
        'title',
        'body',
        'data',
    ];

    protected $casts = [
        'status' => NotificationState::class, // Ép kiểu status sang lớp State
        'data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->whereState('status', Unread::class);
    }

    public function markAsRead(): void
    {
        if ($this->status->canTransitionTo(Read::class)) {
            $this->status->transitionTo(Read::class);
        }
    }

    public function markAsUnread(): void
    {
        if ($this->status->canTransitionTo(Unread::class)) {
            $this->status->transitionTo(Unread::class);
        }
    }
}
