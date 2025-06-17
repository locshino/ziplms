<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

class QuestionChoice extends Base\Model
{
    use HasTranslations;

    protected $casts = [
        'choice_text' => 'json',
        'is_correct' => 'boolean',
    ];

    public $translatable = [
        'choice_text',
    ];

    protected $fillable = [
        'question_id',
        'choice_text',
        'is_correct',
        'choice_order',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
