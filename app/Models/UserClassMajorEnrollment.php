<?php

namespace App\Models;

use Spatie\Tags\HasTags;

/**
 * @property string $id
 * @property string $user_id
 * @property string $class_major_id
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\ClassesMajor $classMajor
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\UserClassMajorEnrollmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereClassMajorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserClassMajorEnrollment withoutTrashed()
 *
 * @mixin \Eloquent
 */
class UserClassMajorEnrollment extends Base\Model
{
    use HasTags;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $fillable = [
        'user_id',
        'class_major_id',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classMajor()
    {
        return $this->belongsTo(ClassesMajor::class, 'class_major_id');
    }
}
