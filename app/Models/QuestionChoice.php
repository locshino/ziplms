<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $question_id
 * @property array<array-key, mixed> $choice_text
 * @property bool $is_correct
 * @property int $choice_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Question $question
 * @property-read mixed $translations
 *
 * @method static \Database\Factories\QuestionChoiceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereChoiceOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereChoiceText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionChoice withoutTrashed()
 *
 * @mixin \Eloquent
 */
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
