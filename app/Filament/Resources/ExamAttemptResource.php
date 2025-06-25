<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamAttemptResource\Pages;
use App\Filament\Resources\ExamAttemptResource\RelationManagers;
use App\Models\ExamAttempt;
use Filament\Forms\Form; // Vẫn cần Form cho các hành động khác (nếu có)
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExamAttemptResource extends Resource
{
    protected static ?string $model = ExamAttempt::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Quản lý Đánh giá';
    protected static ?string $label = 'Lượt làm bài';
    protected static ?string $pluralLabel = 'Danh sách Lượt làm bài';

    // BỎ phương thức form() cũ đi và thay bằng infolist()

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Thông tin chung')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('exam.title')
                            ->label('Bài kiểm tra'),
                        Components\TextEntry::make('user.name')
                            ->label('Học sinh'),
                    ]),
                Components\Section::make('Kết quả')
                    ->columns(3)
                    ->schema([
                        Components\TextEntry::make('score')
                            ->label('Điểm số')
                            ->badge()
                            ->color('success'),
                        Components\TextEntry::make('status')
                            ->label('Trạng thái')
                            ->badge(),
                        Components\TextEntry::make('time_spent_seconds')
                            ->label('Thời gian làm bài')
                            // Định dạng lại số giây thành Giờ:Phút:Giây
                            ->formatStateUsing(fn(?int $state): string => $state ? gmdate('H:i:s', $state) : 'N/A'),
                    ]),
                Components\Section::make('Thời gian')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('started_at')
                            ->label('Bắt đầu lúc')
                            ->dateTime('d/m/Y H:i:s'),
                        Components\TextEntry::make('completed_at')
                            ->label('Hoàn thành lúc')
                            ->dateTime('d/m/Y H:i:s'),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('exam.title')->label('Bài kiểm tra')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Học sinh')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('score')->label('Điểm số')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Trạng thái')->badge(),
                Tables\Columns\TextColumn::make('completed_at')->label('Ngày nộp bài')->dateTime('d/m/Y')->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Xem & Chấm bài'),
            ])
            ->bulkActions([]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExamAttempts::route('/'),
            'view' => Pages\ViewExamAttempt::route('/{record}'),
        ];
    }
}
