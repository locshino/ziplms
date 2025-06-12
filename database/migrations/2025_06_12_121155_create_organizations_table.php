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
         Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name'); // Tên tổ chức (hỗ trợ đa ngôn ngữ).
            $table->string('slug')->unique()->nullable(); // Chuỗi định danh duy nhất, thân thiện URL.
            $table->string('type')->nullable(); // Ví dụ: 'high_school', 'college', 'university', 'training_center'.
            $table->json('address')->nullable(); // Địa chỉ (hỗ trợ đa ngôn ngữ).
            $table->string('phone_number', 50)->nullable();
            // $table->string('logo_path')->nullable(); // (Managed by spatie/laravel-medialibrary via spatie/laravel-settings)
            $table->json('settings')->nullable(); // Các cài đặt riêng của tổ chức (hoặc dùng spatie/laravel-settings).
            // $table->string('status', 50)->nullable(); // (Managed by spatie/laravel-model-states)
            $table->timestamps();
            $table->softDeletes(); // Hỗ trợ soft delete.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
