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
 * @property string $quiz_id
 * @property string $question_id
 * @property numeric $points
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Question $question
 * @property-read \App\Models\Quiz $quiz
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizQuestion withoutTrashed()
 * @mixin \Eloquent
 */
class QuizQuestion extends Pivot implements Auditable
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
    protected $table = 'quiz_questions';

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
        'quiz_id',
        'question_id',
        'points',
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
        ];
    }

    // Quiz relationship
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    // Question relationship
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
