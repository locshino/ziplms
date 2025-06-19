<?php

namespace App\Models;

/**
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
