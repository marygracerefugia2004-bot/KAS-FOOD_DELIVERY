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
        Schema::create('vehicle_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->string('service_type'); // oil_change, tire_rotation, brake_service, etc.
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->integer('odometer_km')->nullable();
            $table->date('service_date');
            $table->date('next_service_due')->nullable();
            $table->string('service_center')->nullable();
            $table->string('receipt_image')->nullable();
            $table->timestamps();

            $table->index(['driver_id', 'service_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenances');
    }
};
