<?php

namespace App\Filament\Pages;

use App\Models\Course;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Actions\Action;
use App\Filament\Pages\ManageCourses;

class ViewCourseDetails extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.view-course-details';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $route = '/courses/{course}';

    public Course $record;
    public string $activeTab = 'details';

    public function mount(Course $record): void
    {
        $this->record = $record->load('teacher', 'quizzes', 'assignments', 'students');
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->title ?? 'Chi tiết khóa học';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Quay lại')
                ->color('gray')
                ->icon('heroicon-o-arrow-left')
                ->url(ManageCourses::getUrl()),
        ];
    }
}