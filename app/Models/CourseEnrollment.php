<?php

namespace App\Models;

use App\States\Course\CourseStatus;
use Spatie\ModelStates\HasStates;

/**
 * @property string $id
 * @property string $user_id
 * @property string $course_id
 * @property string|null $final_grade
 * @property CourseStatus $status
 * @property string $enrollment_date
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\CourseEnrollmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment orWhereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment orWhereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereEnrollmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereFinalGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseEnrollment withoutTrashed()
 *
 * @mixin \Eloquent
 */
class CourseEnrollment extends Base\Model
{
    use HasStates;

    protected $casts = [
        'completed_at' => 'datetime',
        'status' => CourseStatus::class,
    ];

    protected $fillable = [
        'user_id',
        'course_id',
        'final_grade',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
