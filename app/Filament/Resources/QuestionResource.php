<?php

namespace App\Filament\Resources;

use App\Enums\OrganizationType;
use App\Enums\QuestionType;
use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use FilamentTiptapEditor\TiptapEditor;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    public static function getNavigationGroup(): ?string
    {
        return __('question-resource.navigation.group');
    }

    public static function getModelLabel(): string
    {
        return __('question-resource.navigation.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('question-resource.navigation.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(3)->schema([
                Section::make(__('question-resource.form.section.question_details'))
                    ->columnSpan(2)
                    ->schema([
                        Textarea::make('question_text')
                            ->label(__('question-resource.form.field.question_text'))
                            ->required(),
                        TiptapEditor::make('explanation')
                            ->label(__('question-resource.form.field.explanation')),
                    ]),
                Section::make(__('question-resource.form.section.attributes'))
                    ->columnSpan(1)
                    ->schema([
                        Select::make('question_type_tag')
                            ->label(__('question-resource.form.field.question_type'))
                            ->required()
                            ->native(false)
                            ->options(
                                collect(QuestionType::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()])
                            )
                            ->loadStateFromRelationshipsUsing(function (Select $component, ?Question $record) {
                                if (! $record) {
                                    return;
                                }
                                $tag = $record->tagsWithType(QuestionType::key())->first();
                                if ($tag) {
                                    $component->state($tag->name);
                                }
                            })
                            ->saveRelationshipsUsing(function (Question $record, $state) {
                                if ($state) {
                                    $record->syncTagsWithType([$state], QuestionType::key());
                                }
                            }),

                        Select::make('organization_type_tag')
                            ->label(__('question-resource.form.field.classification_tags'))
                            ->required()
                            ->native(false)
                            ->options(
                                collect(OrganizationType::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()])
                            )
                            ->loadStateFromRelationshipsUsing(function (Select $component, ?Question $record) {
                                if (! $record) {
                                    return;
                                }
                                $tag = $record->tagsWithType(OrganizationType::key())->first();
                                if ($tag) {
                                    $component->state($tag->name);
                                }
                            })
                            ->saveRelationshipsUsing(function (Question $record, $state) {
                                if ($state) {
                                    $record->syncTagsWithType([$state], OrganizationType::key());
                                }
                            }),

                        Select::make('organization_id')
                            ->label(__('question-resource.form.field.organization'))
                            ->relationship('organization', 'name')
                            ->searchable()->preload()->required()->native(false),
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question_text')
                    ->label(__('question-resource.table.column.question_text'))
                    ->limit(70)
                    ->searchable(),
                SpatieTagsColumn::make('question_tags')
                    ->label(__('question-resource.table.column.question_type'))
                    ->type(QuestionType::key()),
                SpatieTagsColumn::make('organization_tags')
                    ->label(__('question-resource.table.column.organization_type'))
                    ->type(OrganizationType::key()),
                TextColumn::make('organization.name')
                    ->label(__('question-resource.table.column.organization'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label(__('question-resource.table.column.updated_at'))
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                // [SỬA LỖI] Viết lại toàn bộ khối filter
                SelectFilter::make('question_type')
                    ->label(__('question-resource.table.filter.question_type'))
                    ->multiple()
                    ->options(
                        collect(QuestionType::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()])->all()
                    )
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['values'],
                            fn(Builder $query, $tags) => $query->withAnyTags($tags, QuestionType::key())
                        );
                    }),
                SelectFilter::make('organization_type')
                    ->label(__('question-resource.table.filter.organization_type'))
                    ->multiple()
                    ->options(
                        collect(OrganizationType::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()])->all()
                    )
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['values'],
                            fn(Builder $query, $tags) => $query->withAnyTags($tags, OrganizationType::key())
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotificationTitle(__('question-resource.notification.update_success')),
                Tables\Actions\DeleteAction::make()
                    ->successNotificationTitle(__('question-resource.notification.delete_success')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChoicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
