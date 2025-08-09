<?php

namespace App\Filament\Resources\RoleResource\Concerns;

use App\Enums\Permissions\PermissionContextEnum;
use App\Enums\Permissions\PermissionNounEnum;
use App\Enums\Permissions\PermissionVerbEnum;
use App\Libs\Permissions\PermissionHelper;
use App\Models\Permission;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

trait HasShieldFormComponents
{
    public static function getShieldFormComponents(): Component
    {
        return Forms\Components\Tabs::make('Permissions')
            ->contained()
            ->tabs([
                static::getTabFormComponentForResources(),
                static::getTabFormComponentForPage(),
                static::getTabFormComponentForWidget(),
                static::getTabFormComponentForCustomPermissions(),
                static::getTabFormComponentForPermissionManagement(),
            ])
            ->columnSpan('full');
    }

    public static function getResourceEntitiesSchema(): ?array
    {
        return collect(FilamentShield::getResources())
            ->sortKeys()
            ->map(function ($entity) {
                $sectionLabel = strval(
                    static::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedResourceLabel($entity['fqcn'])
                    : $entity['model']
                );

                return Forms\Components\Section::make($sectionLabel)
                    ->description(fn () => new HtmlString('<span style="word-break: break-word;">'.Utils::showModelPath($entity['fqcn']).'</span>'))
                    ->compact()
                    ->schema([
                        static::getCheckBoxListComponentForResource($entity),
                    ])
                    ->columnSpan(static::shield()->getSectionColumnSpan())
                    ->collapsible();
            })
            ->toArray();
    }

    public static function getResourceTabBadgeCount(): ?int
    {
        return collect(FilamentShield::getResources())
            ->map(fn ($resource) => count(static::getResourcePermissionOptions($resource)))
            ->sum();
    }

    public static function getResourcePermissionOptions(array $entity): array
    {
        return collect(Utils::getResourcePermissionPrefixes($entity['fqcn']))
            ->flatMap(function ($permission) use ($entity) {
                $name = $permission.'_'.$entity['resource'];
                $label = static::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedResourcePermissionLabel($permission)
                    : $name;

                return [
                    $name => $label,
                ];
            })
            ->toArray();
    }

    public static function setPermissionStateForRecordPermissions(Component $component, string $operation, array $permissions, ?Model $record): void
    {
        if (in_array($operation, ['edit', 'view'])) {

            if (blank($record)) {
                return;
            }
            if ($component->isVisible() && count($permissions) > 0) {
                $component->state(
                    collect($permissions)
                        /** @phpstan-ignore-next-line */
                        ->filter(fn ($value, $key) => $record->checkPermissionTo($key))
                        ->keys()
                        ->toArray()
                );
            }
        }
    }

    public static function getPageOptions(): array
    {
        return collect(FilamentShield::getPages())
            ->flatMap(fn ($page) => [
                $page['permission'] => static::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedPageLabel($page['class'])
                    : $page['permission'],
            ])
            ->toArray();
    }

    public static function getWidgetOptions(): array
    {
        return collect(FilamentShield::getWidgets())
            ->flatMap(fn ($widget) => [
                $widget['permission'] => static::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedWidgetLabel($widget['class'])
                    : $widget['permission'],
            ])
            ->toArray();
    }

    public static function getCustomPermissionOptions(): ?array
    {
        return FilamentShield::getCustomPermissions()
            ->mapWithKeys(fn ($customPermission) => [
                $customPermission => static::shield()->hasLocalizedPermissionLabels() ? str($customPermission)->headline()->toString() : $customPermission,
            ])
            ->toArray();
    }

    public static function getTabFormComponentForResources(): Component
    {
        return static::shield()->hasSimpleResourcePermissionView()
            ? static::getTabFormComponentForSimpleResourcePermissionsView()
            : Forms\Components\Tabs\Tab::make('resources')
                ->label(__('filament-shield::filament-shield.resources'))
                ->visible(fn (): bool => (bool) Utils::isResourceEntityEnabled())
                ->badge(static::getResourceTabBadgeCount())
                ->schema([
                    Forms\Components\Grid::make()
                        ->schema(static::getResourceEntitiesSchema())
                        ->columns(static::shield()->getGridColumns()),
                ]);
    }

    public static function getCheckBoxListComponentForResource(array $entity): Component
    {
        $permissionsArray = static::getResourcePermissionOptions($entity);

        return static::getCheckboxListFormComponent(
            name: $entity['resource'],
            options: $permissionsArray,
            columns: static::shield()->getResourceCheckboxListColumns(),
            columnSpan: static::shield()->getResourceCheckboxListColumnSpan(),
            searchable: false
        );
    }

