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
 * @property string $quiz_id
 * @property \Illuminate\Support\Carbon|null $start_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\Quiz $quiz
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseQuiz withoutTrashed()
 * @mixin \Eloquent
 */
class CourseQuiz extends Pivot implements Auditable
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
    protected $table = 'course_quizzes';

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
        'quiz_id',
        'start_at',
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
            'end_at' => 'datetime',
        ];
    }

    // Course relationship
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Quiz relationship
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}