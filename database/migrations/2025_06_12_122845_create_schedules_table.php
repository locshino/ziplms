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
        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuidMorphs('schedulable'); // Model liên quan (Course, Lecture, ClassesMajor).
            $table->json('title'); // Tiêu đề buổi học (hỗ trợ đa ngôn ngữ).
            $table->json('description')->nullable(); // Mô tả (hỗ trợ đa ngôn ngữ).
            $table->uuid('assigned_teacher_id')->nullable(); // FK to users table
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('location_type')->nullable(); // Ví dụ: 'online', 'offline_room', 'virtual_classroom'.
            $table->text('location_details')->nullable(); // Chi tiết địa điểm/link họp.
            $table->uuid('created_by')->nullable(); // FK to users table
            // $table->string('status', 50)->nullable(); // (Managed by spatie/laravel-model-states)
            $table->timestamps();
            $table->softDeletes();
            $table->index('assigned_teacher_id');
            $table->index('created_by');
            $table->index('start_time', 's_start_time_idx');
            $table->foreign('assigned_teacher_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
