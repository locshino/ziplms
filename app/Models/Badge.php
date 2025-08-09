<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Promethys\Revive\Concerns\Recyclable;

class Badge extends Model
{
    use HasFactory, HasUuids, Recyclable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'award_status',
    ];

    /**
     * Get the conditions for the badge.
     */
    public function conditions(): BelongsToMany
    {
        return $this->belongsToMany(BadgeCondition::class, 'badge_has_conditions', 'badge_id', 'condition_id');
    }

    /**
     * Get the user badges for the badge.
     */
    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    /**
     * Get the users who have earned this badge.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('awarded_at')
            ->withTimestamps();
    }
}
