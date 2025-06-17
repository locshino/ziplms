<?php

namespace App\Models;

use App\Enums\ExamShowResultsType;
use App\States\Status;
use Spatie\ModelStates\HasStates;
use Spatie\Translatable\HasTranslations;

class Exam extends Base\Model
{
    use HasStates,
        HasTranslations;

    protected $casts = [
        'title' => 'json',
        'description' => 'json',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'shuffle_questions' => 'boolean',
        'shuffle_answers' => 'boolean',
        'status' => Status::class,
        'show_results_after' => ExamShowResultsType::class,
    ];

    public array $translatable = [
        'title',
        'description',
    ];

    protected $fillable = [
        'course_id',
        'lecture_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'duration_minutes',
        'max_attempts',
        'passing_score',
        'shuffle_questions',
        'shuffle_answers',
        'show_results_after',
        'created_by',
        'status',
        'results_visible_at',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
