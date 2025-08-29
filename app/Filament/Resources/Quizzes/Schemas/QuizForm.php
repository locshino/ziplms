<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use App\Enums\Status\QuizStatus;
use App\Models\Quiz;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
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
                            ->label(__('resource_quiz.form.fields.title'))
                            ->required(),
                        Section::make('Thiết lập')
                            ->columns(4)
                            ->components([
                                TextInput::make('max_attempts')
                                    ->label(__('resource_quiz.form.fields.max_attempts'))
                                    ->minValue(0)
                                    ->helperText('Số lần làm bài tối đa (0 là không giới hạn)')
                                    ->numeric(),
                                TextInput::make('time_limit_minutes')
                                    ->label(__('resource_quiz.form.fields.time_limit_minutes'))
                                    ->minValue(0)
                                    ->numeric(),
                                Select::make('status')
                                    ->label(__('resource_quiz.form.fields.status'))
                                    ->options(QuizStatus::class)
                                    ->required(),
                                Toggle::make('is_single_session')
                                    ->label(__('resource_quiz.form.fields.is_single_session'))
                                    ->helperText('Người dùng làm bài trong một phiên duy nhất (không được tạm dừng, không được quay lại tiếp tục làm bài)')
                                    ->required(),
                            ]),
                        SpatieTagsInput::make('tags')
                            ->label(__('resource_quiz.form.fields.tags'))
                            ->type(Quiz::class),
                    ]),

                Section::make('Mở rộng')
                    ->columnSpanFull()
                    ->collapsible()
                    ->components([
                        LexicalEditor::make('description')
                            ->label(__('resource_quiz.form.fields.description'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
