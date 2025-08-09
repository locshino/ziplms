<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Models\Permission;
use App\Services\PermissionService;
use App\Libs\Permissions\PermissionHelper;
use App\Enums\Permissions\PermissionVerbEnum;
use App\Enums\Permissions\PermissionNounEnum;
use App\Enums\Permissions\PermissionContextEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class PermissionResource extends Resource
{
    use CanImportExcelRecords;

    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 12;

    /**
     * Get the Eloquent query builder for the resource.
     * Only show non-system permissions.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_system', false);
    }

    /**
     * Update permission name based on verb-noun-context pattern.
     */
    protected static function updatePermissionName(?string $verb, ?string $noun, ?string $context, ?string $attributeValue, callable $set): void
    {
        if (! $verb || ! $noun || ! $context) {
            $set('name', '');
            return;
        }

        try {
            $builder = PermissionHelper::make();

            // Set verb
            $verbEnum = PermissionVerbEnum::from($verb);
            $builder->verb($verbEnum);

            // Set noun
            $nounEnum = PermissionNounEnum::from($noun);
            $builder->noun($nounEnum);

            // Set context
            $contextEnum = PermissionContextEnum::from($context);
            $builder->context($contextEnum);

            // Add attribute value if needed
            if (in_array($context, [PermissionContextEnum::ID->value, PermissionContextEnum::TAG->value]) && $attributeValue) {
                $builder->withAttribute($attributeValue);
            }

            $permissionName = $builder->build();
            $set('name', $permissionName);
        } catch (\Exception $e) {
            $set('name', 'Invalid combination');
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Permission Builder')
                    ->description('Build permission following verb-noun-context pattern')
                    ->schema([
                        Forms\Components\Select::make('verb')
                            ->label('Verb')
                            ->options(PermissionVerbEnum::optionsWithLabels())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => self::updatePermissionName($state, $get('noun'), $get('context'), $get('attribute_value'), $set)),

                        Forms\Components\Select::make('noun')
                            ->label('Noun')
                            ->options(PermissionNounEnum::optionsWithLabels())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => self::updatePermissionName($get('verb'), $state, $get('context'), $get('attribute_value'), $set)),

                        Forms\Components\Select::make('context')
                            ->label('Context')
                            ->options(PermissionContextEnum::optionsWithLabels())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => self::updatePermissionName($get('verb'), $get('noun'), $state, $get('attribute_value'), $set)),

                        Forms\Components\TextInput::make('attribute_value')
                            ->label('Attribute Value')
                            ->helperText('Required when context is ID or Tag')
                            ->visible(fn (callable $get) => in_array($get('context'), [PermissionContextEnum::ID->value, PermissionContextEnum::TAG->value]))
                            ->required(fn (callable $get) => in_array($get('context'), [PermissionContextEnum::ID->value, PermissionContextEnum::TAG->value]))
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => self::updatePermissionName($get('verb'), $get('noun'), $get('context'), $state, $set)),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Generated Permission')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Permission Name')
                            ->helperText('Auto-generated from verb-noun-context pattern')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('guard_name')
                            ->label('Guard Name')
                            ->default('web')
                            ->required(),

                        Forms\Components\Hidden::make('is_system')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('permission_resource.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('permission_resource.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('guard_name')
                    ->label(__('permission_resource.columns.guard_name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('permission_resource.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('permission_resource.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('permission_resource.columns.deleted_at'))
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'view' => Pages\ViewPermission::route('/{record}'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
