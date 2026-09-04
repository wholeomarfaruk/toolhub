<?php

namespace App\Models;

use App\Contracts\ToolContract;
use App\Services\ToolRegistry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_slug',
        'seo_keyword_id',
        'seo_keyword_group_id',
        'slug',
        'meta_title',
        'meta_description',
        'h1',
        'variables',
        'tool_preset',
        'intro',
        'content',
        'faqs',
        'examples',
        'status',
        'is_indexable',
        'published_at',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'tool_preset' => 'array',
            'faqs' => 'array',
            'examples' => 'array',
            'is_indexable' => 'boolean',
            'published_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function keyword(): BelongsTo
    {
        return $this->belongsTo(SeoKeyword::class, 'seo_keyword_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(SeoKeywordGroup::class, 'seo_keyword_group_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function tool(): ?ToolContract
    {
        return app(ToolRegistry::class)->tryFind($this->tool_slug);
    }

    public function url(): string
    {
        return route('tools.seo-pages.show', ['tool_slug' => $this->tool_slug, 'seo_page_slug' => $this->slug]);
    }
}
