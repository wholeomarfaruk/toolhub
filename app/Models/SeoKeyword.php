<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SeoKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_slug',
        'seo_keyword_group_id',
        'keyword',
        'search_intent',
        'country',
        'language',
        'priority',
        'status',
        'source',
        'search_volume',
        'notes',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            $model->keyword_normalized = static::normalize($model->keyword);
        });
    }

    public static function normalize(string $keyword): string
    {
        return Str::of($keyword)->lower()->squish()->value();
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(SeoKeywordGroup::class, 'seo_keyword_group_id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(SeoPage::class);
    }
}
