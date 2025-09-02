<?php

namespace App\Models;

use App\Enums\Status\QuizStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\Tags\HasTags;

/**
 * @property string $id
 * @property string $title
 * @property string|null $description
 * @property int|null $max_attempts
 * @property bool $is_single_session
 * @property int|null $time_limit_minutes
 * @property QuizStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuizAttempt> $attempts
 * @property-read int|null $attempts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\QuizQuestion|\App\Models\CourseQuiz|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
 * @property-read int|null $questions_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 *
 * @method static \Database\Factories\QuizFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereIsSingleSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereMaxAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereTimeLimitMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Quiz extends Model implements AuditableContract
{
    use Auditable, HasFactory, HasTags, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'max_attempts',
        'is_single_session',
        'time_limit_minutes',
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
            'max_attempts' => 'integer',
            'is_single_session' => 'boolean',
            'time_limit_minutes' => 'integer',
            'status' => QuizStatus::class,
        ];
    }

    // Course relationships
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_quizzes')
            ->using(CourseQuiz::class)
            ->withPivot('id', 'start_at', 'end_at')
            ->withTimestamps();
    }

    // Single course relationship (for backward compatibility)
    public function course()
    {
        return $this->courses()->limit(1);
    }

    // Get the first course ID (for backward compatibility)
    public function getCourseIdAttribute()
    {
        $firstCourse = $this->courses()->first();

        return $firstCourse ? $firstCourse->id : null;
    }

    // Question relationships
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')
            ->using(QuizQuestion::class)
            ->withPivot('id', 'points')
            ->withTimestamps();
    }

    // Quiz attempt relationships
    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get name attribute (alias for title)
     */
    public function getNameAttribute()
    {
        return $this->title;
    }

    /**
     * Get max points calculated from quiz questions
     */
    public function getMaxPointsAttribute()
    {
        return $this->questions()->sum('quiz_questions.points') ?: 10.00;
    }

    /**
     * Distribute points evenly among questions (default 10 total)
     */
    public function distributePointsEvenly($totalPoints = 10.00)
    {
        $questionCount = $this->questions()->count();
        if ($questionCount > 0) {
            $pointsPerQuestion = round($totalPoints / $questionCount, 2);

            // Update all quiz questions with distributed points
            foreach ($this->questions as $question) {
                $this->questions()->updateExistingPivot($question->id, ['points' => $pointsPerQuestion]);
            }
        }
    }

    /**
     * Initialize quiz questions with default points distribution
     */
    public function initializeQuestionPoints()
    {
        $questionCount = $this->questions()->count();
        if ($questionCount > 0) {
            $pointsPerQuestion = round(10.00 / $questionCount, 2);

            // Set default points for questions that don't have points set
            foreach ($this->questions as $question) {
                if ($question->pivot->points == 1.00) { // Default value from migration
                    $this->questions()->updateExistingPivot($question->id, ['points' => $pointsPerQuestion]);
                }
            }
        }
    }

    /**
     * Check if quiz is currently active
     */
    public function getIsActiveAttribute(): bool
    {
        // Quiz must be published to be active
        if ($this->status !== QuizStatus::PUBLISHED) {
            return false;
        }

        // If quiz has no course associations, it's not active
        if ($this->courses()->count() === 0) {
            return false;
        }

        // Check if any course has valid timing for this quiz
        $now = now();
        foreach ($this->courses as $course) {
            $courseQuiz = $course->pivot;
            $startAt = $courseQuiz->start_at;
            $endAt = $courseQuiz->end_at;

            // If no timing restrictions, quiz is active
            if (! $startAt && ! $endAt) {
                return true;
            }

            // Check if current time is within the allowed period
            $isAfterStart = ! $startAt || $now->gte($startAt);
            $isBeforeEnd = ! $endAt || $now->lte($endAt);

            if ($isAfterStart && $isBeforeEnd) {
                return true;
            }
        }

        return false;
    }
}
