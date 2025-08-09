<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all roles and permissions
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $studentRole = Role::where('name', 'student')->first();

        // Super Admin gets all permissions
        if ($superAdminRole) {
            $allPermissions = Permission::all();
            $superAdminRole->syncPermissions($allPermissions);
        }

        // Admin gets most permissions except some system-level ones
        if ($adminRole) {
            $adminPermissions = Permission::whereNotIn('name', [
                'delete_users', // Can't delete users
                'delete_roles', // Can't delete roles
                'delete_permissions', // Can't delete permissions
            ])->get();
            $adminRole->syncPermissions($adminPermissions);
        }

        // Teacher permissions are now limited to viewing and grading
        if ($teacherRole) {
            $teacherPermissions = Permission::whereIn('name', [
                // === Quyền xem thông tin ===
                'view_courses',       // Xem các khóa học mình được phân công
                'view_enrollments',   // Xem danh sách học sinh trong khóa học
                'view_assignments',   // Xem các bài tập đã giao
                'view_submissions',   // Xem bài nộp của học sinh để chấm
                'view_quizzes',       // Xem các bài quiz
                'view_questions',     // Xem các câu hỏi trong quiz
                'view_badges',        // Xem các huy hiệu của hệ thống
                'view_reports',       // Xem báo cáo, tiến độ của học sinh

                // === Quyền chấm bài và tương tác ===
                'grade_assignments',  // Chấm điểm bài tập (quyền quan trọng)
                'award_badges',       // Trao huy hiệu cho học sinh (quyền quan trọng)
                'take_quizzes',       // Có thể làm bài quiz để kiểm tra trước
            ])->get();
            $teacherRole->syncPermissions($teacherPermissions);
        }

        // Student gets basic permissions
        if ($studentRole) {
            $studentPermissions = Permission::whereIn('name', [
                'view_courses',
                'view_enrollments',
                'view_assignments',
                'create_submissions',
                'edit_submissions',
                'view_submissions',
                'view_quizzes',
                'take_quizzes', // This is the key permission for taking quizzes
                'view_badges',
            ])->get();
            $studentRole->syncPermissions($studentPermissions);
        }

        // Assign roles to users
        $this->assignRolesToUsers();
    }

    private function assignRolesToUsers(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        // Get roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $studentRole = Role::where('name', 'student')->first();

        // Assign first user as super admin
        $firstUser = $users->first();
        if ($firstUser && $superAdminRole) {
            $firstUser->assignRole($superAdminRole);
        }

        // Assign second user as admin (if exists)
        if ($users->count() > 1 && $adminRole) {
            $secondUser = $users->skip(1)->first();
            $secondUser->assignRole($adminRole);
        }

        // Assign some users as teachers (next 3-5 users)
        if ($users->count() > 2 && $teacherRole) {
            $teacherUsers = $users->skip(2)->take(3);
            foreach ($teacherUsers as $user) {
                $user->assignRole($teacherRole);
            }
        }

        // Assign remaining users as students
        if ($users->count() > 5 && $studentRole) {
            $studentUsers = $users->skip(5);
            foreach ($studentUsers as $user) {
                $user->assignRole($studentRole);
            }
        }
    }
}
