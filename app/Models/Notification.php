<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $casts = ['title' => 'json', 'content' => 'json'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notifications', 'notification_id', 'user_id');
    }
}
