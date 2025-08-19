<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use App\Enums\Status\QuizStatus;
use App\Models\Quiz;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Malzariey\FilamentLexicalEditor\LexicalEditor;

class QuizForm
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
                                TextInput::make('max_attempts')
                                    ->minValue(0)
                                    ->helperText('Số lần làm bài tối đa (0 là không giới hạn)')
                                    ->numeric(),
                                TextInput::make('time_limit_minutes')
                                    ->minValue(0)
                                    ->numeric(),
                                Select::make('status')
                                    ->options(QuizStatus::class)
                                    ->required(),
                                Toggle::make('is_single_session')
                                    ->helperText('Người dùng làm bài trong một phiên duy nhất (không được tạm dừng, không được quay lại tiếp tục làm bài)')
                                    ->required(),
                            ]),
                        SpatieTagsInput::make('tags')
                            ->label('Phân loại')
                            ->type(Quiz::class),
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
