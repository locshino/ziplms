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
            $table->string('code')->nullable();
            $table->string('name');
            // $table->string('profile_picture_path')->nullable(); // (Managed by spatie/laravel-medialibrary)
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number', 50)->nullable();
            $table->text('address')->nullable();

            // Timestamps and soft deletes
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->string('status')->default(App\States\Active::class);

            // Indexes and unique constraints
            $table->index('status');
            $table->unique([
                'code',
            ], 'unique_code');
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
