<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạo người dùng quản trị viên mặc định và gán vai trò 'Admin'
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password', // Mật khẩu mặc định
        ]);

        $adminUserRoles = [
            RoleEnum::Admin,
            RoleEnum::Dev,
        ];

        $adminUser->assignRole($adminUserRoles);

        // 2. Lấy các vai trò khác để gán ngẫu nhiên
        $otherRoles = Role::whereIn('name', RoleEnum::values(...$adminUserRoles))->get();

        if ($otherRoles->isEmpty()) {
            $this->command->warn('Không tìm thấy vai trò Manager, Teacher, hoặc Student. Sẽ tạo người dùng mà không có các vai trò này.');
        }

        if ($organizations->isEmpty()) {
            $this->command->warn('Không tìm thấy tổ chức nào. Sẽ tạo người dùng mà không có tổ chức.');
        }
        // 3. Tạo các người dùng khác và gán vai trò/tổ chức ngẫu nhiên
        User::factory()->count(20)->create()->each(function (User $user) use ($otherRoles, $organizations) {
            if ($otherRoles->isNotEmpty()) {
                $user->assignRole($otherRoles->random());
            }
            if ($organizations->isNotEmpty()) {
                // Gán người dùng vào một tổ chức ngẫu nhiên
                $user->organizations()->attach($organizations->random());
            }
        });
    }
}
