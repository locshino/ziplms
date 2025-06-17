<?php

namespace App\Models;

use App\States\Status;
use Spatie\ModelStates\HasStates;

class CourseEnrollment extends Base\Model
{
    use HasStates;

    protected $casts = [
        'completed_at' => 'datetime',
        'status' => Status::class,
    ];

    protected $fillable = [
        'user_id',
        'course_id',
        'final_grade',
        'status',
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
