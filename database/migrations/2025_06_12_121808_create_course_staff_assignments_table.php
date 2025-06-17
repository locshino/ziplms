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
        Schema::create('course_staff_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id'); // FK to users table
            $table->uuid('course_id'); // FK to courses table
            // $table->string('role_in_course')->nullable(); // Managed by plugin filament-spatie-tags

            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('course_id');
            $table->unique([
                'user_id',
                'course_id',
            ], 'user_course_role_unique');

            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_staff_assignments');
    }
};
