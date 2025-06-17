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
        Schema::create('badges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id')->nullable(); // FK to organizations table
            $table->json('name'); // Tên huy hiệu (hỗ trợ đa ngôn ngữ).
            $table->json('description')->nullable(); // Mô tả (hỗ trợ đa ngôn ngữ).
            // $table->string('image_path')->nullable(); // (Managed by spatie/laravel-medialibrary)
            $table->json('criteria_description')->nullable(); // Mô tả điều kiện (hỗ trợ đa ngôn ngữ).
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');

            $table->foreign('organization_id')->references('id')
                ->on('organizations')->onDelete('set null');
            // Consider unique key for (organization_id, name->'$.en') if name needs to be unique per org
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
