<?php

namespace App\Models;

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
 * @property string $question_id
 * @property string $title
 * @property string|null $description
 * @property bool $is_correct
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Question $question
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentQuizAnswer> $studentAnswers
 * @property-read int|null $student_answers_count
 *
 * @method static \Database\Factories\AnswerChoiceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AnswerChoice withoutTrashed()
 *
 * @mixin \Eloquent
 */
class AnswerChoice extends Model implements AuditableContract
{
    use Auditable, HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question_id',
        'title',
        'description',
        'is_correct',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    // Question relationship
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    // Student quiz answer relationships
    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentQuizAnswer::class);
    }
}
