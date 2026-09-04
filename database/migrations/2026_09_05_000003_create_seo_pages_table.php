<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('tool_slug');
            $table->foreignId('seo_keyword_id')->nullable()->constrained('seo_keywords')->nullOnDelete();
            $table->foreignId('seo_keyword_group_id')->nullable()->constrained('seo_keyword_groups')->nullOnDelete();
            $table->string('slug');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('h1')->nullable();
            $table->json('variables')->nullable();
            $table->json('tool_preset')->nullable();
            $table->text('intro')->nullable();
            $table->longText('content')->nullable();
            $table->json('faqs')->nullable();
            $table->json('examples')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_indexable')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->unique(['tool_slug', 'slug']);
            $table->index(['tool_slug', 'status']);
            $table->index(['status', 'is_indexable']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_pages');
    }
};
