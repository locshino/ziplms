<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = ['name' => 'json', 'description' => 'json', 'start_date' => 'date', 'end_date' => 'date'];

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
