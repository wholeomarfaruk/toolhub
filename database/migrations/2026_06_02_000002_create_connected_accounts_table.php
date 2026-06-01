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
        Schema::create('connected_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('provider'); // 'google', 'github', 'twitter', etc.
            $table->string('provider_id'); // OAuth provider's unique ID
            $table->string('provider_email')->nullable();
            $table->json('provider_data')->nullable(); // Store extra data from provider
            $table->timestamps();

            // Ensure one connection per provider per user
            $table->unique(['user_id', 'provider']);
            // Ensure provider_id is unique across all users (prevent account takeover)
            $table->unique(['provider', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connected_accounts');
    }
};
