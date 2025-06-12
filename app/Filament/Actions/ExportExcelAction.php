<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ExportExcelAction
 *
 * Hành động Filament để xuất dữ liệu ra file Excel.
 * Cho phép cấu hình lớp export và tên file xuất.
 *
 * @package App\Filament\Actions
 */
class ExportExcelAction extends Action
{
    /**
     * Lớp export sử dụng bởi Maatwebsite Excel (ví dụ: App\Exports\ProductExport).
     *
     * @var string
     */
    protected string $exportClass;

    /**
     * Tên file kết quả khi xuất (bao gồm đuôi .xlsx).
     *
     * @var string
     */
    protected string $fileName = 'export.xlsx';

    /**
     * Tạo instance của action với tên tùy chọn.
     *
     * @param string $name Tên action (mặc định 'exportExcel').
     * @return static
     */
    /**
     * @inheritDoc
     */
    public static function make(?string $name = null): static
    {
        // nếu không truyền $name thì dùng default của bạn
        $name = $name ?? 'exportExcel';

        return parent::make($name);
    }

    /**
     * Thiết lập lớp export sẽ được sử dụng để tạo file Excel.
     *
     * @param string $exportClass Tên đầy đủ của lớp export.
     * @return $this
     */
    public function exportClass(string $exportClass): self
    {
        $this->exportClass = $exportClass;

        return $this;
    }

    /**
     * Thiết lập tên file xuất ra (ví dụ 'products.xlsx').
     *
     * @param string $fileName Tên file bao gồm phần mở rộng.
     * @return $this
     */
    public function fileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Thiết lập cấu hình cơ bản cho action:
     * - Label, icon
     * - Logic xuất file bằng Maatwebsite Excel
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Xuất Excel')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {
                // Trả về file download
                return Excel::download(new ($this->exportClass)(), $this->fileName);
            });
    }
}
