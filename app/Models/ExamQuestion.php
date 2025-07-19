<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

/**
 * This is the dedicated Pivot Model for the exam_questions table.
 * It automatically handles UUID generation for the primary key.
 *
 * @property string $id
 * @property string $exam_id
 * @property string $question_id
 * @property string $points
 * @property int $question_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Exam $exam
 * @property-read \App\Models\Question $question
 *
 * @method static \Database\Factories\ExamQuestionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereQuestionOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ExamQuestion extends Pivot
{
    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'exam_questions';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'exam_id',
        'question_id',
        'question_order',
        'points',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Listen for the "creating" event on the pivot model.
         * This will automatically generate a UUID for the 'id' field
         * before a new record is inserted into the pivot table.
         */
        static::creating(function ($model) {
            // This is a safer way to check and set the primary key,
            // avoiding the TypeError caused by using empty() on a magic property.
            if (! $model->getAttribute($model->getKeyName())) {
                $model->setAttribute($model->getKeyName(), Str::uuid()->toString());
            }
        });
    }

    /**
     * Get the exam that owns the pivot record.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the question that owns the pivot record.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
