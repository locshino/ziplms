<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestion extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        // ĐÃ SỬA: Dùng file ngôn ngữ
        return __('question-resource.notification.create_success');
    }
}
