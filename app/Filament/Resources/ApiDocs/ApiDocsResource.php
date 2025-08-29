<?php

namespace App\Filament\Resources\ApiDocs;

use Filament\Support\Icons\Heroicon;
use ZPMLabs\FilamentApiDocsBuilder\Filament\Resources\ApiDocsResource\ApiDocsResource as FilamentApiDocsResource;

class ApiDocsResource extends FilamentApiDocsResource
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracket;

    public static function getNavigationGroup(): ?string
    {
        return __('resource_api_docs.resource.navigation_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('resource_api_docs.resource.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('resource_api_docs.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource_api_docs.resource.plural_label');
    }
}
