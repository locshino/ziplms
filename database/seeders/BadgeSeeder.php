<?php

namespace Database\Seeders;

use App\Enums\Status\BadgeStatus;
use App\Enums\System\RoleSystem;
use App\Models\Badge;
use App\Models\User;
use Database\Seeders\Contracts\HasCacheSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BadgeSeeder extends Seeder
{
    use HasCacheSeeder;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if badges already exist and cache is valid
        if ($this->shouldSkipSeeding('badges', 'badges')) {
            return;
        }

        // Get or create badges with caching
        $this->getCachedData('badges', function () {
            $badgesData = [
                ['title' => 'Người học chăm chỉ', 'description' => 'Hoàn thành khóa học đầu tiên của bạn.', 'icon' => 'heroicon-o-academic-cap'],
                ['title' => 'Bậc thầy câu đố', 'description' => 'Đạt điểm tuyệt đối trong 5 bài kiểm tra.', 'icon' => 'heroicon-o-puzzle-piece'],
                ['title' => 'Chuyên gia nộp bài', 'description' => 'Nộp 10 bài tập đúng hạn.', 'icon' => 'heroicon-o-document-check'],
                ['title' => 'Nhà chinh phục khóa học', 'description' => 'Hoàn thành 5 khóa học khác nhau.', 'icon' => 'heroicon-o-trophy'],
                ['title' => 'Chuỗi học tập', 'description' => 'Đăng nhập 7 ngày liên tiếp.', 'icon' => 'heroicon-o-calendar-days'],
                ['title' => 'Người tìm kiếm tri thức', 'description' => 'Hoàn thành 20 bài kiểm tra.', 'icon' => 'heroicon-o-light-bulb'],
                ['title' => 'Điểm 10 hoàn hảo', 'description' => 'Đạt điểm tối đa trong 3 bài tập.', 'icon' => 'heroicon-o-star'],
                ['title' => 'Chú chim madu', 'description' => 'Nộp 15 bài tập trước thời hạn.', 'icon' => 'heroicon-o-rocket-launch'],
                ['title' => 'Nhà thám hiểm', 'description' => 'Ghi danh vào 3 khóa học thuộc các lĩnh vực khác nhau.', 'icon' => 'heroicon-o-globe-alt'],
                ['title' => 'Người giao tiếp', 'description' => 'Để lại 10 bình luận hữu ích trong các khóa học.', 'icon' => 'heroicon-o-chat-bubble-left-right'],
            ];

            $badges = collect();
            foreach ($badgesData as $data) {
                $badge = Badge::factory()->create([
                    'title' => $data['title'],
                    'slug' => Str::slug($data['title']),
                    'description' => $data['description'],
                    'status' => BadgeStatus::ACTIVE->value,
                ]);

                // You would typically associate an icon with the badge, for example by storing the icon name in a column
                // For now, we'll just create the badge. If you have a media collection for icons:
                // $badge->addMedia(public_path('images/badges/' . Str::slug($data['title']) . '.svg'))->toMediaCollection('badge_icon');

                $badges->push($badge);
            }

            // Assign badges to students
            $students = User::role(RoleSystem::STUDENT->value)->get();
            $studentsToReceiveBadges = $students->random(min(150, $students->count()));

            foreach ($studentsToReceiveBadges as $student) {
                $numBadges = fake()->numberBetween(1, 4);
                $studentBadges = $badges->random($numBadges);

                foreach ($studentBadges as $badge) {
                    if (! $student->badges()->where('badge_id', $badge->id)->exists()) {
                        $student->badges()->attach($badge->id, [
                            'earned_at' => fake()->dateTimeBetween('-6 months', 'now'),
                            'status' => BadgeStatus::ACTIVE->value, // Assuming user_badge status
                        ]);
                    }
                }
            }

            return true;
        });
    }
}
