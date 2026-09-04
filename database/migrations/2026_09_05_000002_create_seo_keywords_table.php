<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('tool_slug');
            $table->foreignId('seo_keyword_group_id')->nullable()->constrained('seo_keyword_groups')->nullOnDelete();
            $table->string('keyword');
            $table->string('keyword_normalized');
            $table->string('search_intent')->default('informational');
            $table->string('country', 2)->nullable();
            $table->string('language', 5)->default('en');
            $table->unsignedTinyInteger('priority')->default(3);
            $table->string('status')->default('pending');
            $table->string('source')->default('manual');
            $table->unsignedInteger('search_volume')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['tool_slug', 'keyword_normalized', 'country', 'language'], 'seo_keywords_tool_kw_country_lang_unique');
            $table->index(['tool_slug', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_keywords');
    }
};
