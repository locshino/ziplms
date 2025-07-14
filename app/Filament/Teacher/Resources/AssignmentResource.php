<?php

namespace App\Filament\Teacher\Resources;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Teacher\Resources\AssignmentResource\Pages;
use App\Filament\Teacher\Resources\AssignmentResource\RelationManagers;
use App\Models\Assignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Spatie\Tags\Tag;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\BadgeColumn;



class AssignmentResource extends Resource
{
    use Translatable;
    protected static ?string $model = Assignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Forms\Components\Select::make('course_id')
                ->label('Khóa học')
                ->relationship('course', 'name')
                ->required(),

            Forms\Components\TextInput::make('title')
                ->label('Tiêu đề')
                ->required(),

            Forms\Components\Textarea::make('instructions')
                ->label('Hướng dẫn')
                ->required(),

            Forms\Components\TextInput::make('max_score')
                ->numeric()
                ->default(0)->minValue(0)->maxValue(100),

            Forms\Components\DateTimePicker::make('due_date')
                ->label('Hạn nộp'),

            Forms\Components\Toggle::make('allow_late_submissions')
                ->label('Cho phép nộp trễ'),

            Forms\Components\Select::make('status')
    ->label('Trạng thái')
    ->options([
                    'active' => 'Tích cực',
                    'inactive' => 'Không hoạt động',
    ])
    ->required(),
                
           

Forms\Components\TagsInput::make('tags')
    ->label('Thẻ')
    ->suggestions(Tag::pluck('name')->toArray()) 
    ->saveRelationshipsUsing(function ($record, $state) {
        $record->syncTags($state); 
    }),

            ]);
    }


public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('title')->label('Tiêu đề')->limit(50)->searchable(),
        TextColumn::make('course.name')->label('Khóa học'),
        TextColumn::make('max_score')->label('Điểm'),
        TextColumn::make('due_date')->label('Hạn nộp')->dateTime(),
        BooleanColumn::make('allow_late_submissions')->label('Cho phép trễ'),
        TextColumn::make('creator.name')->label('Người tạo')->searchable(),
        TagsColumn::make('tags_string')->label('Thẻ'),
       BadgeColumn::make('status')
    ->label('Trạng thái')
    ->color(fn ($state) => $state::color()) 
    ->formatStateUsing(fn ($state) => $state::label())
        ])

        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAssignments::route('/'),
            'create' => Pages\CreateAssignment::route('/create'),
            'edit' => Pages\EditAssignment::route('/{record}/edit'),
        ];
    }
}
