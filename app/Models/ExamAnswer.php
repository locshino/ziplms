<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $exam_attempt_id
 * @property string $exam_question_id
 * @property string $question_id
 * @property string|null $graded_by
 * @property string|null $selected_choice_id
 * @property array<array-key, mixed>|null $answer_text
 * @property array<array-key, mixed>|null $chosen_option_ids
 * @property string|null $points_earned
 * @property bool|null $is_correct
 * @property array<array-key, mixed>|null $teacher_feedback
 * @property \Illuminate\Support\Carbon|null $graded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\ExamAttempt $examAttempt
 * @property-read \App\Models\ExamQuestion $examQuestion
 * @property-read \App\Models\User|null $grader
 * @property-read \App\Models\Question $question
 * @property-read \App\Models\QuestionChoice|null $selectedChoice
 * @property-read mixed $translations
 *
 * @method static \Database\Factories\ExamAnswerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereAnswerText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereChosenOptionIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereExamAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereExamQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereGradedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereGradedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer wherePointsEarned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereSelectedChoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereTeacherFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamAnswer withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ExamAnswer extends Base\Model
{
    use HasTranslations;

    protected $casts = [
        'chosen_option_ids' => 'json',
        'is_correct' => 'boolean',
        'teacher_feedback' => 'json',
        'graded_at' => 'datetime',
    ];

    public $translatable = [
        'answer_text',
        'teacher_feedback',
    ];

    protected $fillable = [
        'exam_attempt_id',
        'exam_question_id',
        'question_id',
        'graded_by',
        'selected_choice_id',
        'answer_text',
        'chosen_option_ids',
        'points_earned',
        'is_correct',
        'teacher_feedback',
        'graded_at',
    ];

    public function examAttempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    public function examQuestion()
    {
        return $this->belongsTo(ExamQuestion::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedChoice()
    {
        return $this->belongsTo(QuestionChoice::class, 'selected_choice_id');
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
