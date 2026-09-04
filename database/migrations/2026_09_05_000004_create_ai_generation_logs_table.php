<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_generation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('seo_keyword_group_id')->nullable()->constrained('seo_keyword_groups')->nullOnDelete();
            $table->foreignId('seo_page_id')->nullable()->constrained('seo_pages')->nullOnDelete();
            $table->string('provider')->default('openrouter');
            $table->string('model');
            $table->string('status')->default('pending');
            $table->unsignedInteger('prompt_tokens')->nullable();
            $table->unsignedInteger('completion_tokens')->nullable();
            $table->unsignedInteger('total_tokens')->nullable();
            $table->decimal('estimated_cost_usd', 8, 5)->nullable();
            $table->longText('prompt')->nullable();
            $table->longText('raw_response')->nullable();
            $table->text('error_message')->nullable();
            $table->foreignId('triggered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_generation_logs');
    }
};
