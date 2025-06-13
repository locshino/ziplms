<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class UserBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id');
        $badgeIds = Badge::pluck('id');

        if ($userIds->isEmpty() || $badgeIds->isEmpty()) {
            $this->command->info('Bỏ qua UserBadgeSeeder: Không tìm thấy user hoặc badge.');

            return;
        }

        // 1. Tạo ra tất cả các cặp user-badge có thể có
        $allPossiblePairs = new Collection;
        foreach ($userIds as $userId) {
            foreach ($badgeIds as $badgeId) {
                $allPossiblePairs->push([
                    'user_id' => $userId,
                    'badge_id' => $badgeId,
                ]);
            }
        }

        // 2. Xáo trộn danh sách và lấy ra một số lượng (ví dụ: 50 cặp)
        // Điều này đảm bảo mỗi cặp là duy nhất
        $pairsToInsert = $allPossiblePairs->shuffle()->take(50)->map(function ($pair) {
            // Thêm timestamps
            $pair['awarded_at'] = now();
            $pair['created_at'] = now();
            $pair['updated_at'] = now();

            return $pair;
        })->all();

        // 3. Xóa dữ liệu cũ và chèn hàng loạt để có hiệu năng tốt nhất
        UserBadge::query()->truncate();
        UserBadge::insert($pairsToInsert);

        $this->command->info('Đã tạo '.count($pairsToInsert).' user badges.');
    }
}
