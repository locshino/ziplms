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
 * @property string $course_id
 * @property string $user_id
 * @property \Illuminate\Support\Carbon|null $start_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseUser withoutTrashed()
 *
 * @mixin \Eloquent
 */
class CourseUser extends Pivot implements Auditable
{
    use HasFactory,
        // SoftDeletes,
        \OwenIt\Auditing\Auditable,
        HasUuids;

    protected $table = 'course_user';

    public $incrementing = false;

    protected $fillable = [
        'course_id',
        'user_id',
        'start_at',
        'end_at',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
