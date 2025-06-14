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
        Schema::create('lectures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id'); // FK to courses table
            $table->json('title'); // Tiêu đề (hỗ trợ đa ngôn ngữ).
            $table->json('description')->nullable(); // Mô tả/Nội dung (hỗ trợ đa ngôn ngữ).
            $table->unsignedInteger('lecture_order')->default(0); // Thứ tự trong môn học.
            $table->string('duration_estimate')->nullable(); // Thời lượng dự kiến (ví dụ: "30 phút", "2 giờ").
            $table->uuid('created_by')->nullable(); // FK to users table
            // $table->string('status', 50)->nullable(); // (Managed by spatie/laravel-model-states)
            $table->timestamps();
            $table->softDeletes();
            $table->index('course_id');
            $table->index('created_by');
            $table->index('lecture_order');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index('lecture_order', 'lectures_lecture_order_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
