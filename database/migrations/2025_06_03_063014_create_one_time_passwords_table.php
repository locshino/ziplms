<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $table_name = config('one-time-passwords.table_name') ?? 'one_time_passwords';

        Schema::create($table_name, function (Blueprint $table) {
            $table->id();

            $table->string('password');
            $table->json('origin_properties')->nullable();

            $table->dateTime('expires_at');
            $table->morphs('authenticatable', 'otp_authenticatable_index');

            $table->timestamps();
        });
    }

    public function down()
    {
        $table_name = config('one-time-passwords.table_name') ?? 'one_time_passwords';
        Schema::dropIfExists($table_name);
    }
};
