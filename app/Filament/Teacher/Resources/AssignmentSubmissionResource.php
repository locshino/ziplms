<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\AssignmentSubmissionResource\Pages;
use App\Models\AssignmentSubmission;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Filament\Teacher\Resources\AssignmentSubmissionResource\Pages\CreateAssignmentSubmission;
use App\Filament\Teacher\Resources\AssignmentSubmissionResource\Pages\EditAssignmentSubmission;

class AssignmentSubmissionResource extends Resource
{
    use Translatable;

    protected static ?string $model = AssignmentSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Section::make('Tệp bài nộp')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('attachments')
                        ->collection('submissions')
                        ->disabled(), 
                ]),

            Section::make('Chấm điểm')
                ->schema([
                    TextInput::make('grade.grade')
                        ->label('Điểm số')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(10)
                        ->step(0.1)
                        ->required(),

                    Textarea::make('grade.feedback')
                        ->label('Nhận xét')
                        ->rows(4),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->color(fn ($state) => $state::color())
                    ->icon(fn ($state) => $state::icon())
                    ->formatStateUsing(fn ($state) => $state::label()),

                TextColumn::make('media.first.file_name')
                    ->label('File bài nộp')
                    ->url(fn ($record) => $record->getFirstMediaUrl('submissions'))
                    ->openUrlInNewTab(),
                TextColumn::make('user.name')
                    ->label('Người nộp')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('assignment.title')
                    ->label('Bài tập')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('grade.grade')
                    ->label('Điểm số')
                    ->sortable()
                    ->numeric(decimalPlaces: 2)
                    ->alignRight(),
                TextColumn::make('grade.feedback')
                    ->label('Đánh giá')
                    ->sortable()
                    ->limit(50),
                TextColumn::make('created_at')
                    ->label('Ngày nộp')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('media.first.file_name')
    ->label('Tệp')
    // ->formatStateUsing(fn ($state) => 'Tải xuống')
    // ->url(fn ($record) => $record->getFirstMediaUrl('submissions'))
    // ->openUrlInNewTab()
    // ->icon('heroicon-o-arrow-down-tray'),

            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
    ->label('Comment')
    ->color('primary') 
    ->icon('heroicon-m-pencil-square')
    ->button() ,
                ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);

    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssignmentSubmissions::route('/'),
            'create' => Pages\CreateAssignmentSubmission::route('/create'),
            'edit' => Pages\EditAssignmentSubmission::route('/{record}/edit'),
            'view' => Pages\ViewAssignmentSubmission::route('/{record}'),
        ];
    }
}
