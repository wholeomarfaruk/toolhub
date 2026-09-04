<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeoKeywordGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_slug',
        'name',
        'slug',
        'description',
    ];

    public function keywords(): HasMany
    {
        return $this->hasMany(SeoKeyword::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(SeoPage::class);
    }
}
