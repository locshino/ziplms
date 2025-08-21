<?php

namespace App\Filament\Resources\Assignments\Schemas;

use App\Enums\Status\AssignmentStatus;
use App\Models\Assignment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Malzariey\FilamentLexicalEditor\LexicalEditor;

class AssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('title')
                            ->required(),
                        Section::make('Thiết lập')
                            ->columns(4)
                            ->components([
                                TextInput::make('max_points')
                                    ->required()
                                    ->numeric()
                                    ->default(10),
                                TextInput::make('max_attempts')
                                    ->minValue(0)
                                    ->numeric(),
                                Select::make('status')
                                    ->options(AssignmentStatus::class)
                                    ->required(),
                            ]),

                        SpatieTagsInput::make('tags')
                            ->label('Phân loại')
                            ->type(Assignment::class),
                    ]),

                Section::make('Mở rộng')
                    ->columnSpanFull()
                    ->collapsible()
                    ->components([
                        LexicalEditor::make('description')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
