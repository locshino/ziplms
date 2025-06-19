<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $submission_id
 * @property string|null $grade
 * @property array<array-key, mixed>|null $feedback
 * @property string|null $graded_by
 * @property \Illuminate\Support\Carbon|null $graded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $grader
 * @property-read \App\Models\AssignmentSubmission $submission
 * @property-read mixed $translations
 *
 * @method static \Database\Factories\AssignmentGradeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereGradedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereGradedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentGrade withoutTrashed()
 *
 * @mixin \Eloquent
 */
class AssignmentGrade extends Base\Model
{
    use HasTranslations;

    protected $casts = [
        'feedback' => 'json',
        'graded_at' => 'datetime',
    ];

    public array $translatable = [
        'feedback',
    ];

    protected $fillable = [
        'submission_id',
        'graded_by',
        'grade',
        'feedback',
        'graded_at',
    ];

    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
