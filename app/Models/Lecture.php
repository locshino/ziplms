<?php

namespace App\Models;

use App\States\Status;
use Spatie\ModelStates\HasStates;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $course_id
 * @property array<array-key, mixed> $title
 * @property array<array-key, mixed>|null $description
 * @property int $lecture_order
 * @property string|null $duration_estimate
 * @property string|null $created_by
 * @property Status $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Exam> $exams
 * @property-read int|null $exams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LectureMaterial> $materials
 * @property-read int|null $materials_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read mixed $translations
 *
 * @method static \Database\Factories\LectureFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture orWhereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture orWhereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereDurationEstimate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereLectureOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lecture withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Lecture extends Base\Model
{
    use HasStates,
        HasTranslations;

    protected $casts = [
        'title' => 'json',
        'description' => 'json',
        'status' => Status::class,
    ];

    public $translatable = [
        'title',
        'description',
    ];

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'duration_estimate',
        'created_by',
        'status',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials()
    {
        return $this->hasMany(LectureMaterial::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }
}
