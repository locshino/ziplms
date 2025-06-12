<?php

namespace App\Filament\Actions;

use Illuminate\Support\Facades\Log;  
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

/**
 * Class ImportExcelAction
 *
 * Hành động Filament để nhập dữ liệu từ file Excel vào hệ thống.
 * Cho phép cấu hình lớp import, thư mục lưu trữ tạm thời,
 * và thông báo kết quả (thành công hoặc lỗi).
 *
 * @package App\Filament\Actions
 */
class ImportExcelAction extends Action
{
    /**
     * Lớp import dùng bởi Maatwebsite Excel (ví dụ: App\Imports\ProductImport).
     *
     * @var string
     */
    protected string $importClass;

    /**
     * Thư mục lưu file Excel tạm thời trước khi import.
     *
     * @var string
     */
    protected string $fileDirectory = 'temp/excel-imports';

    /**
     * Thông báo hiển thị khi import thành công.
     *
     * @var string
     */
    protected string $successMessage = 'Nhập dữ liệu thành công!';

    /**
     * Tiền tố thông báo khi import bị lỗi.
     *
     * @var string
     */
    protected string $errorMessage = 'Không thể nhập file: ';

    /**
     * Khởi tạo action với tên tùy chọn.
     *
     * @param string Tên của action.
     * @return static
     */
    /**
     * @inheritDoc
     */
    public static function make(?string $name = null): static
    {
        // Nếu không truyền $name thì dùng default của bạn
        $name = $name ?? 'importExcel';

        return parent::make($name);
    }


    /**
     * Cấu hình lớp import sẽ được sử dụng để xử lý file Excel.
     *
     * @param string $importClass Tên đầy đủ của lớp import.
     * @return $this
     */
    public function importClass(string $importClass): self
    {
        $this->importClass = $importClass;

        return $this;
    }

    /**
     * Thiết lập thư mục để lưu trữ file được tải lên.
     *
     * @param string $fileDirectory Đường dẫn thư mục tương đối trong disk 'local'.
     * @return $this
     */
    public function fileDirectory(string $fileDirectory): self
    {
        $this->fileDirectory = $fileDirectory;

        return $this;
    }

    /**
     * Thiết lập thông điệp hiển thị khi import thành công.
     *
     * @param string $message Nội dung thông báo thành công.
     * @return $this
     */
    public function successMessage(string $message): self
    {
        $this->successMessage = $message;

        return $this;
    }

    /**
     * Thiết lập thông điệp hiển thị khi import lỗi.
     *
     * @param string $message Nội dung thông báo lỗi.
     * @return $this
     */
    public function errorMessage(string $message): self
    {
        $this->errorMessage = $message;

        return $this;
    }

    /**
     * Thiết lập cấu hình cơ bản cho action:
     * - Label, icon
     * - Form upload file
     * - Xử lý logic import và thông báo kết quả
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Nhập Excel')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                FileUpload::make('file')
                    ->label('Chọn file Excel')
                    ->required()
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                    ])
                    ->storeFiles(true)
                    ->directory($this->fileDirectory)
                    ->disk('local')
                    ->visibility('private'),
            ])
            ->action(function (array $data) {
                try {
                    $file = $data['file'];

                    if (!Storage::disk('local')->exists($file)) {
                        throw new \Exception('File [' . Storage::disk('local')->path($file) . '] không tồn tại.');
                    }

                    $filePath = Storage::disk('local')->path($file);
                    Excel::import(new ($this->importClass)(), $filePath);
                    Storage::disk('local')->delete($file);

                    Notification::make()
                        ->title($this->successMessage)
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Log::error('Lỗi nhập Excel: ' . $e->getMessage(), ['file' => $data['file']]);

                    Notification::make()
                        ->title('Lỗi nhập Excel')
                        ->body($this->errorMessage . $e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}