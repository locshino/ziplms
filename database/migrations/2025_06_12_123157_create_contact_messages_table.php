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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sender_id')->nullable(); // FK to users table
            $table->uuid('receiver_id')->nullable(); // FK to users table
            $table->json('subject')->nullable(); // Chủ đề (hỗ trợ đa ngôn ngữ).
            $table->text('message');

            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('sender_id');
            $table->index('receiver_id');
            $table->index('sent_at', 'cm_sent_at_idx');

            $table->foreign('sender_id')->references('id')
                ->on('users')->onDelete('set null');
            $table->foreign('receiver_id')->references('id')
                ->on('users')->onDelete('set null');
            $table->index('sent_at', 'contact_messages_sent_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
