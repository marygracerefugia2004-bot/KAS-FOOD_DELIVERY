<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'accepted_at')) {
                $table->timestamp('accepted_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('accepted_at');
            }
            if (! Schema::hasColumn('orders', 'driver_latitude')) {
                $table->decimal('driver_latitude', 10, 8)->nullable()->after('delivery_address');
            }
            if (! Schema::hasColumn('orders', 'driver_longitude')) {
                $table->decimal('driver_longitude', 11, 8)->nullable()->after('driver_latitude');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['accepted_at', 'delivered_at', 'driver_latitude', 'driver_longitude']);
        });
    }
};
