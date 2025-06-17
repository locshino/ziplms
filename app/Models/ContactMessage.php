<?php

namespace App\Models;


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
