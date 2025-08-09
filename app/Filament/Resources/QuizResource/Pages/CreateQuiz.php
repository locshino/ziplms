<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateQuiz extends CreateRecord
{
    protected static string $resource = QuizResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ngăn chặn việc tự động set end_at nếu người dùng đã chọn thời gian cụ thể
        // Logic này sẽ override logic tự động trong QuizResource
        return $data;
    }

    protected function beforeCreate(): void
    {
        // Validation logic trước khi tạo quiz
        $data = $this->data;

        // Kiểm tra thời gian bắt đầu không được trong quá khứ
        if (isset($data['start_at']) && Carbon::parse($data['start_at'])->isPast()) {
            Notification::make()
                ->title('Lỗi thời gian')
                ->body('Thời gian bắt đầu không được trong quá khứ.')
                ->danger()
                ->send();

            $this->halt();

            return;
        }

        // Kiểm tra thời gian mở và đóng quiz
        if (isset($data['start_at']) && isset($data['end_at'])) {
            $startAt = Carbon::parse($data['start_at']);
            $endAt = Carbon::parse($data['end_at']);

            // start_at là thời gian mở quiz, end_at là thời gian đóng quiz
            // end_at phải sau start_at
            if ($endAt->lte($startAt)) {
                Notification::make()
                    ->title('Lỗi thời gian')
                    ->body('Thời gian đóng quiz phải sau thời gian mở quiz.')
                    ->danger()
                    ->send();

                $this->halt();

                return;
            }

            // Kiểm tra khoảng thời gian mở quiz tối thiểu (ít nhất 30 phút)
            $diffInMinutes = $startAt->diffInMinutes($endAt);
            if ($diffInMinutes < 30) {
                Notification::make()
                    ->title('Lỗi thời gian')
                    ->body("Khoảng thời gian mở quiz phải ít nhất 30 phút. Hiện tại: {$diffInMinutes} phút.")
                    ->danger()
                    ->send();

                $this->halt();

                return;
            }
        }

        // Kiểm tra logic time_limit_minutes (thời gian giới hạn làm bài)
        // time_limit_minutes là thời gian tối đa để hoàn thành quiz (độc lập với start_at/end_at)
        // Không cần so sánh với khoảng thời gian mở quiz vì đây là 2 khái niệm khác nhau
        if (isset($data['time_limit_minutes']) && $data['time_limit_minutes'] <= 0) {
            Notification::make()
                ->title('Lỗi thời gian giới hạn')
                ->body('Thời gian giới hạn làm bài phải lớn hơn 0.')
                ->danger()
                ->send();

            $this->halt();

            return;
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
