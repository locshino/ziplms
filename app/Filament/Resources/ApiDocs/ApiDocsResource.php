<?php

namespace App\Filament\Resources\ApiDocs;

use Filament\Support\Icons\Heroicon;
use ZPMLabs\FilamentApiDocsBuilder\Filament\Resources\ApiDocsResource\ApiDocsResource as FilamentApiDocsResource;

class ApiDocsResource extends FilamentApiDocsResource
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracket;
    public static function getNavigationGroup(): ?string
    {
        return "System";
    }
}