    public static function getTabFormComponentForPage(): Component
    {
        $options = static::getPageOptions();
        $count = count($options);

        return Forms\Components\Tabs\Tab::make('pages')
            ->label(__('filament-shield::filament-shield.pages'))
            ->visible(fn (): bool => (bool) Utils::isPageEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                static::getCheckboxListFormComponent(
                    name: 'pages_tab',
                    options: $options,
                ),
            ]);
    }

    public static function getTabFormComponentForWidget(): Component
    {
        $options = static::getWidgetOptions();
        $count = count($options);

        return Forms\Components\Tabs\Tab::make('widgets')
            ->label(__('filament-shield::filament-shield.widgets'))
            ->visible(fn (): bool => (bool) Utils::isWidgetEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                static::getCheckboxListFormComponent(
                    name: 'widgets_tab',
                    options: $options,
                ),
            ]);
    }

    public static function getTabFormComponentForCustomPermissions(): Component
    {
        $options = static::getCustomPermissionOptions();
        $count = count($options);

        return Forms\Components\Tabs\Tab::make('custom')
            ->label(__('filament-shield::filament-shield.custom'))
            ->visible(fn (): bool => (bool) Utils::isCustomPermissionEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                static::getCheckboxListFormComponent(
                    name: 'custom_permissions',
                    options: $options,
                ),
            ]);
    }

    public static function getTabFormComponentForSimpleResourcePermissionsView(): Component
    {
        $options = FilamentShield::getAllResourcePermissions();
        $count = count($options);

        return Forms\Components\Tabs\Tab::make('resources')
            ->label(__('filament-shield::filament-shield.resources'))
            ->visible(fn (): bool => (bool) Utils::isResourceEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                static::getCheckboxListFormComponent(
                    name: 'resources_tab',
                    options: $options,
                ),
            ]);
    }

    public static function getCheckboxListFormComponent(string $name, array $options, bool $searchable = true, array|int|string|null $columns = null, array|int|string|null $columnSpan = null): Component
    {
        return Forms\Components\CheckboxList::make($name)
            ->label('')
            ->options(fn (): array => $options)
            ->searchable($searchable)
            ->afterStateHydrated(
                fn (Component $component, string $operation, ?Model $record) => static::setPermissionStateForRecordPermissions(
                    component: $component,
                    operation: $operation,
                    permissions: $options,
                    record: $record
                )
            )
            ->dehydrated(fn ($state) => ! blank($state))
            ->bulkToggleable()
            ->gridDirection('row')
            ->columns($columns ?? static::shield()->getCheckboxListColumns())
            ->columnSpan($columnSpan ?? static::shield()->getCheckboxListColumnSpan());
    }

    public static function getTabFormComponentForPermissionManagement(): Component
    {
        return Forms\Components\Tabs\Tab::make('permission_management')
            ->label(__('role_resource.permission_management.tab_label'))
            ->icon('heroicon-o-plus-circle')
            ->schema([
                Forms\Components\Section::make(__('role_resource.permission_management.create_section.title'))
                    ->description(__('role_resource.permission_management.create_section.description'))
                    ->schema([
                        Forms\Components\Repeater::make('new_permissions')
                            ->label('')
                            ->schema([
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        Forms\Components\Select::make('verb')
                                            ->label(__('role_resource.permission_management.fields.verb'))
                                            ->options(PermissionVerbEnum::optionsWithLabels())
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => static::updatePermissionName($state, $get('noun'), $get('context'), $get('attribute_value'), $set)),

                                        Forms\Components\Select::make('noun')
                                            ->label(__('role_resource.permission_management.fields.noun'))
                                            ->options(PermissionNounEnum::optionsWithLabels())
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => static::updatePermissionName($get('verb'), $state, $get('context'), $get('attribute_value'), $set)),

                                        Forms\Components\Select::make('context')
                                            ->label(__('role_resource.permission_management.fields.context'))
                                            ->options(PermissionContextEnum::optionsWithLabels())
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => static::updatePermissionName($get('verb'), $get('noun'), $state, $get('attribute_value'), $set)),

                                        Forms\Components\TextInput::make('attribute_value')
                                            ->label(__('role_resource.permission_management.fields.attribute_value'))
                                            ->helperText(__('role_resource.permission_management.helpers.attribute_value'))
                                            ->visible(fn (callable $get) => in_array($get('context'), [PermissionContextEnum::ID->value, PermissionContextEnum::TAG->value]))
                                            ->required(fn (callable $get) => in_array($get('context'), [PermissionContextEnum::ID->value, PermissionContextEnum::TAG->value]))
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => static::updatePermissionName($get('verb'), $get('noun'), $get('context'), $state, $set)),
                                    ]),

                                Forms\Components\TextInput::make('permission_name')
                                    ->label(__('role_resource.permission_management.fields.permission_name'))
                                    ->disabled()
                                    ->helperText(__('role_resource.permission_management.helpers.permission_name')),

                                Forms\Components\TextInput::make('guard_name')
                                    ->label(__('role_resource.permission_management.fields.guard_name'))
                                    ->default('web')
                                    ->required(),
                            ])
                            ->addActionLabel(__('role_resource.permission_management.create_section.add_button'))
                            ->collapsible()
                            ->cloneable()
                            ->deleteAction(
                                fn (Forms\Components\Actions\Action $action) => $action
                                    ->requiresConfirmation()
                            )
                            ->dehydrated(false),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make(__('role_resource.permission_management.existing_section.title'))
                    ->description(__('role_resource.permission_management.existing_section.description'))
                    ->schema([
                        Forms\Components\CheckboxList::make('existing_custom_permissions')
                            ->label('')
                            ->options(fn () => static::getExistingCustomPermissions())
                            ->searchable()
                            ->bulkToggleable()
                            ->gridDirection('row')
                            ->columns(2)
                            ->dehydrated(false),
                    ])
                    ->collapsible(),

                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('save_permissions')
                        ->label(__('role_resource.permission_management.save_button'))
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function (array $data, $livewire) {
                            // Handle saving new permissions
                            if (isset($data['new_permissions']) && is_array($data['new_permissions'])) {
                                foreach ($data['new_permissions'] as $permissionData) {
                                    if (! empty($permissionData['permission_name'])) {
                                        Permission::firstOrCreate(
                                            ['name' => $permissionData['permission_name']],
                                            [
                                                'guard_name' => $permissionData['guard_name'] ?? 'web',
                                                'is_system' => false,
                                            ]
                                        );
                                    }
                                }
                            }

                            // Show success notification
                            Notification::make()
                                ->title(__('role_resource.permission_management.save_success'))
                                ->success()
                                ->send();

                            // Refresh the form to update existing permissions list
                            $livewire->form->fill($livewire->form->getState());
                        })
                        ->visible(fn (callable $get) => ! empty($get('new_permissions'))),
                ]),
            ]);
    }

    /**
     * Update permission name based on verb-noun-context pattern.
     */
    protected static function updatePermissionName(?string $verb, ?string $noun, ?string $context, ?string $attributeValue, callable $set): void
    {
        if (! $verb || ! $noun || ! $context) {
            $set('permission_name', '');

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
            $set('permission_name', $permissionName);
        } catch (\Exception $e) {
            $set('permission_name', 'Invalid combination');
        }
    }

    /**
     * Get existing custom permissions (non-system permissions).
     */
    protected static function getExistingCustomPermissions(): array
    {
        $permissionService = app(\App\Services\Interfaces\PermissionServiceInterface::class);

        return $permissionService->getExistingCustomPermissions()
            ->mapWithKeys(function ($permissionName) {
                return [$permissionName => static::formatPermissionLabel($permissionName)];
            })
            ->toArray();
    }

    /**
     * Format permission name into a human-readable label.
     */
    protected static function formatPermissionLabel(string $permissionName): string
    {
        // Check if there's a specific translation for this permission
        $translationKey = "permissions.{$permissionName}";
        $translation = __($translationKey);

        // If translation exists and is different from the key, use it
        if ($translation !== $translationKey) {
            return $translation;
        }

        // Otherwise, format the permission name to be more readable
        $parts = explode('_', $permissionName);

        // Capitalize each part and join with spaces
        $formatted = collect($parts)
            ->map(function ($part) {
                // Handle special cases for better readability
                return match (strtolower($part)) {
                    'id' => 'ID',
                    'api' => 'API',
                    'url' => 'URL',
                    'html' => 'HTML',
                    'css' => 'CSS',
                    'js' => 'JavaScript',
                    'pdf' => 'PDF',
                    'csv' => 'CSV',
                    'xml' => 'XML',
                    'json' => 'JSON',
                    default => ucfirst(strtolower($part))
                };
            })
            ->join(' ');

        return $formatted;
    }

    public static function shield(): FilamentShieldPlugin
    {
        return FilamentShieldPlugin::get();
    }
}
