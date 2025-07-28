<?php

namespace App\Filament\Resources\ExamAttemptResource\Pages;

use App\Filament\Resources\ExamAttemptResource;
use App\Models\ExamAnswer;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ReviewAnswer extends Page
{
    protected static string $resource = ExamAttemptResource::class;

    protected static string $view = 'filament.resources.exam-attempt-resource.pages.review-answer';

    public ExamAnswer $record;


    public function mount(ExamAnswer $record): void
    {
        // Tải các quan hệ cần thiết vào bản ghi đã được inject
        $record->load(['question.choices', 'question.tags', 'selectedChoice']);

        $this->record = $record;
    }
    // ▲▲▲ KẾT THÚC SỬA LỖI ▲▲▲

    public function getTitle(): string | Htmlable
    {
        return __('review-answer-page.title');
    }
}
