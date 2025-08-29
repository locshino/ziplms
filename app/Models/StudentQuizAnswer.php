<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $quiz_attempt_id
 * @property string $question_id
 * @property string|null $answer_choice_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\AnswerChoice|null $answerChoice
 * @property-read \App\Models\Question $question
 * @property-read \App\Models\QuizAttempt $quizAttempt
 *
 * @method static \Database\Factories\StudentQuizAnswerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer whereAnswerChoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer whereQuizAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentQuizAnswer withoutTrashed()
 *
 * @mixin \Eloquent
 */
class StudentQuizAnswer extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'answer_choice_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    // Quiz attempt relationship
    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    // Question relationship
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    // Answer choice relationship
    public function answerChoice(): BelongsTo
    {
        return $this->belongsTo(AnswerChoice::class);
    }
}
