<?php

namespace App\Models;

use App\States\Exam\Status;
use Spatie\ModelStates\HasStates;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $exam_id
 * @property string $user_id
 * @property int $attempt_number
 * @property string|null $score
 * @property int|null $time_spent_seconds
 * @property array<array-key, mixed>|null $feedback
 * @property Status $status
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Exam $exam
 * @property-read mixed $translations
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\ExamAttemptFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt orWhereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt orWhereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereAttemptNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereTimeSpentSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAttempt withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ExamAttempt extends Base\Pivot
{
    use HasStates,
        HasTranslations;

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'feedback' => 'json',
        'status' => Status::class,
    ];

    public $translatable = [
        'feedback',
    ];

    protected $fillable = [
        'exam_id',
        'user_id',
        'attempt_number',
        'score',
        'time_spent_seconds',
        'feedback',
        'status',
        'started_at',
        'completed_at',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class, 'exam_attempt_id');
    }
}
