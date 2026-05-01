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
        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('direction', ['driver_to_admin', 'admin_to_driver'])->default('driver_to_admin');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['driver_id', 'is_read']);
            $table->index(['driver_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_messages');
    }
};
