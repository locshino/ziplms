<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

class ExamAnswer extends Base\Model
{
    use HasTranslations;

    protected $casts = [
        'chosen_option_ids' => 'json',
        'is_correct' => 'boolean',
        'teacher_feedback' => 'json',
        'graded_at' => 'datetime',
    ];

    public $translatable = [
        'answer_text',
        'teacher_feedback',
    ];

    protected $fillable = [
        'exam_attempt_id',
        'exam_question_id',
        'question_id',
        'graded_by',
        'selected_choice_id',
        'answer_text',
        'chosen_option_ids',
        'points_earned',
        'is_correct',
        'teacher_feedback',
        'graded_at',
    ];

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
