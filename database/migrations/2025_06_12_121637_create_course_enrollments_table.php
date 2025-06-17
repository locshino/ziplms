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
            $table->decimal('final_grade')->nullable();
            $table->string('status')->default(App\States\Active::class);

            $table->timestamp('enrollment_date')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('course_id');
            $table->unique([
                'user_id',
                'course_id',
            ], 'course_enrollments_user_id_course_id_unique');

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
        Schema::dropIfExists('course_enrollments');
    }
};
