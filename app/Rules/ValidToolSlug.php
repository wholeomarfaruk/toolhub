<?php

namespace App\Rules;

use App\Services\ToolRegistry;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidToolSlug implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || app(ToolRegistry::class)->tryFind($value) === null) {
            $fail('The selected tool is not valid.');
        }
    }
}
