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
        Schema::create('assignment_grades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('submission_id')->unique(); // FK to assignment_submissions table
            $table->decimal('grade')->nullable();
            $table->json('feedback')->nullable(); // Phản hồi (hỗ trợ đa ngôn ngữ).
            $table->uuid('graded_by')->nullable(); // FK to users table
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            $table->foreign('submission_id')->references('id')->on('assignment_submissions')->onDelete('cascade');
            $table->foreign('graded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_grades');
    }
};
