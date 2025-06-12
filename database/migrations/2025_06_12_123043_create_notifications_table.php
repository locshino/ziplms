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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type'); // Loại thông báo (để client xử lý).
            $table->json('title'); // Tiêu đề (hỗ trợ đa ngôn ngữ).
            $table->json('content'); // Nội dung (hỗ trợ đa ngôn ngữ).
            $table->string('link')->nullable();
            $table->uuid('sender_id')->nullable(); // FK to users table
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
