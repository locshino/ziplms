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
        Schema::create('user_class_major_enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id'); // FK to users table
            $table->uuid('class_major_id'); // FK to classes_majors table
            $table->string('enrollment_type')->nullable(); // Ví dụ: 'student', 'homeroom_teacher', 'subject_teacher', 'dean', 'member'.
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('class_major_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_major_id')->references('id')->on('classes_majors')->onDelete('cascade');

            $table->unique(['user_id', 'class_major_id', 'enrollment_type'], 'user_class_major_enrollment_unique');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_class_major_enrollments');
    }
};
