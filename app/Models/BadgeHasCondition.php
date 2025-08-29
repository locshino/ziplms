<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property string $id
 * @property string $badge_id
 * @property string $badge_condition_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Badge $badge
 * @property-read \App\Models\BadgeCondition $badgeCondition
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition whereBadgeConditionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition whereBadgeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeHasCondition withoutTrashed()
 *
 * @mixin \Eloquent
 */
class BadgeHasCondition extends Pivot implements Auditable
{
    use HasFactory,
        HasUuids,
        \OwenIt\Auditing\Auditable,
        SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'badge_has_conditions';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'badge_id',
        'badge_condition_id',
        'status',
    ];

    // Badge relationship
    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }

    // Badge condition relationship
    public function badgeCondition(): BelongsTo
    {
        return $this->belongsTo(BadgeCondition::class);
    }
}
