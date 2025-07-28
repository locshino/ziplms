<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use App\Models\Question;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $recordTitleAttribute = 'question_text';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('exam-resource.relation_manager.questions.label');
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('question_order', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('question_text')
                    ->label(__('exam-resource.relation_manager.questions.column.question_content'))
                    ->limit(80)
                    ->wrap()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('question_text->' . app()->getLocale(), 'like', "%{$search}%");
                    }),

                Tables\Columns\TextInputColumn::make('points')
                    ->label(__('exam-resource.relation_manager.questions.column.points'))
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),

                Tables\Columns\TextInputColumn::make('question_order')
                    ->label(__('exam-resource.relation_manager.questions.column.order'))
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('add_questions')
                    ->label(__('exam-resource.relation_manager.questions.action.attach.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        Forms\Components\Select::make('records')
                            ->label('Chọn câu hỏi')
                            ->multiple()
                            ->options(function (RelationManager $livewire) {
                                $questionTable = (new Question)->getTable();
                                $attachedIds = $livewire->getOwnerRecord()->questions()->pluck($questionTable . '.id')->all();

                                return Question::whereNotIn('id', $attachedIds)->pluck('question_text', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('points')
                            ->label(__('exam-resource.relation_manager.questions.form.points'))
                            ->numeric()
                            ->required()
                            ->default(1.00),
                    ])
                    ->action(function (RelationManager $livewire, array $data): void {
                        $relationship = $livewire->getRelationship();
                        $maxOrder = $relationship->max('question_order') ?? -1;
                        $orderCounter = $maxOrder + 1;

                        foreach ($data['records'] as $recordId) {
                            $relationship->attach($recordId, [
                                'points' => $data['points'],
                                'question_order' => $orderCounter,
                            ]);
                            $orderCounter++;
                        }

                        Notification::make()
                            ->title(__('exam-resource.relation_manager.questions.action.attach.notification_success'))
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form(function (Model $record) {
                        return [
                            Forms\Components\TextInput::make('points')
                                ->label(__('exam-resource.relation_manager.questions.form.points'))
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('question_order')
                                ->label(__('exam-resource.relation_manager.questions.form.order'))
                                ->numeric()
                                ->required()
                                ->minValue(0),
                        ];
                    }),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
