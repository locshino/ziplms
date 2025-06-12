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
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id'); // FK to users table
            $table->uuid('course_id'); // FK to courses table
            $table->timestamp('enrollment_date')->useCurrent();
            $table->decimal('final_grade')->nullable();
            $table->timestamp('completed_at')->nullable();
            // $table->string('status', 50)->nullable(); // (Managed by spatie/laravel-model-states)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->unique(['user_id', 'course_id'], 'course_enrollments_user_id_course_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_enrollments');
    }
};
