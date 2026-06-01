<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolUsage extends Model
{
    const UPDATED_AT = null;

    protected $fillable = ['user_id', 'tool_slug'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
