<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

class AssignmentGrade extends Base\Model
{
    use HasTranslations;

    protected $casts = [
        'feedback' => 'json',
        'graded_at' => 'datetime',
    ];

    public array $translatable = [
        'feedback',
    ];

    protected $fillable = [
        'submission_id',
        'graded_by',
        'grade',
        'feedback',
        'graded_at',
    ];

    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
