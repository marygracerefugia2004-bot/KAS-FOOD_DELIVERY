<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'vehicle_type')) {
                return;
            }

            $table->string('vehicle_type')->nullable()->after('is_available');
            $table->string('vehicle_model')->nullable()->after('vehicle_type');
            $table->string('license_plate')->nullable()->after('vehicle_model');
            $table->string('driver_license_number')->nullable()->after('license_plate');
            $table->date('driver_license_expiry')->nullable()->after('driver_license_number');
            $table->integer('years_of_experience')->default(0)->after('driver_license_expiry');

            $table->decimal('total_earnings', 10, 2)->default(0)->after('years_of_experience');
            $table->decimal('available_balance', 10, 2)->default(0)->after('total_earnings');
            $table->decimal('pending_balance', 10, 2)->default(0)->after('available_balance');

            $table->date('last_service_date')->nullable()->after('pending_balance');
            $table->date('next_service_due')->nullable()->after('last_service_date');
            $table->integer('service_interval_km')->nullable()->after('next_service_due');
            $table->integer('last_service_km')->nullable()->after('service_interval_km');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_type', 'vehicle_model', 'license_plate',
                'driver_license_number', 'driver_license_expiry', 'years_of_experience',
                'total_earnings', 'available_balance', 'pending_balance',
                'last_service_date', 'next_service_due', 'service_interval_km', 'last_service_km',
            ]);
        });
    }
};
