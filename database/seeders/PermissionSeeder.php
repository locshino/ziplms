<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Role Management
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            
            // Permission Management
            'view_permissions',
            'create_permissions',
            'edit_permissions',
            'delete_permissions',
            
            // Course Management
            'view_courses',
            'create_courses',
            'edit_courses',
            'delete_courses',
            'manage_own_courses',
            
            // Enrollment Management
            'view_enrollments',
            'create_enrollments',
            'edit_enrollments',
            'delete_enrollments',
            
            // Assignment Management
            'view_assignments',
            'create_assignments',
            'edit_assignments',
            'delete_assignments',
            'grade_assignments',
            
            // Submission Management
            'view_submissions',
            'create_submissions',
            'edit_submissions',
            'delete_submissions',
            
            // Quiz Management
            'view_quizzes',
            'create_quizzes',
            'edit_quizzes',
            'delete_quizzes',
            'take_quizzes',
            
            // Question Management
            'view_questions',
            'create_questions',
            'edit_questions',
            'delete_questions',
            
            // Badge Management
            'view_badges',
            'create_badges',
            'edit_badges',
            'delete_badges',
            'award_badges',
            
            // Reports and Analytics
            'view_reports',
            'export_data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web'],
                ['is_system' => true]
            );
        }
    }
}