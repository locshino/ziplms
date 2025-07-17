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
        // Get the table name from the webpush config, defaulting if not set.
        $tableName = config('webpush.table_name', 'push_subscriptions');
        // Get the database connection from the webpush config, defaulting if not set.
        $connection = config('webpush.database_connection');

        Schema::connection($connection)->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('id');

            // Instead of $table->uuidMorphs('subscribable');
            // We manually define the columns and the index with a shorter name
            $table->string('subscribable_type'); // Stores the model class name
            $table->uuid('subscribable_id');     // Stores the UUID of the subscribable model

            // Define a custom, shorter index name for the morphs columns.
            // This prevents the "Identifier name too long" error.
            $table->index(['subscribable_type', 'subscribable_id'], 'subscribable_idx');

            $table->string('endpoint', 500)->unique(); // Endpoint should be unique
            $table->string('public_key')->nullable();
            $table->string('auth_token')->nullable();
            $table->string('content_encoding')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('webpush.database_connection'))->dropIfExists(config('webpush.table_name'));
    }
};
