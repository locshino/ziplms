<?php

namespace App\Models;

use App\States\Status;
use Spatie\ModelStates\HasStates;
use Spatie\Translatable\HasTranslations;

class Lecture extends Base\Model
{
    use HasStates,
        HasTranslations;

    protected $casts = [
        'title' => 'json',
        'description' => 'json',
        'status' => Status::class,
    ];

    public $translatable = [
        'title',
        'description',
    ];

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'duration_estimate',
        'created_by',
        'status'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials()
    {
        return $this->hasMany(LectureMaterial::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }
}
