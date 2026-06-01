<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->unsignedBigInteger('plan_id');

            // NOWPayments invoice details
            $table->string('nowpayments_invoice_id')->nullable()->unique();
            $table->integer('amount'); // in USD cents (299 = $2.99)
            $table->string('currency')->default('USD');

            // Billing info
            $table->enum('billing_period', ['monthly', 'yearly'])->default('monthly');

            // Crypto payment details
            $table->decimal('crypto_amount', 20, 8)->nullable();
            $table->string('crypto_currency')->nullable(); // BTC, ETH, USDT, etc.
            $table->string('payment_address')->nullable(); // Wallet address

            // Payment status
            $table->enum('status', ['pending', 'completed', 'failed', 'expired', 'refunded'])
                ->default('pending');

            // Payment URLs & tracking
            $table->text('payment_url')->nullable();
            $table->string('tx_hash')->nullable(); // Blockchain transaction hash
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('restrict');

            $table->timestamps();
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
