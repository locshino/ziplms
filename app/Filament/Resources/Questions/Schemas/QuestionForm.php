<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Enums\Status\QuestionStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('title')
                    ->label(__('resource_question.form.fields.title'))
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label(__('resource_question.form.fields.description'))
                    ->columnSpanFull(),
                Select::make('status')
                    ->label(__('resource_question.form.fields.status'))
                    ->options(QuestionStatus::class)
                    ->required(),
            ]);
    }
}
