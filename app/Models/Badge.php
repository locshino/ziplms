<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string|null $organization_id
 * @property array<array-key, mixed> $name
 * @property array<array-key, mixed>|null $description
 * @property array<array-key, mixed>|null $criteria_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Organization|null $organization
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\BadgeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereCriteriaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Badge extends Base\Model implements HasMedia
{
    use HasTranslations,
        InteractsWithMedia;

    protected $casts = [
        'name' => 'json',
        'description' => 'json',
        'criteria_description' => 'json',
    ];

    public array $translatable = [
        'name',
        'description',
        'criteria_description',
    ];

    protected $fillable = [
        'name',
        'description',
        'criteria_description',
        'organization_id',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image_badge')->singleFile();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges');
    }
}
