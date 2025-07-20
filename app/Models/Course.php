<?php

namespace App\Models;

use App\States\Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $staff
 * @property-read int|null $staff_count
 * // ... các PHPDoc khác
 * @mixin \Eloquent
 */
class Course extends Base\Model implements HasMedia
{
    use HasStates,
        HasTags,
        HasTranslations,
        InteractsWithMedia;

    protected $casts = [
        'name' => 'json',
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
        'code'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Course::class, 'parent_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lectures(): HasMany
    {
        return $this->hasMany(Lecture::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function staffAssignments(): HasMany
    {
        return $this->hasMany(CourseStaffAssignment::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_enrollments');
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_staff_assignments');
    }

    public function schedules(): MorphMany
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }
}