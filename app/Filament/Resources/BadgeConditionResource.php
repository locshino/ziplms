<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BadgeConditionResource\Pages;
use App\Models\BadgeCondition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class BadgeConditionResource extends Resource
{
    use CanImportExcelRecords;

    protected static ?string $model = BadgeCondition::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('badge_condition_resource.fields.name'))
                    ->required(),
                Forms\Components\TextInput::make('type')
                    ->label(__('badge_condition_resource.fields.type'))
                    ->required(),
                Forms\Components\TextInput::make('operator')
                    ->label(__('badge_condition_resource.fields.operator')),
                Forms\Components\Textarea::make('parameters')
                    ->label(__('badge_condition_resource.fields.parameters'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('badge_condition_resource.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('badge_condition_resource.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('badge_condition_resource.columns.type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('operator')
                    ->label(__('badge_condition_resource.columns.operator'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('badge_condition_resource.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('badge_condition_resource.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('badge_condition_resource.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->exports([
                        ExcelExport::make()
                            ->queue()
                            ->askForFilename()
                            ->askForWriterType(),
                    ]),
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
            'index' => Pages\ListBadgeConditions::route('/'),
            'create' => Pages\CreateBadgeCondition::route('/create'),
            'view' => Pages\ViewBadgeCondition::route('/{record}'),
            'edit' => Pages\EditBadgeCondition::route('/{record}/edit'),
        ];
    }
}
