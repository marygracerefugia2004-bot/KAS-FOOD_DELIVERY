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
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // bank_transfer, gcash, etc.
            $table->string('payment_details'); // account number, etc.
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['driver_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
