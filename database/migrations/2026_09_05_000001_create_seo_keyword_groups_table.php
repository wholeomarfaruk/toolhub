<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_keyword_groups', function (Blueprint $table) {
            $table->id();
            $table->string('tool_slug');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['tool_slug', 'slug']);
            $table->index('tool_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_keyword_groups');
    }
};
