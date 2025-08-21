<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property string $id
 * @property string $course_id
 * @property string $assignment_id
 * @property \Illuminate\Support\Carbon|null $start_at
 * @property \Illuminate\Support\Carbon|null $end_submission_at
 * @property \Illuminate\Support\Carbon|null $start_grading_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Assignment $assignment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Course $course
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment whereEndSubmissionAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment whereStartGradingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseAssignment withoutTrashed()
 * @mixin \Eloquent
 */
class CourseAssignment extends Pivot implements Auditable
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'course_assignments';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'assignment_id',
        'start_at',
        'end_submission_at',
        'start_grading_at',
        'end_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_submission_at' => 'datetime',
            'start_grading_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    // Course relationship
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Assignment relationship
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }
}