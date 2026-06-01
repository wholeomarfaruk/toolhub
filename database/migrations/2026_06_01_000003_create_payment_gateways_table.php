<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();

            // Gateway info
            $table->string('name'); // NOWPayments, SSL Commerz, etc.
            $table->string('slug')->unique(); // nowpayments, ssl_commerz
            $table->text('description')->nullable();

            // Gateway status
            $table->boolean('is_active')->default(false);
            $table->enum('environment', ['sandbox', 'production'])->default('sandbox');

            // Configuration (stored as JSON)
            $table->json('config'); // API keys, secrets, etc.

            // Display settings
            $table->string('icon_url')->nullable(); // Payment gateway logo
            $table->integer('sort_order')->default(0);

            // Tracking
            $table->integer('total_transactions')->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamp('last_used_at')->nullable();

            $table->timestamps();
            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
