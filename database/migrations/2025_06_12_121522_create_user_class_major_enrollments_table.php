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
            // $table->string('enrollment_type')->nullable(); // Managed by plugin filament-spatie-tags

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes and unique constraints
            $table->index('user_id', 'u_c_m_e_user_id_index');
            $table->index('class_major_id', 'u_c_m_e_class_major_id_index');
            $table->unique([
                'user_id',
                'class_major_id',
                'deleted_at',
            ], 'u_c_m_e_unique');

            // Foreign key constraints
            $table->foreign('user_id', 'u_c_m_e_user_fk')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('class_major_id', 'u_c_m_e_class_major_fk')->references('id')
                ->on('classes_majors')->onDelete('cascade');
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
