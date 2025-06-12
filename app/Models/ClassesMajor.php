<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassesMajor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes_majors';

    protected $casts = ['name' => 'json', 'description' => 'json'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function parent()
    {
        return $this->belongsTo(ClassesMajor::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ClassesMajor::class, 'parent_id');
    }

    public function enrollments()
    {
        return $this->hasMany(UserClassMajorEnrollment::class, 'class_major_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_class_major_enrollments', 'class_major_id', 'user_id');
    }

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }
}
