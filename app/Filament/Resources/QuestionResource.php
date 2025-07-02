<?php

namespace App\Filament\Resources;

use App\Enums\OrganizationType; // <-- Import Enum của Tổ chức
use App\Enums\QuestionType;     // <-- Import Enum của Câu hỏi
use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section; // Keep Select for organization_id
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput; // Import Repeater
use Filament\Forms\Components\Toggle; // Import TextInput for choices
use Filament\Forms\Form; // Import Toggle for is_correct
use Filament\Resources\Resource;
use Filament\Tables;
// <-- Import bộ lọc
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Quản lý Đánh giá';

    protected static ?string $label = 'Câu hỏi';

    protected static ?string $pluralLabel = 'Ngân hàng Câu hỏi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(3)->schema([
                Section::make('Chi tiết câu hỏi')
                    ->columnSpan(2)
                    ->schema([
                        Textarea::make('question_text')
                            ->label('Nội dung câu hỏi')
                            ->required(),
                        RichEditor::make('explanation')
                            ->label('Giải thích đáp án (nếu có)'),
                    ]),
                Section::make('Thuộc tính')
                    ->columnSpan(1)
                    ->schema([
                        // 1. Ô Chọn LOẠI CÂU HỎI
                        SpatieTagsInput::make('question_type_tags') // Use SpatieTagsInput for tags
                            ->label('Loại câu hỏi')
                            ->type(QuestionType::key()) // Specify the tag type
                            ->required()
                            ->suggestions(QuestionType::values()), // Optional: provide suggestions from enum values

                        // 2. Ô Chọn THẺ PHÂN LOẠI (Organization Type)
                        SpatieTagsInput::make('organization_type_tags') // Use SpatieTagsInput for tags
                            ->label('Thẻ phân loại')
                            ->type(OrganizationType::key()) // Specify the tag type
                            ->suggestions(OrganizationType::values()) // Optional: provide suggestions from enum values
                            ->required(),

                        // 3. Ô Chọn TỔ CHỨC
                        Select::make('organization_id')
                            ->label('Tổ chức')
                            ->relationship('organization', 'name')
                            ->searchable()->preload()->required()->native(false),
                    ]),
            ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question_text')
                    ->label('Nội dung câu hỏi')
                    ->limit(70)
                    ->searchable(),

                // Cột 1: Hiển thị "Loại câu hỏi"
                SpatieTagsColumn::make('question_tags')
                    ->label('Loại câu hỏi')
                    ->type('question-type'), // Specify the tag type

                // CỘT 2: HIỂN THỊ "LOẠI HÌNH TỔ CHỨC" (ĐÃ SỬA)
                SpatieTagsColumn::make('organization_tags')
                    ->label('Loại hình Tổ chức')
                    ->type('organization-type'), // Specify the tag type

                // Cột 3: Hiển thị tên Tổ chức
                TextColumn::make('organization.name')
                    ->label('Tổ chức')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                // Để trống để không gây lỗi
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotificationTitle('Câu hỏi đã được cập nhật thành công.'),
                Tables\Actions\DeleteAction::make()
                    ->successNotificationTitle('Câu hỏi đã được xóa thành công.'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChoicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
