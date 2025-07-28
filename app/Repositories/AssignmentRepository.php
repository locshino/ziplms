<?php

namespace App\Repositories;

use App\Models\Assignment;
use App\Repositories\Base\Repository;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AssignmentRepository extends Repository implements AssignmentRepositoryInterface
{
    /**
     * Xác định model chính mà repository thao tác.
     */
    protected function model(): string
    {
        return Assignment::class;
    }

    /**
     * Lấy nội dung hướng dẫn (text) bằng tiếng Việt của bài tập.
     *
     * @return string|null Trả về chuỗi nội dung hoặc null nếu không có
     */
    public function getInstructionsText(Assignment $assignment): ?string
    {
        // Lấy bản dịch trường 'instructions' theo ngôn ngữ 'vi'
        $vi = $assignment->getTranslation('instructions', 'vi');

        // Nếu là mảng, lấy phần text; nếu không có thì trả về null
        return is_array($vi) ? ($vi['text'] ?? null) : null;
    }

    /**
     * Lấy URL đầy đủ file hướng dẫn của bài tập (nếu có).
     *
     * @return string|null URL file hoặc null nếu không tồn tại
     */
    public function getInstructionsFileUrl(Assignment $assignment): ?string
    {
        // Lấy đường dẫn file lưu trong storage
        $filePath = $this->getInstructionsFileDefault($assignment);

        // Nếu có đường dẫn, tạo URL đầy đủ, nếu không trả về null
        return $filePath ? asset('storage/'.$filePath) : null;
    }

    /**
     * Lấy đường dẫn file hướng dẫn mặc định của bài tập.
     * Ưu tiên lấy bản dịch tiếng Việt, nếu không có lấy bản tiếng Anh.
     *
     * @return string|null Đường dẫn file hoặc null nếu không có
     */
    public function getInstructionsFileDefault(Assignment $assignment): ?string
    {
        // Lấy bản dịch hướng dẫn theo tiếng Việt
        $vi = $assignment->getTranslation('instructions', 'vi');

        // Nếu là mảng, lấy file trong bản dịch tiếng Việt hoặc fallback sang tiếng Anh, nếu không có trả về null
        return is_array($vi) ? ($vi['file'] ?? $vi['en'] ?? null) : null;
    }

    /**
     * Kiểm tra xem có nên hiển thị file hướng dẫn hay không.
     *
     * @return bool true nếu có file hướng dẫn tiếng Việt hoặc tiếng Anh, false nếu không
     */
    public function shouldShowInstructionsFile(Assignment $assignment): bool
    {
        $vi = $assignment->getTranslation('instructions', 'vi');

        // Kiểm tra nếu bản dịch là mảng và có trường 'file' hoặc trường 'en' không rỗng thì trả về true
        return is_array($vi) && (! empty($vi['file'] ?? null) || ! empty($vi['en'] ?? null));
    }

    public function isStudent(): bool
    {
        return Auth::check() && ! Auth::user()->hasRole('student');
    }
}
