<?php

namespace App\Filament\Teacher\Resources\AssignmentSubmissionResource\Pages;

use App\Filament\Teacher\Resources\AssignmentSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssignmentSubmission extends EditRecord
{
    protected static string $resource = AssignmentSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
   public function getTitle(): string
    {
        return 'Nộp bài tập';
    }
    protected function getFormActions(): array {
return [
    Actions\Action:: make('submit')
->label('Nộp bài')
->action('save')
->color('primary'),
];
}


}
