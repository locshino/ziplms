<?php

namespace App\Filament\Teacher\Resources;

use App\Filament\Teacher\Resources\AssignmentSubmissionResource\Pages;
use App\Models\AssignmentSubmission;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\States\SubmissionStatus\{Pending, Submitted, Graded};
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables\Actions\ViewAction;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
class AssignmentSubmissionResource extends Resource
{
    use Translatable;
    protected static ?string $model = AssignmentSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
SpatieMediaLibraryFileUpload::make('attachments')
    ->collection('submissions')
    ->multiple()
        
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->color(fn ($state) => $state::color())
                    ->icon(fn ($state) =>  $state::icon() )
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


            ])


            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Nộp bài')
                    ->icon('heroicon-o-pencil'),
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
