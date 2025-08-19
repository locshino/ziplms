<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Resources\Assignments\AssignmentResource;
use Filament\Actions\CreateAction;
use Filament\Actions\AttachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class AssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assignments';

    protected static ?string $relatedResource = AssignmentResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
                AttachAction::make()
                    ->multiple()
            ]);
    }
}
