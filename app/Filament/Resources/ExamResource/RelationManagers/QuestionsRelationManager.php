<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $recordTitleAttribute = 'question_text';

    // Updated to use translation with the correct key
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('exam-resource.relation_manager.questions.label');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question_text')
                    ->label(__('exam-resource.relation_manager.questions.column.question_content'))
                    ->limit(80)
                    ->wrap()
                    // This correctly gets the translation for the question itself
                    ->getStateUsing(fn($record): ?string => $record->getTranslation('question_text', app()->getLocale())),

                Tables\Columns\TextColumn::make('points')
                    ->label(__('exam-resource.relation_manager.questions.column.points'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('question_order')
                    ->label(__('exam-resource.relation_manager.questions.column.order'))
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->successNotificationTitle(__('exam-resource.relation_manager.questions.action.attach.notification_success'))
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('points')
                            ->label(__('exam-resource.relation_manager.questions.form.points'))
                            ->numeric()
                            ->required()
                            ->default(1.00),
                        Forms\Components\TextInput::make('question_order')
                            ->label(__('exam-resource.relation_manager.questions.form.order'))
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->validationMessages([
                                'min' => __('exam-resource.relation_manager.questions.validation.order_not_negative'),
                            ])
                            ->rules([
                                // FIXED: Inject the RelationManager's Livewire component instead of the Action.
                                function (RelationManager $livewire) {
                                    return function (string $attribute, $value, Closure $fail) use ($livewire) {
                                        $exists = $livewire->getOwnerRecord()->questions()->where('question_order', $value)->exists();
                                        if ($exists) {
                                            $fail(__('exam-resource.relation_manager.questions.validation.order_unique'));
                                        }
                                    };
                                },
                            ]),
                    ])
                    // This block generates a UUID for the pivot table's primary key.
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['id'] = Str::uuid()->toString();

                        return $data;
                    })
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotificationTitle(__('exam-resource.relation_manager.questions.action.edit.notification_success'))
                    ->form(function (Model $record) { // Pass the record to the form closure
                        return [
                            Forms\Components\TextInput::make('points')
                                ->label(__('exam-resource.relation_manager.questions.form.points'))
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('question_order')
                                ->label(__('exam-resource.relation_manager.questions.form.order'))
                                ->numeric()
                                ->required()
                                ->minValue(0)
                                ->validationMessages([
                                    'min' => __('exam-resource.relation_manager.questions.validation.order_not_negative'),
                                ])
                                ->rules([
                                    // Custom rule to check for uniqueness, ignoring the current record
                                    function (RelationManager $livewire) use ($record) {
                                        return function (string $attribute, $value, Closure $fail) use ($livewire, $record) {
                                            // In a BelongsToMany relation, pivot data is on the `pivot` property.
                                            $pivotId = $record->pivot->id;
                                            $query = $livewire->getOwnerRecord()->questions()
                                                ->where('question_order', $value)
                                                ->wherePivot('id', '!=', $pivotId);

                                            if ($query->exists()) {
                                                $fail(__('exam-resource.relation_manager.questions.validation.order_unique'));
                                            }
                                        };
                                    },
                                ]),
                        ];
                    }),

                Tables\Actions\DetachAction::make()
                    ->successNotificationTitle(__('exam-resource.relation_manager.questions.action.detach.notification_success')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->successNotificationTitle(__('exam-resource.relation_manager.questions.action.detach_bulk.notification_success')),
                ]),
            ]);
    }
}
