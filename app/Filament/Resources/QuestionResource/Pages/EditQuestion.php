<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Tùy chỉnh dữ liệu trước khi đổ vào form.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Lấy bản ghi hiện tại
        $record = $this->getRecord();

        // Với mỗi trường đa ngôn ngữ, chúng ta sẽ lấy bản dịch cho ngôn ngữ hiện tại
        // và gán lại vào mảng data.
        $data['question_text'] = $record->getTranslation('question_text', app()->getLocale());
        $data['explanation'] = $record->getTranslation('explanation', app()->getLocale());

        // Tương tự, xử lý cho các lựa chọn trong Repeater
        if (isset($data['choices']) && is_array($data['choices'])) {
            foreach ($data['choices'] as $key => $choiceData) {
                // Giả sử choiceData['id'] tồn tại và là UUID của QuestionChoice
                $choiceRecord = \App\Models\QuestionChoice::find($choiceData['id']);
                if ($choiceRecord) {
                    $data['choices'][$key]['choice_text'] = $choiceRecord->getTranslation('choice_text', app()->getLocale());
                }
            }
        }

        return $data;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Câu hỏi đã được cập nhật thành công.';
    }
}
