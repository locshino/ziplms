<?php

namespace App\Models;

use App\Enums\MimeType;
use App\Enums\Status\CourseStatus;
use App\Enums\System\RoleSystem;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

/**
 * @property string $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property numeric|null $price
 * @property bool $is_featured
 * @property string $teacher_id
 * @property \Illuminate\Support\Carbon|null $start_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property CourseStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\CourseUser|\App\Models\CourseQuiz|\App\Models\CourseAssignment|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $teacher
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\CourseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Course extends Model implements Auditable, Eventable, HasMedia
{
    use HasFactory,
        HasSlug,
        HasTags,
        HasUuids,
        InteractsWithMedia,
        \OwenIt\Auditing\Auditable,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'is_featured',
        'teacher_id',
        'start_at',
        'end_at',
        'status',
    ];

    /**
     * The Eager Loading paths.
     *
     * @var array
     */
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
        'pivotAttached',
        'pivotDetached',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'status' => CourseStatus::class,
        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Teacher relationship
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id')->role(RoleSystem::TEACHER);
    }

    // Enrolled users relationship
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
            ->using(CourseUser::class)
            ->withPivot('id', 'start_at', 'end_at')
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }

    /**
     * Get only the students associated with the course.
     * This reuses the main users() relationship and filters by role.
     */
    public function students(): BelongsToMany
    {
        // Assumes the role name is 'student'
        return $this->users()->role(RoleSystem::STUDENT);
    }

    /**
     * Get only the managers associated with the course.
     * This reuses the main users() relationship and filters by role.
     */
    public function managers(): BelongsToMany
    {
        // Assumes the role name is 'manager'
        return $this->users()->role(RoleSystem::MANAGER);
    }

    // Assignment relationships
    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(Assignment::class, 'course_assignments')
            ->using(CourseAssignment::class)
            ->withPivot('id', 'start_at', 'end_submission_at', 'start_grading_at', 'end_at')
            ->withTimestamps();
    }

    // Quiz relationships
    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'course_quizzes')
            ->using(CourseQuiz::class)
            ->withPivot('id', 'start_at', 'end_at')
            ->withTimestamps();
    }

    // Media collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('course_cover')
            ->singleFile()
            ->acceptsMimeTypes(MimeType::images());

        $this->addMediaCollection('course_documents')
            ->acceptsMimeTypes([
                ...MimeType::documents(),
                ...MimeType::images(),
                ...MimeType::archives(),
            ]);
    }

    public function toCalendarEvent(): CalendarEvent
    {

        return CalendarEvent::make($this)
            ->title($this->title)
            ->start($this->start_at)
            ->end($this->end_at);
    }
}
