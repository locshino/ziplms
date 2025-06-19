<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // --- Core Entities ---
            OrganizationSeeder::class,
            UserSeeder::class,

            // --- Class & Course Structure ---
            ClassesMajorSeeder::class,
            UserClassMajorEnrollmentSeeder::class,
            CourseSeeder::class,
            CourseEnrollmentSeeder::class,
            CourseStaffAssignmentSeeder::class,
            LectureSeeder::class,
            LectureMaterialSeeder::class,

            // --- Questions & Assessments ---
            QuestionSeeder::class, // Handles QuestionChoiceSeeder internally
            AssignmentSeeder::class,
            AssignmentSubmissionSeeder::class,
            AssignmentGradeSeeder::class,
            ExamSeeder::class,
            ExamQuestionSeeder::class,
            ExamAttemptSeeder::class,
            ExamAnswerSeeder::class,

            // --- Scheduling & Events ---
            ScheduleSeeder::class,
            AttendanceSeeder::class,
            EventSeeder::class,

            // --- Communication ---
            ContactMessageSeeder::class,

            // --- Gamification ---
            BadgeSeeder::class,
            UserBadgeSeeder::class,

            // --- Import Management ---
            BatchSeeder::class,
        ]);
    }
}
