<?php

namespace App\Filament\Resources\Submissions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SubmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('resource_submission.infolist.entries.id')),
                TextEntry::make('assignment.title')
                    ->label(__('resource_submission.infolist.entries.assignment.title')),
                TextEntry::make('student.name')
                    ->label(__('resource_submission.infolist.entries.student.name')),
                TextEntry::make('status')
                    ->label(__('resource_submission.infolist.entries.status')),
                TextEntry::make('submitted_at')
                    ->label(__('resource_submission.infolist.entries.submitted_at'))
                    ->dateTime(),
                TextEntry::make('graded_by')
                    ->label(__('resource_submission.infolist.entries.graded_by')),
                TextEntry::make('points')
                    ->label(__('resource_submission.infolist.entries.points'))
                    ->numeric(),
                TextEntry::make('graded_at')
                    ->label(__('resource_submission.infolist.entries.graded_at'))
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->label(__('resource_submission.infolist.entries.created_at'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(__('resource_submission.infolist.entries.updated_at'))
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->label(__('resource_submission.infolist.entries.deleted_at'))
                    ->dateTime(),
            ]);
    }
}
