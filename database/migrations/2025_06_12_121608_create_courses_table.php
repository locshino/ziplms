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
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id')->nullable(); // FK to organizations table
            $table->json('name'); // Tên môn học (hỗ trợ đa ngôn ngữ).
            $table->string('code')->nullable(); // Mã môn học (có thể unique trong tổ chức).
            $table->json('description')->nullable(); // Mô tả (hỗ trợ đa ngôn ngữ).
            // $table->string('image_path')->nullable(); // (Managed by spatie/laravel-medialibrary)
            $table->uuid('parent_id')->nullable(); // FK to courses table (self-referencing)
            $table->uuid('created_by')->nullable(); // FK to users table
            $table->date('start_date')->nullable(); // Ngày bắt đầu dự kiến của khóa học.
            $table->date('end_date')->nullable(); // Ngày kết thúc dự kiến của khóa học.
            // $table->string('status', 50)->nullable(); // (Managed by spatie/laravel-model-states)
            $table->timestamps();
            $table->softDeletes();
            $table->index('organization_id');
            $table->index('parent_id');
            $table->index('created_by');
            $table->index('code');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('courses')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
