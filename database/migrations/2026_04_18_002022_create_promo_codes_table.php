<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('promo_codes')) {
            return;
        }
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount_percent', 5, 2);
            $table->integer('max_uses')->default(100);
            $table->integer('used_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
