<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions from PermissionEnum
        foreach (PermissionEnum::cases() as $permission) {
            Permission::findOrCreate($permission->value, 'web');
        }

        $this->command->info('Permissions created successfully!');

        // Assign permissions to roles
        $adminRole = Role::findByName(RoleEnum::Admin->value);
        $managerRole = Role::findByName(RoleEnum::Manager->value);
        $teacherRole = Role::findByName(RoleEnum::Teacher->value);
        $studentRole = Role::findByName(RoleEnum::Student->value);

        // --- Assign all permissions to Admin ---
        $adminRole->givePermissionTo(PermissionEnum::cases());
        $this->command->info('Admin permissions assigned.');

        // --- Assign permissions to Manager ---
        $managerRole->givePermissionTo([
            // User Management
            PermissionEnum::ViewAnyUsers,
            PermissionEnum::ViewUser,
            PermissionEnum::CreateUser,
            PermissionEnum::UpdateUser,
            PermissionEnum::DeleteUser,
            PermissionEnum::ImportUsers,

            // Role viewing & assignment (but not creation/deletion)
            PermissionEnum::ViewAnyRoles,
            PermissionEnum::ViewRole,
            PermissionEnum::AssignRole,

            // Academic Structure
            PermissionEnum::ViewAnyClassesMajors,
            PermissionEnum::ViewClassMajor,
            PermissionEnum::CreateClassMajor,
            PermissionEnum::UpdateClassMajor,
            PermissionEnum::DeleteClassMajor,
            PermissionEnum::EnrollUserInClassMajor,

            // Course Management
            PermissionEnum::ViewAnyCourses,
            PermissionEnum::ViewCourse,
            PermissionEnum::CreateCourse,
            PermissionEnum::UpdateCourse,
            PermissionEnum::DeleteCourse,
            PermissionEnum::EnrollStudentInCourse,
            PermissionEnum::AssignStaffToCourse,

            // Scheduling & Attendance
            PermissionEnum::ViewAnySchedules,
            PermissionEnum::CreateSchedule,
            PermissionEnum::UpdateSchedule,
            PermissionEnum::DeleteSchedule,
            PermissionEnum::ManageAttendance,

            // Reporting
            PermissionEnum::ViewReports,
        ]);
        $this->command->info('Manager permissions assigned.');

        // --- Assign permissions to Teacher ---
        $teacherRole->givePermissionTo([
            // Can view users in their context (e.g., their students)
            PermissionEnum::ViewAnyUsers,
            PermissionEnum::ViewUser,

            // Course & Content Management for their courses
            PermissionEnum::ViewAnyCourses,
            PermissionEnum::ViewCourse,
            PermissionEnum::UpdateCourse, // Can update courses they are assigned to
            PermissionEnum::ViewAnyLectures,
            PermissionEnum::CreateLecture,
            PermissionEnum::UpdateLecture,
            PermissionEnum::DeleteLecture,
            PermissionEnum::ManageLectureMaterials,

            // Assessment
            PermissionEnum::ManageQuestionBank, // Often teachers contribute to question banks
            PermissionEnum::ManageExams,
            PermissionEnum::ManageAssignments,
            PermissionEnum::GradeSubmissions,

            // Scheduling & Attendance for their classes/courses
            PermissionEnum::ViewAnySchedules,
            PermissionEnum::CreateSchedule,
            PermissionEnum::UpdateSchedule,
            PermissionEnum::DeleteSchedule,
            PermissionEnum::ManageAttendance,
        ]);
        $this->command->info('Teacher permissions assigned.');

        // --- Student Permissions ---
        $studentRole->givePermissionTo([
            PermissionEnum::ViewAnyCourses, // To see the course catalog
            PermissionEnum::ViewAnySchedules, // To see their own schedule
        ]);
        $this->command->info('Student permissions assigned.');
    }
}
