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
        Schema::create('driver_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->decimal('order_amount', 10, 2);
            $table->decimal('commission_percent', 5, 2)->default(20);
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->date('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();

            $table->index(['driver_id', 'status']);
            $table->unique('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_earnings');
    }
};
