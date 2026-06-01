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
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            // Feature key: e.g. 'pdf_export', 'daily_invoice_limit'
            $table->string('key', 80);
            // Value: 'true', 'false', '20', 'unlimited'
            // Stored as string to support booleans AND numeric quotas in one column.
            $table->string('value', 50)->default('false');

            $table->unique(['plan_id', 'key']);
            $table->index('key'); // query all plans that have a given feature
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_features');
    }
};
