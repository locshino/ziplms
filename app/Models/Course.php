<?php

namespace App\Models;

use App\Enums\MimeType;
use App\Enums\Status\CourseStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Course extends Model implements HasMedia, Auditable
{
    use HasFactory,
        HasTags,
        HasUuids,
        InteractsWithMedia,
        SoftDeletes,
        \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'is_featured',
        'teacher_id',
        'start_at',
        'end_at',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'status' => CourseStatus::class,
        ];
    }

    // Teacher relationship
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
            ->using(CourseUser::class)
            ->withPivot('id', 'start_at', 'end_at')
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }

    // Assignment relationships
    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(Assignment::class, 'course_assignments')
            ->using(CourseAssignment::class)
            ->withPivot('id', 'start_at', 'end_submission_at', 'start_grading_at', 'end_at')
            ->withTimestamps();
    }

    /**
     * FIX: Thêm mối quan hệ này để sửa lỗi BadMethodCallException.
     * Mối quan hệ này cho phép truy vấn các bản ghi pivot 'course_assignments'
     * trực tiếp từ một đối tượng Course.
     */
    public function courseAssignments(): HasMany
    {
        return $this->hasMany(CourseAssignment::class);
    }

    // Quiz relationships
    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'course_quizzes')
            ->using(CourseQuiz::class)
            ->withPivot('id', 'start_at', 'end_at')
            ->withTimestamps();
    }

    // Media collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('course_cover')
            ->singleFile()
            ->acceptsMimeTypes(MimeType::images());

        $this->addMediaCollection('course_documents')
            ->acceptsMimeTypes([
                ...MimeType::documents(),
                ...MimeType::images(),
                ...MimeType::archives(),
            ]);
    }
    
}
