<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LectureMaterialResource\Pages;
use App\Models\LectureMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LectureMaterialResource extends Resource
{
    protected static ?string $model = LectureMaterial::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('lecture-material-resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('lecture-material-resource.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('lecture-material-resource.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('lecture-material-resource.form.section'))
                    ->schema([
                        Forms\Components\Select::make('lecture_id')
                            ->relationship('lecture', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label(__('lecture-material-resource.form.lecture')),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label(__('lecture-material-resource.form.name')),

                        Forms\Components\RichEditor::make('description')
                            ->label(__('lecture-material-resource.form.description'))
                            ->columnSpanFull(),

                        Forms\Components\KeyValue::make('video_links')
                            ->label(__('lecture-material-resource.form.video_links'))
                            ->keyLabel(__('lecture-material-resource.form.video_links_key'))
                            ->valueLabel(__('lecture-material-resource.form.video_links_value')),

                        Forms\Components\SpatieMediaLibraryFileUpload::make('attachments')
                            ->collection('attachments')
                            ->multiple()
                            ->reorderable()
                            ->label(__('lecture-material-resource.form.attachments'))
                            ->openable()
                            ->downloadable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('lecture-material-resource.table.name'))
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('lecture.title')
                    ->label(__('lecture-material-resource.table.lecture'))
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\IconColumn::make('video_links')
                    ->label(__('lecture-material-resource.table.has_video'))
                    ->boolean()
                    ->trueIcon('heroicon-o-video-camera')
                    ->falseIcon('heroicon-o-no-symbol'),
                Tables\Columns\TextColumn::make('uploader.name')
                    ->label(__('lecture-material-resource.table.uploader'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('lecture-material-resource.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('lecture-material-resource.infolist.section'))
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('lecture-material-resource.form.name')),
                        Infolists\Components\TextEntry::make('lecture.title')
                            ->label(__('lecture-material-resource.form.lecture')),
                        Infolists\Components\TextEntry::make('description')
                            ->label(__('lecture-material-resource.form.description'))
                            ->html()
                            ->columnSpanFull(),
                        Infolists\Components\KeyValueEntry::make('video_links')
                            ->label(__('lecture-material-resource.infolist.video_links')),
                        Infolists\Components\TextEntry::make('uploader.name')
                            ->label(__('lecture-material-resource.infolist.uploader')),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label(__('lecture-material-resource.infolist.created_at'))
                            ->dateTime(),
                    ])->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLectureMaterials::route('/'),
            'create' => Pages\CreateLectureMaterial::route('/create'),
            'view' => Pages\ViewLectureMaterial::route('/{record}'),
            'edit' => Pages\EditLectureMaterial::route('/{record}/edit'),
        ];
    }
}