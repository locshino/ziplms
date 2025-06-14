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
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('schedule_id'); // FK to schedules table
            $table->uuid('user_id'); // FK to users table
            $table->timestamp('attended_at')->useCurrent(); // Thời điểm điểm danh.
            $table->json('notes')->nullable(); // Ghi chú (hỗ trợ đa ngôn ngữ).
            $table->uuid('marked_by')->nullable(); // FK to users table
            // $table->string('status', 50)->nullable(); // (Managed by spatie/laravel-model-states)
            $table->timestamps();
            $table->index('schedule_id');
            $table->index('user_id');
            $table->index('marked_by');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('marked_by')->references('id')->on('users')->onDelete('set null');
            $table->unique(['schedule_id', 'user_id'], 'attendance_schedule_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
