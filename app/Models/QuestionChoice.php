<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionChoice extends Model
{
    use HasFactory;

    protected $casts = ['choice_text' => 'json', 'is_correct' => 'boolean'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
