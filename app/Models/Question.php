<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = ['question_text' => 'json', 'explanation' => 'json'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function choices()
    {
        return $this->hasMany(QuestionChoice::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_questions');
    }
}
