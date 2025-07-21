<?php

namespace App\Models;

use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $organization_id
 * @property array<array-key, mixed> $name
 * @property string|null $code
 * @property array<array-key, mixed>|null $description
 * @property string|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ClassesMajor> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserClassMajorEnrollment> $enrollments
 * @property-read int|null $enrollments_count
 * @property-read \App\Models\Organization $organization
 * @property-read ClassesMajor|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read mixed $translations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\ClassesMajorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassesMajor withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ClassesMajor extends Base\Model
{
    use HasTags,
        HasTranslations;

    protected $casts = [
        'name' => 'json',
        'description' => 'json',
    ];

    protected $fillable = [
        'organization_id',
        'name',
        'code',
        'description',
        'parent_id',
    ];

    public array $translatable = [
        'name',
        'description',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function parent()
    {
        return $this->belongsTo(ClassesMajor::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ClassesMajor::class, 'parent_id');
    }

    public function enrollments()
    {
        return $this->hasMany(UserClassMajorEnrollment::class, 'class_major_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_class_major_enrollments', 'class_major_id', 'user_id');
    }

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }
    
}
