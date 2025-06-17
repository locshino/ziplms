<?php

namespace App\Models;

use App\States\Status;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;
use Spatie\Translatable\HasTranslations;

class Course extends Base\Model
{
    use HasStates,
        HasTranslations,
        InteractsWithMedia; // For image_path

    protected $casts = [
        'description' => 'json',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => Status::class,
    ];

    public array $translatable = [
        'name',
        'description',
    ];

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'organization_id',
        'parent_id',
        'created_by',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function parent()
    {
        return $this->belongsTo(Course::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Course::class, 'parent_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function staffAssignments()
    {
        return $this->hasMany(CourseStaffAssignment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_enrollments');
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'course_staff_assignments');
    }

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }
}
