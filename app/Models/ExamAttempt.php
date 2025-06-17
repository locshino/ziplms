<?php

namespace App\Models;

use App\States\Status;
use Spatie\ModelStates\HasStates;
use Spatie\Translatable\HasTranslations;

class ExamAttempt extends Base\Model
{
    use HasStates,
        HasTranslations;

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'feedback' => 'json',
        'status' => Status::class,
    ];

    public $translatable = [
        'feedback',
    ];

    protected $fillable = [
        'exam_id',
        'user_id',
        'attempt_number',
        'score',
        'time_spent_seconds',
        'feedback',
        'status',
        'started_at',
        'completed_at',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
