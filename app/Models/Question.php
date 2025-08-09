<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Promethys\Revive\Concerns\Recyclable;

class Question extends Model
{
    use HasFactory, HasUuids, Recyclable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quiz_id',
        'title',
        'points',
        'is_multiple_response',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points' => 'decimal:2',
            'is_multiple_response' => 'boolean',
        ];
    }

    /**
     * Get the quiz that owns the question.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the answer choices for the question.
     */
    public function answerChoices(): HasMany
    {
        return $this->hasMany(AnswerChoice::class);
    }

    /**
     * Get the student quiz answers for the question.
     */
    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentQuizAnswer::class);
    }
}
