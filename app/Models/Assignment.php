<?php

namespace App\Models;

use App\States\Status;
use Spatie\ModelStates\HasStates;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;
use App\States\AssignmentStatus;

/**
 * @property string $id
 * @property string $course_id
 * @property array<array-key, mixed> $title
 * @property array<array-key, mixed>|null $instructions
 * @property string|null $max_score
 * @property bool $allow_late_submissions
 * @property string|null $created_by
 * @property Status $status
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\User|null $creator
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssignmentSubmission> $submissions
 * @property-read int|null $submissions_count
 * @property-read int|null $tags_count
 * @property-read mixed $translations
 *
 * @method static \Database\Factories\AssignmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment orWhereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment orWhereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereAllowLateSubmissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Assignment extends Base\Model
{
    use HasStates,
        HasTags,
        HasTranslations;

    protected $casts = [
        'title' => 'json',
        'instructions' => 'json',
        'due_date' => 'datetime',
        'allow_late_submissions' => 'boolean',
        'status' => AssignmentStatus::class,
    ];

    public array $translatable = [
        'title',
        'instructions',
    ];

    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function getTagsStringAttribute(): string
    {
        return $this->tags ? $this->tags->pluck('name')->join(', ') : '';
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }
}
