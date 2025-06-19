<?php

namespace App\Models;

use Spatie\Tags\HasTags;

/**
 * @property string $id
 * @property string $user_id
 * @property string $course_id
 * @property \Illuminate\Support\Carbon $assigned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Course $course
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\CourseStaffAssignmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereAssignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseStaffAssignment withoutTrashed()
 *
 * @mixin \Eloquent
 */
class CourseStaffAssignment extends Base\Model
{
    use HasTags;

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'course_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
