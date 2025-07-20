<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Models\Course;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewCourse extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Thống kê')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('students_count')
                            ->label('Số lượng học viên')
                            ->badge()
                            ->color('success')
                            ->state(fn(Course $record): int => $record->students()->count()),

                        TextEntry::make('staff_count')
                            ->label('Số lượng nhân viên/giáo viên') // Sửa lại label cho rõ nghĩa
                            ->badge()
                            ->color('info')
                            ->state(fn(Course $record): int => $record->staff()->count()), // SỬA Ở ĐÂY
                    ]),

                Section::make('Thông tin môn học')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')->label('Tên môn học'),
                        TextEntry::make('code')->label('Mã môn học'),
                        TextEntry::make('organization.name')->label('Tổ chức'),
                        TextEntry::make('parent.name')->label('Thuộc môn học cha'),
                        TextEntry::make('status')->label('Trạng thái')->badge(),
                        TextEntry::make('description')
                            ->label('Mô tả')
                            ->html()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}