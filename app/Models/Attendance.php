<?php

namespace App\Models;

class Attendance extends Base\Model
{
    protected $casts = ['notes' => 'json'];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marker()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
