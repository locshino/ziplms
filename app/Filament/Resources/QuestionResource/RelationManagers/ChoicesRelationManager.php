<?php

namespace App\Filament\Resources\QuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ChoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'choices';
    protected static ?string $label = 'Lựa chọn trả lời';
    protected static ?string $pluralLabel = 'Các lựa chọn trả lời';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('choice_text')
                    ->label('Nội dung lựa chọn')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_correct')
                    ->label('Là đáp án đúng?'),
                Forms\Components\TextInput::make('choice_order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('choice_text')
            ->columns([
                Tables\Columns\TextColumn::make('choice_text')
                    ->label('Nội dung')
                    ->limit(60)
                    ->wrap()
                    ->getStateUsing(fn($record): ?string => $record->getTranslation('choice_text', app()->getLocale())),

                Tables\Columns\IconColumn::make('is_correct')
                    ->label('Đáp án đúng')
                    ->boolean(),

                Tables\Columns\TextColumn::make('choice_order')->label('Thứ tự')->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Thêm lựa chọn'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
