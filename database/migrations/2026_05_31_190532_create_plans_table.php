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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Free, Pro, Enterprise
            $table->string('slug')->unique();               // free, pro, enterprise (matches PlanTier enum)
            $table->string('description')->nullable();
            $table->unsignedInteger('price_monthly');       // cents: 0, 299, 799
            $table->unsignedInteger('price_yearly')->nullable(); // cents, discounted annual
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('sort_order')->default(0); // display order
            $table->timestamps();

            $table->index(['slug', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
