<?php

namespace App\Models;

/**
 * @property string $id
 * @property string $schedule_id
 * @property string $user_id
 * @property string $attended_at
 * @property array<array-key, mixed>|null $notes
 * @property string|null $marked_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $marker
 * @property-read \App\Models\Schedule $schedule
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\AttendanceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereAttendedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereMarkedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Attendance extends Base\Model
{
    protected $casts = ['notes' => 'json'];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marker()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
