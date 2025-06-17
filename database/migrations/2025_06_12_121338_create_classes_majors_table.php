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
        Schema::create('classes_majors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id'); // FK to organizations table
            $table->json('name'); // Multilingual description.
            $table->string('code')->nullable();
            $table->json('description')->nullable(); // Multilingual description.
            // $table->string('type'); // Managed by plugin filament-spatie-tags
            $table->uuid('parent_id')->nullable(); // FK to classes_majors table (self-referencing)

            $table->timestamps();
            $table->softDeletes();

            // Indexes and unique constraints
            $table->index('organization_id');
            $table->index('parent_id');
            $table->index('code');

            // Foreign key constraints
            $table->foreign('organization_id')->references('id')
                ->on('organizations')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')
                ->on('classes_majors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes_majors');
    }
};
