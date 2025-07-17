<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $recordTitleAttribute = 'question_text';

    protected static ?string $label = 'Questions in the exam';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question_text')
                    ->label('Question Content')
                    ->limit(80)
                    ->wrap()
                    ->getStateUsing(fn($record): ?string => $record->getTranslation('question_text', app()->getLocale())),

                Tables\Columns\TextColumn::make('points')->label('Points')->sortable(),
                Tables\Columns\TextColumn::make('question_order')->label('Order')->sortable(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->successNotificationTitle('Câu hỏi đã được thêm vào bài thi.')
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('points')->label('Điểm')->numeric()->required()->default(1.00),
                        Forms\Components\TextInput::make('question_order')->label('Thứ tự')->numeric()->default(0),
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
                    ->successNotificationTitle('Question information has been updated.')
                    ->form([
                        Forms\Components\TextInput::make('points')
                            ->label('Points for this question')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('question_order')
                            ->label('Question order')
                            ->numeric(),
                    ]),

                Tables\Actions\DetachAction::make()
                    ->successNotificationTitle('Question has been removed from the exam.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->successNotificationTitle('Selected questions have been removed from the exam.'),
                ]),
            ]);
    }
}
