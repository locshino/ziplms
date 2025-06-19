<?php

namespace App\Models;

/**
 * @property string $id
 * @property string $schedulable_type
 * @property string $schedulable_id
 * @property array<array-key, mixed> $title
 * @property array<array-key, mixed>|null $description
 * @property string|null $assigned_teacher_id
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon $end_time
 * @property string|null $location_type
 * @property string|null $location_details
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $assignedTeacher
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $attendances
 * @property-read int|null $attendances_count
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $schedulable
 *
 * @method static \Database\Factories\ScheduleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereAssignedTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereLocationDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereLocationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereSchedulableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereSchedulableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Schedule extends Base\Model
{
    protected $casts = [
        'title' => 'json',
        'description' => 'json',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function schedulable()
    {
        return $this->morphTo();
    }

    public function assignedTeacher()
    {
        return $this->belongsTo(User::class, 'assigned_teacher_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
