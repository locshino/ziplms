<?php

namespace App\Models;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Organization $organization
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\OrganizationUserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizationUser withoutTrashed()
 *
 * @mixin \Eloquent
 */
class OrganizationUser extends Base\Model
{
    protected $fillable = [
        'user_id',
        'organization_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
