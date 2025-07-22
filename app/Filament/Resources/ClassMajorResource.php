<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ClassesMajorExporter;
use App\Filament\Resources\ClassMajorResource\Pages;
use App\Models\ClassesMajor;
use App\Repositories\Contracts\ClassesMajorRepositoryInterface;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Tags\Tag;

class ClassMajorResource extends Resource
{
    use Translatable;

    protected static ?string $model = ClassesMajor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getTreeLabel(): string
    {
        return 'name';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('organization_id')
                    ->relationship('organization', 'name')
                    ->label(__('class_major_lang.organization'))
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->label(__('class_major_lang.name'))
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label(__('class_major_lang.description')),

                Forms\Components\TextInput::make('code')
                    ->label(__('class_major_lang.code'))
                    ->required(),

                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->label(__('class_major_lang.parent'))
                    ->searchable(),

                Forms\Components\TagsInput::make('tags')
                    ->label(__('class_major_lang.tags'))
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
                Tables\Columns\TextColumn::make('name')
                    ->label(__('classes_major.columns.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('code')
                    ->label(__('classes_major.columns.code')),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label(__('classes_major.columns.parent.name')),

                Tables\Columns\TextColumn::make('organization.name')
                    ->label(__('classes_major.columns.organization.name')),
                TagsColumn::make('tags.name')
                    ->label(__('classes_major.columns.tags.name')),
            ])
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Lọc theo loại')
                    ->options(fn () => app(ClassesMajorRepositoryInterface::class)->getParentOptions())
                    ->query(function (Builder $query, array $data): Builder {
                        return app(ClassesMajorRepositoryInterface::class)->applyParentFilter($query, $data['value']);
                    }),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(ClassesMajorExporter::class),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getModelLabel(): string
    {
        return __('class_major_lang.Classes Majors');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassMajors::route('/'),
            'create' => Pages\CreateClassMajor::route('/create'),
            'edit' => Pages\EditClassMajor::route('/{record}/edit'),
            'view' => Pages\ViewClassMajor::route('/{record}'),
        ];
    }
}
