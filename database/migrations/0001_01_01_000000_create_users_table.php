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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id')->nullable(); // FK to organizations table
            $table->string('code')->nullable(); // Mã định danh.
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number', 50)->nullable();
            $table->text('address')->nullable();
            // $table->string('profile_picture_path')->nullable(); // (Managed by spatie/laravel-medialibrary)
            // $table->text('two_factor_secret')->nullable(); // (Managed by spatie/laravel-one-time-password)
            // $table->text('two_factor_recovery_codes')->nullable(); // (Managed by spatie/laravel-one-time-password)
            // $table->timestamp('two_factor_confirmed_at')->nullable(); // (Managed by spatie/laravel-one-time-password)
            $table->timestamp('last_login_at')->nullable(); // Thời điểm đăng nhập cuối cùng.
            $table->timestamp('email_verified_at')->nullable();
            // $table->string('status', 50)->nullable(); // (Managed by spatie/laravel-model-states)
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
            $table->unique(['organization_id', 'code'], 'users_code_organization_unique'); // Mã unique trong tổ chức
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
