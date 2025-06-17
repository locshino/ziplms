<?php

namespace App\Models;

class ExamQuestion extends Base\Model
{
    protected $fillable = [
        'exam_id',
        'question_id',
        'question_order',
        'points',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
