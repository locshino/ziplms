<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentGrade extends Model
{
    use HasFactory;

    protected $casts = ['feedback' => 'json', 'graded_at' => 'datetime'];

    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
