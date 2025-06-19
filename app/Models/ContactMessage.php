<?php

namespace App\Models;

/**
 * @property string $id
 * @property string|null $sender_id
 * @property string|null $receiver_id
 * @property array<array-key, mixed>|null $subject
 * @property string $message
 * @property string $sent_at
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $receiver
 * @property-read \App\Models\User|null $sender
 *
 * @method static \Database\Factories\ContactMessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ContactMessage extends Base\Model
{
    protected $casts = [
        'subject' => 'json',
        'read_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
