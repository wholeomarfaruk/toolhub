<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenRouterService
{
    public function chatJson(string $systemPrompt, string $userPrompt, ?string $model = null): array
    {
        $apiKey = config('services.openrouter.api_key');
        if (!$apiKey) {
            throw new RuntimeException('OpenRouter API key is not configured.');
        }

        $response = Http::withToken($apiKey)
            ->timeout((int) config('services.openrouter.timeout', 60))
            ->baseUrl(config('services.openrouter.base_url'))
            ->post('/chat/completions', [
                'model' => $model ?: config('services.openrouter.default_model'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'response_format' => ['type' => 'json_object'],
                'max_tokens' => (int) config('services.openrouter.max_tokens', 2000),
            ]);

        if ($response->failed()) {
            throw new RuntimeException('OpenRouter request failed: ' . $response->body());
        }

        $body = $response->json();
        $content = $body['choices'][0]['message']['content'] ?? null;
        $decoded = $content ? json_decode($content, true) : null;

        if (json_last_error() !== JSON_ERROR_NONE || $decoded === null) {
            throw new RuntimeException('OpenRouter returned invalid JSON content.');
        }

        return [
            'content' => $decoded,
            'usage' => $body['usage'] ?? [],
            'model' => $body['model'] ?? ($model ?: config('services.openrouter.default_model')),
            'raw' => $body,
        ];
    }

    public function testConnection(): bool
    {
        try {
            $this->chatJson('You are a test.', 'Reply with {"ok": true} as JSON.');
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
