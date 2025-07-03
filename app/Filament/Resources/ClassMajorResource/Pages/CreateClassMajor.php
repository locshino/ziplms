<?php

namespace App\Filament\Resources\ClassMajorResource\Pages;

use App\Filament\Resources\ClassMajorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClassesMajorImport; // ✅ Đảm bảo đúng tên class

class CreateClassMajor extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ClassMajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),

            Action::make('import_excel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('excel_file')
                        ->label('Chọn file Excel')
                        ->required()
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes(['.xlsx', '.csv']),
                ])
                ->action(function (array $data) {
                    if (!isset($data['excel_file'])) {
                        throw new \Exception('Bạn chưa chọn file!');
                    }

                    $path = storage_path('app/' . $data['excel_file']);
                    Excel::import(new ClassesMajorImport, $path);

                    \Filament\Notifications\Notification::make()
                        ->title('Import thành công!')
                        ->success()
                        ->send();
                }),
        ];
    }
}
