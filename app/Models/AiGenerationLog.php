<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiGenerationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'seo_keyword_group_id',
        'seo_page_id',
        'provider',
        'model',
        'status',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'estimated_cost_usd',
        'prompt',
        'raw_response',
        'error_message',
        'triggered_by',
    ];

    public function seoPage(): BelongsTo
    {
        return $this->belongsTo(SeoPage::class, 'seo_page_id');
    }

    public function keywordGroup(): BelongsTo
    {
        return $this->belongsTo(SeoKeywordGroup::class, 'seo_keyword_group_id');
    }

    public function triggeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }
}
