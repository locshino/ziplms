<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('status'); // Trạng thái (sẽ được quản lý bởi spatie/laravel-model-states)
            $table->string('title'); // Tiêu đề thông báo
            $table->text('body'); // Nội dung thông báo
            $table->json('data')->nullable(); // Lưu trữ dữ liệu bổ sung, ví dụ: link, icon
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
