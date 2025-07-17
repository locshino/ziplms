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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('todo');
            $table->integer('order_column')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->uuid('assigned_to_id')->nullable();
            $table->timestamps();

            $table->index('assigned_to_id', 'tasks_agn_idx');
            $table->foreign('assigned_to_id')->references('id')
                ->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
