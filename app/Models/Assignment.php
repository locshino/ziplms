<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Promethys\Revive\Concerns\Recyclable;

class Assignment extends Model
{
    use HasFactory, HasUuids, SoftDeletes, Recyclable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'title',
        'instructions',
        'max_points',
        'late_penalty_percentage',
        'start_at',
        'due_at',
        'grading_at',
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
            'late_penalty_percentage' => 'decimal:2',
            'start_at' => 'datetime',
            'due_at' => 'datetime',
            'grading_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    /**
     * Get the course that owns the assignment.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the submissions for the assignment.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
