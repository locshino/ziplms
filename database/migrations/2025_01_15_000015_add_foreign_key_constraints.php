<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Courses & Content
        Schema::table('courses', function (Blueprint $table) {
            $table->foreign('teacher_id', 'fk_courses_teacher_id')
                ->references('id')->on('users')
                ->onDelete('restrict');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreign('course_id', 'fk_enrollments_course_id')
                ->references('id')->on('courses')
                ->onDelete('cascade');
            $table->foreign('student_id', 'fk_enrollments_student_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        // Assignments & Submissions
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreign('course_id', 'fk_assignments_course_id')
                ->references('id')->on('courses')
                ->onDelete('cascade');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->foreign('assignment_id', 'fk_submissions_assignment_id')
                ->references('id')->on('assignments')
                ->onDelete('cascade');
            $table->foreign('student_id', 'fk_submissions_student_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('graded_by', 'fk_submissions_graded_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });

        // Quizzes
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreign('course_id', 'fk_quizzes_course_id')
                ->references('id')->on('courses')
                ->onDelete('cascade');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('quiz_id', 'fk_questions_quiz_id')
                ->references('id')->on('quizzes')
                ->onDelete('cascade');
        });

        Schema::table('answer_choices', function (Blueprint $table) {
            $table->foreign('question_id', 'fk_answer_choices_question_id')
                ->references('id')->on('questions')
                ->onDelete('cascade');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->foreign('quiz_id', 'fk_quiz_attempts_quiz_id')
                ->references('id')->on('quizzes')
                ->onDelete('cascade');
            $table->foreign('student_id', 'fk_quiz_attempts_student_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        Schema::table('student_quiz_answers', function (Blueprint $table) {
            $table->foreign('quiz_attempt_id', 'fk_sqa_attempt_id')
                ->references('id')->on('quiz_attempts')
                ->onDelete('cascade');
            $table->foreign('question_id', 'fk_sqa_question_id')
                ->references('id')->on('questions')
                ->onDelete('cascade');
            $table->foreign('answer_choice_id', 'fk_sqa_answer_choice_id')
                ->references('id')->on('answer_choices')
                ->onDelete('cascade');
        });

        // Badges & Gamification
        Schema::table('badge_has_conditions', function (Blueprint $table) {
            $table->foreign('badge_id', 'fk_bhc_badge_id')
                ->references('id')->on('badges')
                ->onDelete('cascade');
            $table->foreign('condition_id', 'fk_bhc_condition_id')
                ->references('id')->on('badge_conditions')
                ->onDelete('cascade');
        });

        Schema::table('user_badges', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_user_badges_user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('badge_id', 'fk_user_badges_badge_id')
                ->references('id')->on('badges')
                ->onDelete('cascade');
        });

        // Add foreign key for sessions table
        Schema::table('sessions', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys in reverse order
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_badges', function (Blueprint $table) {
            $table->dropForeign('fk_user_badges_user_id');
            $table->dropForeign('fk_user_badges_badge_id');
        });

        Schema::table('badge_has_conditions', function (Blueprint $table) {
            $table->dropForeign('fk_bhc_badge_id');
            $table->dropForeign('fk_bhc_condition_id');
        });

        Schema::table('student_quiz_answers', function (Blueprint $table) {
            $table->dropForeign('fk_sqa_attempt_id');
            $table->dropForeign('fk_sqa_question_id');
            $table->dropForeign('fk_sqa_answer_choice_id');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropForeign('fk_quiz_attempts_quiz_id');
            $table->dropForeign('fk_quiz_attempts_student_id');
        });

        Schema::table('answer_choices', function (Blueprint $table) {
            $table->dropForeign('fk_answer_choices_question_id');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign('fk_questions_quiz_id');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign('fk_quizzes_course_id');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign('fk_submissions_assignment_id');
            $table->dropForeign('fk_submissions_student_id');
            $table->dropForeign('fk_submissions_graded_by');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign('fk_assignments_course_id');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign('fk_enrollments_course_id');
            $table->dropForeign('fk_enrollments_student_id');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign('fk_courses_teacher_id');
        });
    }
};
