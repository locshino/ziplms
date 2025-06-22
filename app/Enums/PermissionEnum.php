<?php

namespace App\Enums;

enum PermissionEnum: string
{
    use Concerns\HasEnumValues;

    // ----------------------------------------------------------------
    // Organization Permissions
    // ----------------------------------------------------------------
    case ViewAnyOrganizations = 'view_any_organizations';
    case ViewOrganization = 'view_organization';
    case CreateOrganization = 'create_organization';
    case UpdateOrganization = 'update_organization';
    case DeleteOrganization = 'delete_organization';

    // ----------------------------------------------------------------
    // User Permissions
    // ----------------------------------------------------------------
    case ViewAnyUsers = 'view_any_users';
    case ViewUser = 'view_user';
    case CreateUser = 'create_user';
    case UpdateUser = 'update_user';
    case DeleteUser = 'delete_user';
    case ImportUsers = 'import_users';

    // ----------------------------------------------------------------
    // Role & Permission Management Permissions (for Super Admin)
    // ----------------------------------------------------------------
    case ViewAnyRoles = 'view_any_roles';
    case ViewRole = 'view_role';
    case CreateRole = 'create_role';
    case UpdateRole = 'update_role';
    case DeleteRole = 'delete_role';
    case AssignRole = 'assign_role';
    case ManagePermissions = 'manage_permissions'; // Granting permissions to roles

    // ----------------------------------------------------------------
    // Academic Structure Permissions (classes_majors)
    // ----------------------------------------------------------------
    case ViewAnyClassesMajors = 'view_any_classes_majors';
    case ViewClassMajor = 'view_class_major';
    case CreateClassMajor = 'create_class_major';
    case UpdateClassMajor = 'update_class_major';
    case DeleteClassMajor = 'delete_class_major';
    case EnrollUserInClassMajor = 'enroll_user_in_class_major';

    // ----------------------------------------------------------------
    // Course Permissions
    // ----------------------------------------------------------------
    case ViewAnyCourses = 'view_any_courses';
    case ViewCourse = 'view_course';
    case CreateCourse = 'create_course';
    case UpdateCourse = 'update_course';
    case DeleteCourse = 'delete_course';
    case EnrollStudentInCourse = 'enroll_student_in_course';
    case AssignStaffToCourse = 'assign_staff_to_course';

    // ----------------------------------------------------------------
    // Lecture & Material Permissions
    // ----------------------------------------------------------------
    case ViewAnyLectures = 'view_any_lectures';
    case CreateLecture = 'create_lecture';
    case UpdateLecture = 'update_lecture';
    case DeleteLecture = 'delete_lecture';
    case ManageLectureMaterials = 'manage_lecture_materials';

    // ----------------------------------------------------------------
    // Assessment Permissions
    // ----------------------------------------------------------------
    case ManageQuestionBank = 'manage_question_bank';
    case ManageExams = 'manage_exams';
    case ManageAssignments = 'manage_assignments';
    case GradeSubmissions = 'grade_submissions';

    // ----------------------------------------------------------------
    // Scheduling & Attendance Permissions
    // ----------------------------------------------------------------
    case ViewAnySchedules = 'view_any_schedules';
    case CreateSchedule = 'create_schedule';
    case UpdateSchedule = 'update_schedule';
    case DeleteSchedule = 'delete_schedule';
    case ManageAttendance = 'manage_attendance';

    // ----------------------------------------------------------------
    // System Administration Permissions
    // ----------------------------------------------------------------
    case ViewSystemSettings = 'view_system_settings';
    case UpdateSystemSettings = 'update_system_settings';
    case ViewSystemHealth = 'view_system_health';
    case ManageBackups = 'manage_backups';
    case ViewActivityLogs = 'view_activity_logs';

    // ----------------------------------------------------------------
    // Gamification Permissions
    // ----------------------------------------------------------------
    case ManageBadges = 'manage_badges';
    case AwardBadge = 'award_badge';

    // ----------------------------------------------------------------
    // Reporting Permissions
    // ----------------------------------------------------------------
    case ViewReports = 'view_reports';
}
