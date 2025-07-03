<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateFilamentExportsTable extends Migration
{
    public function up()
    {
        Schema::create('filament_exports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('exporter');
            $table->integer('total_rows')->default(0);
            $table->string('file_disk')->default('local');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('filament_exports');
    }
}
