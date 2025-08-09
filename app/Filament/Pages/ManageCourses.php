<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ViewCourseDetails;
use App\Models\Course;
use Filament\Actions\Action;
use App\Services\CourseService;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Filament\Forms\Components\SpatieTagsInput;
use Livewire\Attributes\Url;

class ManageCourses extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static string $view = 'filament.pages.manage-courses';
    protected static ?string $navigationLabel = 'Courses';

    #[Url]
    public string $search = '';

    public function getTitle(): string|Htmlable
    {
        return __('Khóa học');
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getCoursesProperty(): Paginator
    {
        return Course::with(['teacher', 'media'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(9);
    }

    protected function getCourseForm(Form $form): Form
    {
        return $form->schema([
            SpatieMediaLibraryFileUpload::make('thumbnail')
                ->label('Ảnh đại diện khóa học')
                ->collection('course_thumbnail')
                ->image()
                ->imageEditor()
                ->responsiveImages()
                ->columnSpanFull(),
            Select::make('teacher_id')
                ->label('Giảng viên')
                ->relationship('teacher', 'name')
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('title')
                ->label('Tên khóa học')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            SpatieTagsInput::make('tags')
                ->label('Thẻ')
                ->placeholder('Nhập thẻ mới')
                ->type('course_tags')
                ->columnSpanFull(),
            RichEditor::make('description')
                ->label('Mô tả khóa học')
                ->columnSpanFull(),
            Select::make('students')
                ->label('Học viên')
                ->relationship('students', 'name')
                ->multiple()
                ->preload()
                ->searchable()
                ->placeholder('Chọn một hoặc nhiều học sinh')
                ->columnSpanFull(),
        ]);
    }

    public function createCourseAction(): CreateAction
    {
        return CreateAction::make('createCourse')
            ->label('Tạo khóa học mới')
            ->model(Course::class)
            ->form(fn(Form $form) => $this->getCourseForm($form))
            ->using(function (array $data): Course {
                $studentIds = $data['students'] ?? [];
                unset($data['students']);

                $course = Course::create($data);

                if (!empty($studentIds)) {
                    $course->students()->sync($studentIds);
                }

                return $course;
            })
            ->after(fn() => $this->dispatch('refresh'))
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('Tạo khóa học thành công')
                    ->body('Khóa học mới và danh sách học sinh đã được lưu.'),
            )
            ->createAnother(false);
    }

    public function viewCourseAction(): Action
    {
        return Action::make('viewCourse')
            ->label('Xem')
            ->color('info')
            ->icon('heroicon-s-eye')
            ->url(fn(array $arguments): string => ViewCourseDetails::getUrl(['record' => $arguments['record']]));
    }

    public function editCourseAction(): EditAction
    {
        return EditAction::make('editCourse')
            ->label('Sửa')
            ->color('warning')
            ->icon('heroicon-s-pencil')
            ->record(fn(array $arguments) => Course::findOrFail($arguments['record'] ?? null))
            ->form(fn(Form $form) => $this->getCourseForm($form))
            ->modalHeading(fn($record) => $record ? 'Sửa Khóa học' : 'Sửa')
            ->using(function (Course $record, array $data): Course {
                $studentIds = $data['students'] ?? null;
                unset($data['students']);

                app(CourseService::class)->updateCourse($record->id, $data);

                if (is_array($studentIds)) {
                    $record->students()->sync($studentIds);
                }

                return $record;
            })
            ->after(fn() => $this->dispatch('refresh'))
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('Cập nhật thành công')
                    ->body('Thông tin khóa học đã được cập nhật.'),
            );
    }

    public function deleteCourseAction(): DeleteAction
    {
        return DeleteAction::make('deleteCourse')
            ->label('Xóa')
            ->icon('heroicon-s-trash')
            ->record(fn(array $arguments): ?Course => Course::find($arguments['record'] ?? null))
            ->requiresConfirmation()
            ->modalIcon('heroicon-o-trash')
            ->modalHeading(fn(?Course $record) => $record ? 'Xóa: ' . $record->title : 'Xóa Khóa học')
            ->modalDescription('Bạn có chắc chắn muốn xóa khóa học này không?')
            ->action(function (?Course $record) {
                if (!$record) {
                    return;
                }
                if ($record->enrollments()->exists()) {
                    Notification::make()
                        ->danger()
                        ->title('Xóa không thành công')
                        ->body('Không thể xóa khóa học này vì đã có học viên tham gia.')
                        ->send();

                    return;
                }
                app(CourseService::class)->deleteCourse($record->id);
                Notification::make()
                    ->success()
                    ->title('Đã xóa khóa học')
                    ->body('Khóa học đã được xóa khỏi hệ thống.')
                    ->send();
                $this->dispatch('refresh');
            });
    }
}