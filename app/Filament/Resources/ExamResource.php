<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamResource\Pages;
use App\Filament\Resources\ExamResource\RelationManagers;
use App\Models\Exam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Quản lý Đánh giá';

    protected static ?string $label = 'Bài kiểm tra';

    protected static ?string $pluralLabel = 'Danh sách Bài kiểm tra';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(3)->schema([
                // CỘT TRÁI (2/3) - NỘI DUNG CHÍNH
                Forms\Components\Section::make('Nội dung đa ngôn ngữ')
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\Tabs::make('Translations')->tabs([
                            Forms\Components\Tabs\Tab::make('Tiếng Việt')
                                ->schema([
                                    Forms\Components\TextInput::make('title.vi')->label('Tiêu đề')->required(),
                                    Forms\Components\RichEditor::make('description.vi')->label('Mô tả / Hướng dẫn'),
                                ]),
                            Forms\Components\Tabs\Tab::make('Tiếng Anh')
                                ->schema([
                                    Forms\Components\TextInput::make('title.en')->label('Title'),
                                    Forms\Components\RichEditor::make('description.en')->label('Description / Instructions'),
                                ]),
                        ]),
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\Select::make('course_id')
                                ->relationship('course', 'name')
                                ->searchable()->preload()->label('Thuộc khóa học'),
                            Forms\Components\Select::make('lecture_id')
                                ->relationship('lecture', 'title')
                                ->searchable()->preload()->label('Thuộc bài giảng'),
                        ]),
                    ]),

                // CỘT PHẢI (1/3) - CÁC CÀI ĐẶT
                Forms\Components\Section::make('Cài đặt & Thuộc tính')
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_time')->label('Thời gian bắt đầu'),
                        Forms\Components\DateTimePicker::make('end_time')->label('Thời gian kết thúc'),
                        Forms\Components\TextInput::make('duration_minutes')->label('Thời gian làm bài (phút)')->numeric()->required()->default(60),
                        Forms\Components\TextInput::make('max_attempts')->label('Số lần làm bài tối đa')->numeric()->required()->default(1),
                        Forms\Components\TextInput::make('passing_score')->label('Điểm đạt (%)')->numeric()->required()->default(50),
                        Forms\Components\Select::make('show_results_after')
                            ->label('Hiển thị kết quả')
                            ->options(
                                collect(\App\Enums\ExamShowResultsType::cases())->mapWithKeys(fn($case) => [
                                    $case->value => $case->label(),
                                ])->all()
                            )
                            ->required()->native(false),
                        Forms\Components\Toggle::make('shuffle_questions')->label('Xáo trộn câu hỏi?'),
                        Forms\Components\Toggle::make('shuffle_answers')->label('Xáo trộn đáp án?'),
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->limit(40)
                    ->getStateUsing(fn($record): ?string => $record->getTranslation('title', app()->getLocale()))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('title->' . app()->getLocale(), 'like', "%{$search}%");
                    }),
                // Đã xoá cột exam_type ở đây
                Tables\Columns\TextColumn::make('course.name')->label('Khóa học')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Trạng thái')->badge(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // THÊM NÚT MỚI TẠI ĐÂY
                Action::make('take')
                    ->label('Làm bài')
                    ->icon('heroicon-o-pencil-square')
                    ->color('success') // Tạo URL đến trang làm bài
                    ->url(fn(Exam $record): string => static::getUrl('take', ['record' => $record])),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
            'take' => Pages\TakeExam::route('/{record}/take'),
        ];
    }
}
