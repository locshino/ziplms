<?php

namespace App\Models;

/**
 * @property string $id
 * @property string $user_id
 * @property string $badge_id
 * @property string $awarded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Badge $badge
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\UserBadgeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereAwardedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereBadgeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge withoutTrashed()
 *
 * @mixin \Eloquent
 */
class UserBadge extends Base\Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
