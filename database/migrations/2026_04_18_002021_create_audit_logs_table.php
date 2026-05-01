<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('audit_logs')) {
            return;
        }
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('role')->nullable();
            $table->string('action');
            $table->text('description');
            $table->string('ip_address');
            $table->text('user_agent');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamp('created_at')->useCurrent();
            // No updated_at — immutable log
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
