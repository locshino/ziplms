<?php

namespace App\Models;

use Spatie\Tags\HasTags;

class CourseStaffAssignment extends Base\Model
{
    use HasTags;

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'course_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
