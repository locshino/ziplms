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
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id')->nullable(); // FK to courses table
            $table->uuid('lecture_id')->nullable(); // FK to lectures table
            $table->json('title'); // Tiêu đề (hỗ trợ đa ngôn ngữ).
            $table->json('description')->nullable(); // Mô tả (hỗ trợ đa ngôn ngữ).
            $table->dateTime('start_time')->nullable(); // Thời gian bắt đầu làm bài.
            $table->dateTime('end_time')->nullable(); // Thời gian kết thúc làm bài.
            $table->unsignedInteger('duration_minutes')->nullable(); // Thời gian làm bài.
            $table->unsignedInteger('max_attempts')->default(1); // Số lần được phép làm bài.
            $table->decimal('passing_score')->nullable(); // Điểm đạt.
            $table->boolean('shuffle_questions')->default(false); // Xáo trộn câu hỏi.
            $table->boolean('shuffle_answers')->default(false); // Xáo trộn đáp án.
            $table->string('show_results_after')->nullable(); // Khi nào hiển thị kết quả.
            $table->uuid('created_by')->nullable(); // FK to users table
            // $table->string('status', 50)->nullable(); // (Managed by spatie/laravel-model-states)
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
            $table->foreign('lecture_id')->references('id')->on('lectures')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index('start_time', 'exams_start_time_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
