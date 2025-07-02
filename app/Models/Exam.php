<?php

namespace App\Models;

use App\Enums\ExamShowResultsType;
use App\States\Status;
use Spatie\ModelStates\HasStates;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string|null $course_id
 * @property string|null $lecture_id
 * @property array<array-key, mixed> $title
 * @property array<array-key, mixed>|null $description
 * @property \Illuminate\Support\Carbon|null $start_time
 * @property \Illuminate\Support\Carbon|null $end_time
 * @property int|null $duration_minutes
 * @property int $max_attempts
 * @property string|null $passing_score
 * @property bool $shuffle_questions
 * @property bool $shuffle_answers
 * @property ExamShowResultsType $show_results_after
 * @property string|null $created_by
 * @property Status $status
 * @property string|null $results_visible_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamAttempt> $attempts
 * @property-read int|null $attempts_count
 * @property-read \App\Models\Course|null $course
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamQuestion> $examQuestions
 * @property-read int|null $exam_questions_count
 * @property-read \App\Models\Lecture|null $lecture
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
 * @property-read int|null $questions_count
 * @property-read mixed $translations
 *
 * @method static \Database\Factories\ExamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam orWhereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam orWhereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereLectureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereMaxAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam wherePassingScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereResultsVisibleAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereShowResultsAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereShuffleAnswers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereShuffleQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Exam extends Base\Model
{
    use HasStates,
        HasTranslations;

    protected $casts = [
        'title' => 'json',
        'description' => 'json',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'shuffle_questions' => 'boolean',
        'shuffle_answers' => 'boolean',
        'status' => Status::class,
        'show_results_after' => ExamShowResultsType::class,
    ];

    public array $translatable = [
        'title',
        'description',
    ];

    protected $fillable = [
        'course_id',
        'lecture_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'duration_minutes',
        'max_attempts',
        'passing_score',
        'shuffle_questions',
        'shuffle_answers',
        'show_results_after',
        'created_by',
        'status',
        'results_visible_at',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function questions()
    {
        // trung gian exam_questions
        return $this->belongsToMany(Question::class, 'exam_questions')
            ->withPivot('points', 'question_order')
            ->withTimestamps();
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
