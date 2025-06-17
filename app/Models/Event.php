<?php

namespace App\Models;

class Event extends Base\Model
{
    protected $casts = [
        'title' => 'json',
        'description' => 'json',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
