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

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('is_featured')->default(false);

            $table->uuid('teacher_id');

            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            $table->string('status');

            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index('teacher_id');
            $table->index('status');
            $table->index('is_featured');
            $table->index(['status', 'start_at']);

            // NOTE: courses has course_documents, course_cover, tags (use spatie media and spatie tags)
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
