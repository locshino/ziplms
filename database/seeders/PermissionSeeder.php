<?php

namespace Database\Seeders;

use App\Enums\System\RoleSystem;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tất cả permissions từ danh sách thực tế
        $allPermissions = [
            // Answer Choices
            'create_answer::choices::answer::choice',
            'view_answer::choices::answer::choice',
            'view_any_answer::choices::answer::choice',
            'update_answer::choices::answer::choice',
            'delete_answer::choices::answer::choice',
            'delete_any_answer::choices::answer::choice',
            'force_delete_answer::choices::answer::choice',
            'force_delete_any_answer::choices::answer::choice',
            'restore_answer::choices::answer::choice',
            'restore_any_answer::choices::answer::choice',
            'reorder_answer::choices::answer::choice',
            'replicate_answer::choices::answer::choice',

            // API Docs
            'create_api::docs::api::docs',
            'view_api::docs::api::docs',
            'view_any_api::docs::api::docs',
            'update_api::docs::api::docs',
            'delete_api::docs::api::docs',
            'delete_any_api::docs::api::docs',
            'force_delete_api::docs::api::docs',
            'force_delete_any_api::docs::api::docs',
            'restore_api::docs::api::docs',
            'restore_any_api::docs::api::docs',
            'reorder_api::docs::api::docs',
            'replicate_api::docs::api::docs',

            // Assignments
            'create_assignments::assignment',
            'view_assignments::assignment',
            'view_any_assignments::assignment',
            'update_assignments::assignment',
            'delete_assignments::assignment',
            'delete_any_assignments::assignment',
            'force_delete_assignments::assignment',
            'force_delete_any_assignments::assignment',
            'restore_assignments::assignment',
            'restore_any_assignments::assignment',
            'reorder_assignments::assignment',
            'replicate_assignments::assignment',

            // Authentication Logs
            'create_authentication::logs::authentication::log',
            'view_authentication::logs::authentication::log',
            'view_any_authentication::logs::authentication::log',
            'update_authentication::logs::authentication::log',
            'delete_authentication::logs::authentication::log',
            'delete_any_authentication::logs::authentication::log',
            'force_delete_authentication::logs::authentication::log',
            'force_delete_any_authentication::logs::authentication::log',
            'restore_authentication::logs::authentication::log',
            'restore_any_authentication::logs::authentication::log',
            'reorder_authentication::logs::authentication::log',
            'replicate_authentication::logs::authentication::log',

            // Badge Conditions
            'create_badge::conditions::badge::condition',
            'view_badge::conditions::badge::condition',
            'view_any_badge::conditions::badge::condition',
            'update_badge::conditions::badge::condition',
            'delete_badge::conditions::badge::condition',
            'delete_any_badge::conditions::badge::condition',
            'force_delete_badge::conditions::badge::condition',
            'force_delete_any_badge::conditions::badge::condition',
            'restore_badge::conditions::badge::condition',
            'restore_any_badge::conditions::badge::condition',
            'reorder_badge::conditions::badge::condition',
            'replicate_badge::conditions::badge::condition',

            // Badges
            'create_badges::badge',
            'view_badges::badge',
            'view_any_badges::badge',
            'update_badges::badge',
            'delete_badges::badge',
            'delete_any_badges::badge',
            'force_delete_badges::badge',
            'force_delete_any_badges::badge',
            'restore_badges::badge',
            'restore_any_badges::badge',
            'reorder_badges::badge',
            'replicate_badges::badge',

            // Courses
            'create_courses::course',
            'view_courses::course',
            'view_any_courses::course',
            'update_courses::course',
            'delete_courses::course',
            'delete_any_courses::course',
            'force_delete_courses::course',
            'force_delete_any_courses::course',
            'restore_courses::course',
            'restore_any_courses::course',
            'reorder_courses::course',
            'replicate_courses::course',

            // Mail Logs
            'create_mail::logs::mail::log',
            'view_mail::logs::mail::log',
            'view_any_mail::logs::mail::log',
            'update_mail::logs::mail::log',
            'delete_mail::logs::mail::log',
            'delete_any_mail::logs::mail::log',
            'force_delete_mail::logs::mail::log',
            'force_delete_any_mail::logs::mail::log',
            'restore_mail::logs::mail::log',
            'restore_any_mail::logs::mail::log',
            'reorder_mail::logs::mail::log',
            'replicate_mail::logs::mail::log',

            // Questions
            'create_questions::question',
            'view_questions::question',
            'view_any_questions::question',
            'update_questions::question',
            'delete_questions::question',
            'delete_any_questions::question',
            'force_delete_questions::question',
            'force_delete_any_questions::question',
            'restore_questions::question',
            'restore_any_questions::question',
            'reorder_questions::question',
            'replicate_questions::question',

            // Quiz Attempts
            'create_quiz::attempts::quiz::attempt',
            'view_quiz::attempts::quiz::attempt',
            'view_any_quiz::attempts::quiz::attempt',
            'update_quiz::attempts::quiz::attempt',
            'delete_quiz::attempts::quiz::attempt',
            'delete_any_quiz::attempts::quiz::attempt',
            'force_delete_quiz::attempts::quiz::attempt',
            'force_delete_any_quiz::attempts::quiz::attempt',
            'restore_quiz::attempts::quiz::attempt',
            'restore_any_quiz::attempts::quiz::attempt',
            'reorder_quiz::attempts::quiz::attempt',
            'replicate_quiz::attempts::quiz::attempt',

            // Quizzes
            'create_quizzes::quiz',
            'view_quizzes::quiz',
            'view_any_quizzes::quiz',
            'update_quizzes::quiz',
            'delete_quizzes::quiz',
            'delete_any_quizzes::quiz',
            'force_delete_quizzes::quiz',
            'force_delete_any_quizzes::quiz',
            'restore_quizzes::quiz',
            'restore_any_quizzes::quiz',
            'reorder_quizzes::quiz',
            'replicate_quizzes::quiz',

            // Submissions
            'create_submissions::submission',
            'view_submissions::submission',
            'view_any_submissions::submission',
            'update_submissions::submission',
            'delete_submissions::submission',
            'delete_any_submissions::submission',
            'force_delete_submissions::submission',
            'force_delete_any_submissions::submission',
            'restore_submissions::submission',
            'restore_any_submissions::submission',
            'reorder_submissions::submission',
            'replicate_submissions::submission',

            // Users
            'create_users::user',
            'view_users::user',
            'view_any_users::user',
            'update_users::user',
            'delete_users::user',
            'delete_any_users::user',
            'force_delete_users::user',
            'force_delete_any_users::user',
            'restore_users::user',
            'restore_any_users::user',
            'reorder_users::user',
            'replicate_users::user',

            // Roles
            'view_roles::role',
            'view_any_roles::role',
            'update_roles::role',

            // Pages
            'page_CourseDetail',
            'page_ForbiddenPage',
            'page_GradingPage',
            'page_ListLogs',
            'page_MyAssignmentsPage',
            'page_MyCourse',
            'page_MyDocument',
            'page_MyQuiz',
            'page_PageNotFoundPage',
            'page_QuizAnswers',
            'page_QuizResults',
            'page_QuizTaking',
            'page_Reports',
            'page_ViewLog',

            // Widget
            'widget_MyCalendarWidget',
        ];

        // Tạo tất cả permissions
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Định nghĩa permissions cho từng role
        $forceDeletePermissions = array_filter($allPermissions, function ($permission) {
            return str_contains($permission, 'force_delete');
        });

        $rolePermissions = array_filter($allPermissions, function ($permission) {
            return str_contains($permission, 'roles::role');
        });

        // Super Admin: Có tất cả quyền
        $superAdminPermissions = $allPermissions;

        // Admin: Tất cả quyền trừ role management và force delete
        $adminPermissions = array_diff($allPermissions, array_merge($forceDeletePermissions, $rolePermissions));

        // Manager: Chỉ có thể sửa và xem khóa học, xem báo cáo
        $managerPermissions = [
            'view_courses::course',
            'view_any_courses::course',
            'update_courses::course',
            'page_Reports',
            'page_MyCourse',
            'widget_MyCalendarWidget',
        ];

        // Teacher: Chấm bài (submissions) và xem báo cáo
        $teacherPermissions = [
            'page_Reports',
            'page_GradingPage',
            'page_MyAssignmentsPage',
            'page_QuizAnswers',
            'page_QuizResults',
            'widget_MyCalendarWidget',
        ];

        // Student: Xem khóa học, làm quiz, làm bài tập
        $studentPermissions = [
            'page_CourseDetail',
            'page_MyCourse',
            'page_MyAssignmentsPage',
            'page_MyQuiz',
            'page_QuizTaking',
            'page_QuizResults',
            'widget_MyCalendarWidget',
        ];

        // Tạo roles và gán permissions
        $roles = [
            RoleSystem::SUPER_ADMIN->value => $superAdminPermissions,
            RoleSystem::ADMIN->value => $adminPermissions,
            RoleSystem::MANAGER->value => $managerPermissions,
            RoleSystem::TEACHER->value => $teacherPermissions,
            RoleSystem::STUDENT->value => $studentPermissions,
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($permissions);

            $this->command->info("Role '{$roleName}' created/updated with ".count($permissions).' permissions.');
        }

        $this->command->info('Permission seeding completed successfully!');
    }
}
