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
            $table->uuid('assigned_id')->nullable(); // FK to users table
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->uuid('location_id')->nullable();
            $table->uuid('created_by')->nullable(); // FK to users table
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();

            $table->index('assigned_id', 'asg_idx');
            $table->index('location_id', 'lct_idx');
            $table->index('created_by', 'crt_b_idx');
            $table->index('start_time', 'srt_tm_idx');

            $table->foreign('assigned_id')->references('id')
                ->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')
                ->on('users')->onDelete('set null');
            $table->foreign('location_id')->references('id')
                ->on('locations')->onDelete('set null');
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
