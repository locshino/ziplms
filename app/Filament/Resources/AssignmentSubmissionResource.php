<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssignmentSubmissionResource\Pages;
use App\Models\AssignmentSubmission;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// use Filament\Tables\Filters\SelectFilter;

class AssignmentSubmissionResource extends Resource
{
    use Translatable;

    protected static ?string $model = AssignmentSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('grade.grade')
                    ->label(__('assignment_submission.form.grade'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10)
                    ->required(),

                Textarea::make('feedback')
                    ->label(__('assignment_submission.form.feedback'))
                    ->rows(4)
                    ->maxLength(1000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('user.name')->label(__('assignment_submission.fields.user_name'))
                    ->visible(fn() => !Auth::user()?->hasRole('student')),

                Tables\Columns\TextColumn::make('assignment.title')->label(__('assignment_submission.fields.assignment_title')),
                Tables\Columns\TextColumn::make('grade.grade')->label(__('assignment_submission.fields.grade')),
                Tables\Columns\TextColumn::make('feedback')->label(__('assignment_submission.fields.feedback'))->limit(20),
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('assignment_submission.fields.status'))
                    ->color(fn($state) => $state::color())
                    ->formatStateUsing(fn($state) => $state::label()),

            ])

            ->filters([

            ])
            ->actions([
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (Auth::user()?->hasRole('teacher')) {
            $query->whereHas('assignment', function ($query) {
                $query->where('created_by', Auth::id());
            });
        }
        if (Auth::user()?->hasRole('student')) {
            $query->where('user_id', Auth::id());
        }

        $query->select('assignment_submissions.*')
            ->joinSub(
                DB::table('assignment_submissions as sub2')
                    ->selectRaw('MAX(id) as ids')
                    ->groupBy('user_id', 'assignment_id'),
                'latest_submissions',
                'assignment_submissions.id',
                '=',
                'latest_submissions.ids'

            );

        return $query;
    }

    public static function getNavigationLabel(): string
    {
        return __('assignment_submission.label.plural');
    }

    public static function getModelLabel(): string
    {
        return __('assignment_submission.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('assignment_submission.label.plural');
    }
    public static function canCreate(): bool
    {
        return false;
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
