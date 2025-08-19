<?php

namespace App\Models;

use App\Enums\Status\BadgeConditionStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property string $id
 * @property string $title
 * @property string|null $description
 * @property string $condition_type
 * @property array<array-key, mixed>|null $condition_data
 * @property BadgeConditionStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\BadgeHasCondition|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Badge> $badges
 * @property-read int|null $badges_count
 * @method static \Database\Factories\BadgeConditionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition whereConditionData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition whereConditionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BadgeCondition withoutTrashed()
 * @mixin \Eloquent
 */
class BadgeCondition extends Model implements Auditable
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'condition_type',
        'condition_data',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'condition_data' => 'array',
            'status' => BadgeConditionStatus::class,
        ];
    }

    // Badge relationships
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'badge_has_conditions')
            ->using(BadgeHasCondition::class)
            ->withTimestamps();
    }
}
