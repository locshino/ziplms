<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamAnswer extends Model
{
    use HasFactory;

    protected $casts = ['chosen_option_ids' => 'json', 'is_correct' => 'boolean', 'teacher_feedback' => 'json', 'graded_at' => 'datetime'];

    public function examAttempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    public function examQuestion()
    {
        return $this->belongsTo(ExamQuestion::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedChoice()
    {
        return $this->belongsTo(QuestionChoice::class, 'selected_choice_id');
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
