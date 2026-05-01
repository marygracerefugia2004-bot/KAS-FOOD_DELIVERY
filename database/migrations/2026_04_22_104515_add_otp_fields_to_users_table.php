<?php

// database/migrations/xxxx_add_otp_fields_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp_code', 6)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            // email_verified_at already exists, we will use it
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp_code', 'otp_expires_at']);
        });
    }
};
