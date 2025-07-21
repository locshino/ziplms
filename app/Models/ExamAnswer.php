<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $exam_attempt_id
 * @property string $exam_question_id
 * @property string $question_id
 * @property string|null $graded_by
 * @property string|null $selected_choice_id
 * @property array<array-key, mixed>|null $answer_text
 * @property array<array-key, mixed>|null $chosen_option_ids
 * @property string|null $points_earned
 * @property bool|null $is_correct
 * @property array<array-key, mixed>|null $teacher_feedback
 * @property \Illuminate\Support\Carbon|null $graded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\ExamAttempt $attempt
 * @property-read \App\Models\User|null $grader
 * @property-read \App\Models\Question $question
 * @property-read \App\Models\QuestionChoice|null $selectedChoice
 * @property-read mixed $translations
 */
class ExamAnswer extends Base\Model
{
    use HasTranslations;

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

    protected $casts = [
        'answer_text' => 'json',
        'teacher_feedback' => 'json',
        'chosen_option_ids' => 'json',
        'is_correct' => 'boolean',
        'graded_at' => 'datetime',
    ];

    public array $translatable = [
        'answer_text',
        'teacher_feedback',
    ];

    public function selectedChoice()
    {
        return $this->belongsTo(QuestionChoice::class, 'selected_choice_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
