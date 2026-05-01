<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            return;
        }
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('food_id')->constrained('foods')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'assigned', 'out_for_delivery', 'delivered', 'cancelled'])->default('pending');
            $table->text('delivery_address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('driver_latitude', 10, 8)->nullable();
            $table->decimal('driver_longitude', 11, 8)->nullable();
            $table->timestamp('estimated_arrival')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->string('promo_code')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('delivery_proof')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
