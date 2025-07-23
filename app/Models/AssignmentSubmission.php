<?php

namespace App\Models;

use App\Enums\AssignmentType;
use App\Enums\AttachmentType;
use App\States\Status;
use App\States\SubmissionStatus\SubmissionStatus;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;

/**use App\States\SubmissionStatus\SubmissionStatus;

protected $casts = [
    'status' => SubmissionStatus::class,
];

 * @property string $id
 * @property string $assignment_id
 * @property string $user_id
 * @property string|null $submission_text
 * @property Status $status
 * @property string $submission_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Assignment $assignment
 * @property-read \App\Models\AssignmentGrade|null $grade
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\AssignmentSubmissionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission orWhereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission orWhereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereSubmissionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereSubmissionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentSubmission withoutTrashed()
 *
 * @mixin \Eloquent
 */
class AssignmentSubmission extends Base\Model implements HasMedia
{
    use HasStates,
        InteractsWithMedia;

    protected $casts = [
        'submitted_at' => 'datetime',
        'status' => SubmissionStatus::class,
    ];

    protected $fillable = [
        'assignment_id',
        'user_id',
        'submission_text',
        'submission_date',
        'status',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(AssignmentType::key())
            ->useDisk('public')
            ->acceptsMimeTypes(AttachmentType::values())
            ->withResponsiveImages();
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grade()
    {
        return $this->hasOne(AssignmentGrade::class, 'submission_id');
    }
}
