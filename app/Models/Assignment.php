<?php

namespace App\Models;

use App\Enums\MimeType;
use App\Enums\Status\AssignmentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Assignment extends Model implements HasMedia, Auditable
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
        'description',
        'max_points',
        'max_attempts',
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
            'max_points' => 'decimal:2',
            'max_attempts' => 'integer',
            'status' => AssignmentStatus::class,
        ];
    }

    /**
     * Mối quan hệ nhiều-nhiều với Course.
     * Một bài tập có thể được sử dụng trong nhiều khóa học.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_assignments')
            ->using(CourseAssignment::class) // Sử dụng Pivot Model tùy chỉnh
            ->withPivot('id', 'start_at', 'end_submission_at', 'start_grading_at', 'end_at')
            ->withTimestamps();
    }
    

    /**
     * Một bài tập có nhiều bài nộp.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Đăng ký media collection cho các tài liệu đính kèm của bài tập.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('assignment_documents')->acceptsMimeTypes([
            ...MimeType::documents(),
            ...MimeType::images(),
            ...MimeType::archives(),
        ]);
    }
}
