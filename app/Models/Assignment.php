<?php

namespace App\Models;

use App\States\Status;
use Spatie\ModelStates\HasStates;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

class Assignment extends Base\Model
{
    use HasStates,
        HasTags,
        HasTranslations;

    protected $casts = [
        'title' => 'json',
        'instructions' => 'json',
        'due_date' => 'datetime',
        'allow_late_submissions' => 'boolean',
        'status' => Status::class,
    ];

    public array $translatable = [
        'title',
        'instructions',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
