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
            $table->json('name'); // Multilingual name.
            $table->string('code')->nullable();
            $table->json('description')->nullable(); // Multilingual description.
            // $table->string('image_path')->nullable(); // (Managed by spatie/laravel-medialibrary)
            $table->uuid('parent_id')->nullable(); // FK to courses table (self-referencing)
            $table->uuid('created_by')->nullable(); // FK to users table

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default(App\States\Active::class);
            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');
            $table->index('parent_id');
            $table->index('created_by');
            $table->index('code');

            $table->foreign('organization_id')->references('id')
                ->on('organizations')->onDelete('set null');
            $table->foreign('parent_id')->references('id')
                ->on('courses')->onDelete('set null');
            $table->foreign('created_by')->references('id')
                ->on('users')->onDelete('set null');
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
