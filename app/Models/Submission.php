<?php

namespace App\Models;

use App\Enums\MimeType;
use App\Enums\Status\SubmissionStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $id
 * @property string $assignment_id
 * @property string $student_id
 * @property string|null $content
 * @property SubmissionStatus $status
 * @property \Illuminate\Support\Carbon|null $submitted_at
 * @property string|null $graded_by
 * @property numeric|null $points
 * @property string|null $feedback
 * @property \Illuminate\Support\Carbon|null $graded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Assignment $assignment
 * @property-read \App\Models\User|null $grader
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $student
 *
 * @method static \Database\Factories\SubmissionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereGradedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereGradedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Submission extends Model implements HasMedia
{
    use HasFactory, HasUuids, InteractsWithMedia, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assignment_id',
        'student_id',
        'content',
        'status',
        'graded_by',
        'points',
        'feedback',
        'submitted_at',
        'graded_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => SubmissionStatus::class,
            'points' => 'decimal:2',
            'submitted_at' => 'datetime',
            'graded_at' => 'datetime',
        ];
    }

    // Assignment relationship
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    // Student relationship
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Grader relationship
    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    // Media collections
    public function registerMediaCollections(): void
    {
        $acceptsMimeTypesOfDocuments = [
            ...MimeType::images(),
            ...MimeType::documents(),
            ...MimeType::archives(),
        ];

        $this->addMediaCollection('submission_documents')
            ->acceptsMimeTypes($acceptsMimeTypesOfDocuments);

        $this->addMediaCollection('submission_feedback_documents')
            ->acceptsMimeTypes($acceptsMimeTypesOfDocuments);
    }
}
