<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;
use Spatie\Tags\HasTags;

class Question extends Base\Model
{
    use HasTags,
        HasTranslations;

    protected $casts = [
        'question_text' => 'json',
        'explanation' => 'json',
    ];

    public $translatable = [
        'question_text',
        'explanation',
    ];

    protected $fillable = [
        'organization_id',
        'question_text',
        'explanation',
        'created_by',
    ];

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
