<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Libs\Permissions\PermissionHelper;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for different roles
        $permissions = $this->getPermissions();

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['is_system' => true]
            );
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    /**
     * Get all permissions for the system
     */
    private function getPermissions(): array
    {
        return [
            // User Management Permissions
            PermissionHelper::make()->view()->user()->all()->build(),
            PermissionHelper::make()->create()->user()->all()->build(),
            PermissionHelper::make()->update()->user()->all()->build(),
            PermissionHelper::make()->delete()->user()->all()->build(),
            PermissionHelper::make()->manage()->user()->all()->build(),
            PermissionHelper::make()->view()->user()->self()->build(),
            PermissionHelper::make()->update()->user()->self()->build(),
            PermissionHelper::make()->invite()->user()->all()->build(),
            PermissionHelper::make()->suspend()->user()->all()->build(),
            PermissionHelper::make()->activate()->user()->all()->build(),
            PermissionHelper::make()->deactivate()->user()->all()->build(),

            // Role & Permission Management
            PermissionHelper::make()->view()->role()->all()->build(),
            PermissionHelper::make()->create()->role()->all()->build(),
            PermissionHelper::make()->update()->role()->all()->build(),
            PermissionHelper::make()->delete()->role()->all()->build(),
            PermissionHelper::make()->assign()->role()->all()->build(),
            PermissionHelper::make()->view()->permission()->all()->build(),
            PermissionHelper::make()->create()->permission()->all()->build(),
            PermissionHelper::make()->update()->permission()->all()->build(),
            PermissionHelper::make()->delete()->permission()->all()->build(),

            // Course Management Permissions
            PermissionHelper::make()->view()->course()->all()->build(),
            PermissionHelper::make()->create()->course()->all()->build(),
            PermissionHelper::make()->update()->course()->all()->build(),
            PermissionHelper::make()->delete()->course()->all()->build(),
            PermissionHelper::make()->manage()->course()->all()->build(),
            PermissionHelper::make()->view()->course()->assigned()->build(),
            PermissionHelper::make()->update()->course()->assigned()->build(),
            PermissionHelper::make()->view()->course()->enrolled()->build(),
            PermissionHelper::make()->teach()->course()->assigned()->build(),
            PermissionHelper::make()->attend()->course()->enrolled()->build(),
            PermissionHelper::make()->enroll()->course()->all()->build(),
            PermissionHelper::make()->publish()->course()->all()->build(),
            PermissionHelper::make()->unpublish()->course()->all()->build(),
            PermissionHelper::make()->archive()->course()->all()->build(),
            PermissionHelper::make()->restore()->course()->all()->build(),

            // Quiz Management Permissions
            PermissionHelper::make()->view()->quiz()->all()->build(),
            PermissionHelper::make()->create()->quiz()->all()->build(),
            PermissionHelper::make()->update()->quiz()->all()->build(),
            PermissionHelper::make()->delete()->quiz()->all()->build(),
            PermissionHelper::make()->manage()->quiz()->all()->build(),
            PermissionHelper::make()->view()->quiz()->assigned()->build(),
            PermissionHelper::make()->update()->quiz()->assigned()->build(),
            PermissionHelper::make()->view()->quiz()->enrolled()->build(),
            PermissionHelper::make()->take()->quiz()->enrolled()->build(),
            PermissionHelper::make()->retake()->quiz()->enrolled()->build(),
            PermissionHelper::make()->start()->quiz()->enrolled()->build(),
            PermissionHelper::make()->finish()->quiz()->enrolled()->build(),
            PermissionHelper::make()->pause()->quiz()->enrolled()->build(),
            PermissionHelper::make()->resume()->quiz()->enrolled()->build(),
            PermissionHelper::make()->viewResults()->quiz()->all()->build(),
            PermissionHelper::make()->viewResults()->quiz()->self()->build(),

            // Assignment Management Permissions
            PermissionHelper::make()->view()->assignment()->all()->build(),
            PermissionHelper::make()->create()->assignment()->all()->build(),
            PermissionHelper::make()->update()->assignment()->all()->build(),
            PermissionHelper::make()->delete()->assignment()->all()->build(),
            PermissionHelper::make()->manage()->assignment()->all()->build(),
            PermissionHelper::make()->view()->assignment()->assigned()->build(),
            PermissionHelper::make()->update()->assignment()->assigned()->build(),
            PermissionHelper::make()->view()->assignment()->enrolled()->build(),
            PermissionHelper::make()->distribute()->assignment()->all()->build(),
            PermissionHelper::make()->collect()->assignment()->all()->build(),
            PermissionHelper::make()->extend()->assignment()->all()->build(),

            // Submission Management Permissions
            PermissionHelper::make()->view()->submission()->all()->build(),
            PermissionHelper::make()->create()->submission()->all()->build(),
            PermissionHelper::make()->update()->submission()->all()->build(),
            PermissionHelper::make()->delete()->submission()->all()->build(),
            PermissionHelper::make()->view()->submission()->self()->build(),
            PermissionHelper::make()->submit()->submission()->enrolled()->build(),
            PermissionHelper::make()->gradeVerb()->submission()->all()->build(),
            PermissionHelper::make()->review()->submission()->all()->build(),
            PermissionHelper::make()->approve()->submission()->all()->build(),
            PermissionHelper::make()->reject()->submission()->all()->build(),

            // Enrollment Management Permissions
            PermissionHelper::make()->view()->enrollment()->all()->build(),
            PermissionHelper::make()->create()->enrollment()->all()->build(),
            PermissionHelper::make()->update()->enrollment()->all()->build(),
            PermissionHelper::make()->delete()->enrollment()->all()->build(),
            PermissionHelper::make()->manage()->enrollment()->all()->build(),
            PermissionHelper::make()->view()->enrollment()->self()->build(),

            // Badge Management Permissions
            PermissionHelper::make()->view()->badge()->all()->build(),
            PermissionHelper::make()->create()->badge()->all()->build(),
            PermissionHelper::make()->update()->badge()->all()->build(),
            PermissionHelper::make()->delete()->badge()->all()->build(),
            PermissionHelper::make()->manage()->badge()->all()->build(),
            PermissionHelper::make()->view()->badge()->self()->build(),
            PermissionHelper::make()->earn()->badge()->all()->build(),
            PermissionHelper::make()->award()->badge()->all()->build(),
            PermissionHelper::make()->revoke()->badge()->all()->build(),

            // Media Management Permissions
            PermissionHelper::make()->view()->media()->all()->build(),
            PermissionHelper::make()->create()->media()->all()->build(),
            PermissionHelper::make()->update()->media()->all()->build(),
            PermissionHelper::make()->delete()->media()->all()->build(),
            PermissionHelper::make()->upload()->media()->all()->build(),
            PermissionHelper::make()->download()->media()->all()->build(),
            PermissionHelper::make()->stream()->media()->all()->build(),

            // Question Management Permissions
            PermissionHelper::make()->view()->question()->all()->build(),
            PermissionHelper::make()->create()->question()->all()->build(),
            PermissionHelper::make()->update()->question()->all()->build(),
            PermissionHelper::make()->delete()->question()->all()->build(),
            PermissionHelper::make()->manage()->question()->all()->build(),

            // Tag Management Permissions
            PermissionHelper::make()->view()->tag()->all()->build(),
            PermissionHelper::make()->create()->tag()->all()->build(),
            PermissionHelper::make()->update()->tag()->all()->build(),
            PermissionHelper::make()->delete()->tag()->all()->build(),

            // Communication Permissions
            PermissionHelper::make()->notify()->user()->all()->build(),
            PermissionHelper::make()->message()->user()->all()->build(),
            PermissionHelper::make()->announce()->course()->all()->build(),

            // Monitoring & Analytics Permissions
            PermissionHelper::make()->monitor()->user()->all()->build(),
            PermissionHelper::make()->track()->user()->all()->build(),
            PermissionHelper::make()->evaluate()->user()->all()->build(),
            PermissionHelper::make()->generate()->media()->all()->build(), // Reports
            PermissionHelper::make()->analyze()->media()->all()->build(), // Analytics

            // System Administration Permissions
            PermissionHelper::make()->configure()->user()->all()->build(),
            PermissionHelper::make()->backup()->user()->all()->build(),
            PermissionHelper::make()->export()->user()->all()->build(),
            PermissionHelper::make()->import()->user()->all()->build(),
            PermissionHelper::make()->reset()->user()->all()->build(),
        ];
    }

    /**
     * Assign permissions to roles
     */
    private function assignPermissionsToRoles(): void
    {
        // Get roles
        $superAdmin = Role::where('name', 'super_admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $manager = Role::where('name', 'manager')->first();
        $teacher = Role::where('name', 'teacher')->first();
        $student = Role::where('name', 'student')->first();

        // Super Admin gets all permissions
        if ($superAdmin) {
            $allPermissions = Permission::all();
            $superAdmin->syncPermissions($allPermissions);
        }

        // Admin permissions (system management)
        if ($admin) {
            $adminPermissions = [
                // User Management
                'view_user_all', 'create_user_all', 'update_user_all', 'delete_user_all',
                'invite_user_all', 'suspend_user_all', 'activate_user_all', 'deactivate_user_all',
                
                // Role & Permission Management
                'view_role_all', 'create_role_all', 'update_role_all', 'delete_role_all', 'assign_role_all',
                'view_permission_all', 'create_permission_all', 'update_permission_all', 'delete_permission_all',
                
                // Course Management
                'view_course_all', 'create_course_all', 'update_course_all', 'delete_course_all',
                'manage_course_all', 'publish_course_all', 'unpublish_course_all', 'archive_course_all', 'restore_course_all',
                
                // Quiz Management
                'view_quiz_all', 'create_quiz_all', 'update_quiz_all', 'delete_quiz_all', 'manage_quiz_all',
                'view_results_quiz_all',
                
                // Assignment Management
                'view_assignment_all', 'create_assignment_all', 'update_assignment_all', 'delete_assignment_all',
                'manage_assignment_all', 'distribute_assignment_all', 'collect_assignment_all', 'extend_assignment_all',
                
                // Submission Management
                'view_submission_all', 'grade_submission_all', 'review_submission_all', 'approve_submission_all', 'reject_submission_all',
                
                // Enrollment Management
                'view_enrollment_all', 'create_enrollment_all', 'update_enrollment_all', 'delete_enrollment_all', 'manage_enrollment_all',
                
                // Badge Management
                'view_badge_all', 'create_badge_all', 'update_badge_all', 'delete_badge_all',
                'manage_badge_all', 'award_badge_all', 'revoke_badge_all',
                
                // Media Management
                'view_media_all', 'create_media_all', 'update_media_all', 'delete_media_all',
                'upload_media_all', 'download_media_all', 'stream_media_all',
                
                // Question Management
                'view_question_all', 'create_question_all', 'update_question_all', 'delete_question_all', 'manage_question_all',
                
                // Tag Management
                'view_tag_all', 'create_tag_all', 'update_tag_all', 'delete_tag_all',
                
                // Communication
                'notify_user_all', 'message_user_all', 'announce_course_all',
                
                // Monitoring & Analytics
                'monitor_user_all', 'track_user_all', 'evaluate_user_all', 'generate_media_all', 'analyze_media_all',
                
                // System Administration
                'configure_user_all', 'backup_user_all', 'export_user_all', 'import_user_all', 'reset_user_all',
            ];
            $admin->syncPermissions($adminPermissions);
        }

        // Manager permissions (department/institution level management)
        if ($manager) {
            $managerPermissions = [
                // User Management (limited)
                'view_user_all', 'update_user_all', 'invite_user_all',
                
                // Course Management
                'view_course_all', 'create_course_all', 'update_course_all',
                'manage_course_all', 'publish_course_all', 'unpublish_course_all',
                
                // Quiz Management
                'view_quiz_all', 'create_quiz_all', 'update_quiz_all',
                'manage_quiz_all', 'view_results_quiz_all',
                
                // Assignment Management
                'view_assignment_all', 'create_assignment_all', 'update_assignment_all',
                'manage_assignment_all', 'distribute_assignment_all', 'collect_assignment_all',
                
                // Submission Management
                'view_submission_all', 'grade_submission_all', 'review_submission_all',
                
                // Enrollment Management
                'view_enrollment_all', 'create_enrollment_all', 'update_enrollment_all', 'manage_enrollment_all',
                
                // Badge Management
                'view_badge_all', 'create_badge_all', 'update_badge_all', 'award_badge_all',
                
                // Media Management
                'view_media_all', 'upload_media_all', 'download_media_all', 'stream_media_all',
                
                // Question Management
                'view_question_all', 'create_question_all', 'update_question_all', 'manage_question_all',
                
                // Communication
                'notify_user_all', 'message_user_all', 'announce_course_all',
                
                // Monitoring & Analytics
                'monitor_user_all', 'track_user_all', 'evaluate_user_all', 'generate_media_all', 'analyze_media_all',
            ];
            $manager->syncPermissions($managerPermissions);
        }

        // Teacher permissions (course and content management)
        if ($teacher) {
            $teacherPermissions = [
                // User Management (self only)
                'view_user_self', 'update_user_self',
                
                // Course Management (assigned courses)
                'view_course_all', 'view_course_assigned', 'update_course_assigned', 'teach_course_assigned',
                
                // Quiz Management (assigned courses)
                'view_quiz_all', 'view_quiz_assigned', 'update_quiz_assigned', 'create_quiz_all', 'update_quiz_all',
                'view_results_quiz_all',
                
                // Assignment Management (assigned courses)
                'view_assignment_all', 'view_assignment_assigned', 'update_assignment_assigned',
                'create_assignment_all', 'update_assignment_all', 'distribute_assignment_all', 'collect_assignment_all',
                
                // Submission Management
                'view_submission_all', 'grade_submission_all', 'review_submission_all', 'approve_submission_all', 'reject_submission_all',
                
                // Enrollment Management (view only)
                'view_enrollment_all',
                
                // Badge Management (award only)
                'view_badge_all', 'award_badge_all',
                
                // Media Management
                'view_media_all', 'upload_media_all', 'download_media_all', 'stream_media_all',
                
                // Question Management
                'view_question_all', 'create_question_all', 'update_question_all', 'manage_question_all',
                
                // Communication
                'notify_user_all', 'message_user_all', 'announce_course_all',
                
                // Monitoring (assigned students)
                'track_user_all', 'evaluate_user_all',
            ];
            $teacher->syncPermissions($teacherPermissions);
        }

        // Student permissions (learning activities)
        if ($student) {
            $studentPermissions = [
                // User Management (self only)
                'view_user_self', 'update_user_self',
                
                // Course Management (enrolled courses)
                'view_course_enrolled', 'attend_course_enrolled',
                
                // Quiz Management (enrolled courses)
                'view_quiz_enrolled', 'take_quiz_enrolled', 'retake_quiz_enrolled',
                'start_quiz_enrolled', 'finish_quiz_enrolled', 'pause_quiz_enrolled', 'resume_quiz_enrolled',
                'view_results_quiz_self',
                
                // Assignment Management (enrolled courses)
                'view_assignment_enrolled',
                
                // Submission Management (own submissions)
                'view_submission_self', 'create_submission_all', 'update_submission_all', 'submit_submission_enrolled',
                
                // Enrollment Management (self only)
                'view_enrollment_self',
                
                // Badge Management (view own badges)
                'view_badge_self', 'earn_badge_all',
                
                // Media Management (view and download)
                'view_media_all', 'download_media_all', 'stream_media_all',
            ];
            $student->syncPermissions($studentPermissions);
        }
    }
}
