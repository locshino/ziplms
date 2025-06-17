<?php

namespace App\Models;

use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

class ClassesMajor extends Base\Model
{
    use HasTags,
        HasTranslations;

    protected $casts = [
        'name' => 'json',
        'description' => 'json',
    ];

    protected $fillable = [
        'organization_id',
        'name',
        'code',
        'description',
        'parent_id',
    ];

    public array $translatable = [
        'name',
        'description',
    ];

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
