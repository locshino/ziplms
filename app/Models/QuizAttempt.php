<?php

namespace App\Models;

use App\Enums\Status\QuizAttemptStatus;
use App\Enums\System\RoleSystem;
use App\Models\StudentQuizAnswer;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property string $id
 * @property string $quiz_id
 * @property string $student_id
 * @property numeric|null $points
 * @property array<array-key, mixed>|null $answers
 * @property \Illuminate\Support\Carbon|null $start_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property QuizAttemptStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Quiz $quiz
 * @property-read \App\Models\User $student
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentQuizAnswer> $studentAnswers
 * @property-read int|null $student_answers_count
 * @method static \Database\Factories\QuizAttemptFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttempt withoutTrashed()
 * @mixin \Eloquent
 */
class QuizAttempt extends Model implements AuditableContract
{
    use HasFactory, HasUuids, SoftDeletes, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quiz_id',
        'student_id',
        'points',
        'answers',
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
            'points' => 'decimal:2',
            'answers' => 'json',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'status' => QuizAttemptStatus::class,
        ];
    }

    // Quiz relationship
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    // Student relationship
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id')->role(RoleSystem::STUDENT);
    }

    // Student quiz answer relationships
    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentQuizAnswer::class);
    }

    // Accessor for score (alias for points)
    public function getScoreAttribute()
    {
        return $this->points;
    }

    // Accessor for completed_at (alias for end_at)
    public function getCompletedAtAttribute()
    {
        return $this->end_at;
    }

    // Alias relationship for answers
    public function answers(): HasMany
    {
        return $this->hasMany(StudentQuizAnswer::class, 'quiz_attempt_id');
    }
    
    // Alternative relationship name to avoid conflicts
    public function quizAnswers(): HasMany
    {
        return $this->hasMany(StudentQuizAnswer::class, 'quiz_attempt_id');
    }
}
