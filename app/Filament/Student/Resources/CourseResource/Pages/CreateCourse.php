<?php

namespace App\Filament\Student\Resources\CourseResource\Pages;

use App\Filament\Student\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;
}
