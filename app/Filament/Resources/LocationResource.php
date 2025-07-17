<?php

namespace App\Filament\Resources;

use Afsakar\LeafletMapPicker\LeafletMapPicker;
use App\Enums\LocationType;
use App\Filament\Forms\Actions\ViewOnMapAction;
use App\Filament\Resources\LocationResource\Pages;
use App\Models\Location;
use App\States\Location\LocationStatus;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationResource extends Resource
{
    use Translatable;

    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $recordTitleAttribute = 'name';

    // Use translation keys for model labels
    public static function getModelLabel(): string
    {
        return __('location-resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('location-resource.model_label_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('location-resource.form.section.main'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('location-resource.form.name'))
                                            ->required(),

                                        Forms\Components\Textarea::make('address')
                                            ->label(__('location-resource.form.address')),

                                        Forms\Components\Select::make('status')
                                            ->label(__('location-resource.form.status'))
                                            ->options(LocationStatus::getOptions())
                                            ->required()
                                            ->native(false),

                                        SpatieTagsInput::make('tags')
                                            ->label(__('location-resource.form.tags'))
                                            ->type(LocationType::key()),
                                    ]),

                                Forms\Components\Group::make()
                                    ->schema([
                                        // Field for a single cover image, now clickable
                                        SpatieMediaLibraryFileUpload::make('location_cover_image')
                                            ->collection('location_cover_image')
                                            ->image()
                                            ->imageEditor() // Make image clickable to open editor/viewer
                                            ->label(__('location-resource.form.location_image')),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make(__('location-resource.form.section.map'))
                    ->schema([
                        LeafletMapPicker::make('locate')
                            ->label(__('location-resource.form.pin_location'))
                            ->myLocationButtonLabel(__('location-resource.form.my_location_button'))
                            ->required()
                            ->defaultLocation([21.027870, 105.852290])
                            ->defaultZoom(6),

                        Forms\Components\Actions::make([
                            ViewOnMapAction::make('locate'),
                        ]),
                    ]),

                Forms\Components\Section::make(__('location-resource.form.location_gallery_image'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('location_gallery_image')
                            ->collection('location_gallery_image')
                            ->multiple()
                            ->reorderable() // Allow reordering images
                            ->panelLayout('grid') // Display images in a compact grid
                            ->imagePreviewHeight('150') // Set a smaller preview height
                            ->imageEditor() // Make images clickable to open editor/viewer
                            ->label(__('location-resource.form.location_gallery_image')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('location_image')
                    ->collection('location_image')
                    ->label(__('location-resource.table.location_image')),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('location-resource.form.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('address')
                    ->label(__('location-resource.form.address'))
                    ->searchable()
                    ->sortable()
                    ->tooltip(fn (?string $state): ?string => $state),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('location-resource.form.status'))
                    ->badge(),

                SpatieTagsColumn::make('tags')
                    ->label(__('location-resource.form.tags'))
                    ->type(LocationType::key())
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('location-resource.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('location-resource.table.updated_at'))
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('location-resource.filters.status'))
                    ->options(LocationStatus::getOptions())
                    ->native(false),

                Tables\Filters\SelectFilter::make('tags')
                    ->label(__('location-resource.filters.tags'))
                    ->multiple()
                    ->options(LocationType::options())
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['values'],
                            fn (Builder $query, $tags) => $query->withAnyTags($tags, LocationType::key())
                        );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                //
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
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'view' => Pages\ViewLocation::route('/{record}'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
