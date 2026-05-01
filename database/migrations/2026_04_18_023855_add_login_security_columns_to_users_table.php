<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('users', 'login_attempts')) {
            return;
        }
        Schema::table('users', function (Blueprint $table) {
            $table->integer('login_attempts')->default(0)->after('remember_token');
            $table->timestamp('last_login_at')->nullable()->after('login_attempts');
            $table->timestamp('locked_until')->nullable()->after('last_login_at');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['login_attempts', 'last_login_at', 'locked_until']);
        });
    }
};
