<?php

namespace App\Models;

class Schedule extends Base\Model
{
    protected $casts = [
        'title' => 'json',
        'description' => 'json',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function schedulable()
    {
        return $this->morphTo();
    }

    public function assignedTeacher()
    {
        return $this->belongsTo(User::class, 'assigned_teacher_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
