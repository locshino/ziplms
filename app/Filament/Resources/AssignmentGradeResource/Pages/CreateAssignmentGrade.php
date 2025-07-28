<?php

namespace App\Filament\Resources\AssignmentGradeResource\Pages;

use App\Filament\Resources\AssignmentGradeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssignmentGrade extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = AssignmentGradeResource::class;
}
