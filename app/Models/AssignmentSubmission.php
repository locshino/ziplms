<?php

namespace App\Models;

use App\Enums\AssignmentType;
use App\Enums\AttachmentType;
use App\States\Status;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;

class AssignmentSubmission extends Base\Model implements HasMedia
{
    use HasStates,
        InteractsWithMedia;

    protected $casts = [
        'submitted_at' => 'datetime',
        'status' => Status::class,
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
