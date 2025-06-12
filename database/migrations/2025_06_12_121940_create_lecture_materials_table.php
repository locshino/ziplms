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
       Schema::create('lecture_materials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lecture_id'); // FK to lectures table
            $table->json('name'); // Tên tài liệu (hỗ trợ đa ngôn ngữ).
            $table->json('description')->nullable(); // Mô tả (hỗ trợ đa ngôn ngữ).
            $table->uuid('uploaded_by')->nullable(); // FK to users table
            // Files will be handled by spatie/laravel-medialibrary and associated with this model
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lecture_id')->references('id')->on('lectures')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_materials');
    }
};
