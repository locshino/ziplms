<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamResource\Pages;
use App\Filament\Resources\ExamResource\RelationManagers;
use App\Models\Exam;
use App\States\Exam\Active;
use App\States\Exam\Inactive;
use App\States\Exam\Status;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExamResource extends Resource
{
    use Translatable;

    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function getTranslatableLocales(): array
    {
        return ['vi', 'en'];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('exam-resource.navigation.group');
    }

    public static function getModelLabel(): string
    {
        return __('exam-resource.navigation.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('exam-resource.navigation.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(3)->schema([
                // CỘT TRÁI (2/3) - NỘI DUNG CHÍNH
                Forms\Components\Section::make(__('exam-resource.form.section.main_content'))
                    ->columnSpan(2)
                    ->schema([

                        // XÓA TABS, DÙNG INPUT TRỰC TIẾP
                        Forms\Components\TextInput::make('title') // <-- KHÔNG CẦN .vi HAY .en
                            ->label(__('exam-resource.form.field.title'))
                            ->required(),

                        Forms\Components\RichEditor::make('description') // <-- KHÔNG CẦN .vi HAY .en
                            ->label(__('exam-resource.form.field.description')),

                        Forms\Components\Grid::make()->schema([
                            Forms\Components\Select::make('course_id')->relationship('course', 'name')->searchable()->preload()->label(__('exam-resource.form.field.course')),
                            Forms\Components\Select::make('lecture_id')->relationship('lecture', 'title')->searchable()->preload()->label(__('exam-resource.form.field.lecture')),
                        ]),
                    ]),

                // CỘT PHẢI (1/3) - CÁC CÀI ĐẶT
                Forms\Components\Section::make(__('exam-resource.form.section.settings'))
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label(__('exam-resource.form.field.status'))
                            ->options(Status::getOptionsForSelect([Active::class, Inactive::class]))
                            ->required()->native(false),
                        Forms\Components\DateTimePicker::make('start_time')->label(__('exam-resource.form.field.start_time')),
                        Forms\Components\DateTimePicker::make('end_time')->label(__('exam-resource.form.field.end_time')),
                        Forms\Components\TextInput::make('duration_minutes')->label(__('exam-resource.form.field.duration'))->numeric()->required()->default(60),
                        Forms\Components\TextInput::make('max_attempts')->label(__('exam-resource.form.field.max_attempts'))->numeric()->required()->default(1),
                        Forms\Components\TextInput::make('passing_score')->label(__('exam-resource.form.field.passing_score'))->numeric()->required()->default(50),
                        Forms\Components\Select::make('show_results_after')->label(__('exam-resource.form.field.show_results'))->options(\App\Enums\ExamShowResultsType::options())->required()->native(false),
                        Forms\Components\Toggle::make('shuffle_questions')->label(__('exam-resource.form.field.shuffle_questions')),
                        Forms\Components\Toggle::make('shuffle_answers')->label(__('exam-resource.form.field.shuffle_answers')),
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('exam-resource.table.column.title'))
                    ->limit(40)
                    ->getStateUsing(fn($record): ?string => $record->getTranslation('title', app()->getLocale()))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('title->' . app()->getLocale(), 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('course.name')->label(__('exam-resource.table.column.course'))->sortable(),
                Tables\Columns\TextColumn::make('status')->label(__('exam-resource.table.column.status'))->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->label(__('exam-resource.form.field.course'))
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Action::make('take')
                    ->label(__('exam-resource.table.action.take_exam'))
                    ->icon('heroicon-o-pencil-square')
                    ->color('success')
                    ->url(fn(Exam $record): string => static::getUrl('take', ['record' => $record]))
                    ->visible(fn(Exam $record): bool => $record->status instanceof Active),

                ActionGroup::make([

                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
            'take' => Pages\TakeExam::route('/{record}/take'),
        ];
    }
}
