<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Promethys\Revive\Concerns\Recyclable;

class Quiz extends Model
{
    use HasFactory, HasUuids, Recyclable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'max_points',
        'max_attempts',
        'is_single_session',
        'time_limit_minutes',
        'start_at',
        'end_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'max_points' => 'decimal:2',
            'max_attempts' => 'integer',
            'is_single_session' => 'boolean',
            'time_limit_minutes' => 'integer',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    /**
     * Get the course that owns the quiz.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the questions for the quiz.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the quiz attempts for the quiz.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Determine if the quiz is currently active.
     */
    public function getIsActiveAttribute(): bool
    {
        $now = now();

        // If no start time is set, consider it active
        if (! $this->start_at) {
            return ! $this->end_at || $now->lte($this->end_at);
        }

        // If no end time is set, check if it has started
        if (! $this->end_at) {
            return $now->gte($this->start_at);
        }

        // Check if current time is between start and end
        return $now->gte($this->start_at) && $now->lte($this->end_at);
    }

    /**
     * Determine if the quiz is published and available.
     */
    public function getIsPublishedAttribute(): bool
    {
        // A quiz is published if it's active (within time bounds)
        return $this->is_active;
    }
}
