<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class AiProviderService
{
    /**
     * Send a chat completion request to the given provider, expecting structured JSON output.
     *
     * Returns ['content' => array, 'usage' => array, 'model' => string, 'raw' => array, 'provider' => string].
     */
    public function chatJson(string $providerSlug, string $systemPrompt, string $userPrompt, ?string $model = null): array
    {
        $provider = AiProvider::bySlug($providerSlug);
        if (!$provider || !$provider->is_active) {
            throw new RuntimeException("AI provider [{$providerSlug}] is not configured or not active.");
        }

        $apiKey = $provider->getConfig('api_key');
        if (!$apiKey) {
            throw new RuntimeException("AI provider [{$providerSlug}] has no API key configured.");
        }

        $resolvedModel = $model ?: $provider->getConfig('default_model');
        $maxTokens = (int) $provider->getConfig('max_tokens', 2000);
        $timeout = (int) $provider->getConfig('timeout', 60);
        $baseUrl = $provider->getConfig('base_url');

        $result = match ($providerSlug) {
            'openrouter', 'openai', 'deepseek' => $this->callOpenAiCompatible($baseUrl, $apiKey, $resolvedModel, $systemPrompt, $userPrompt, $maxTokens, $timeout),
            'anthropic' => $this->callAnthropic($baseUrl, $apiKey, $resolvedModel, $systemPrompt, $userPrompt, $maxTokens, $timeout),
            'gemini' => $this->callGemini($baseUrl, $apiKey, $resolvedModel, $systemPrompt, $userPrompt, $maxTokens, $timeout),
            default => throw new RuntimeException("Unsupported AI provider [{$providerSlug}]."),
        };

        $provider->recordUsage();

        return array_merge($result, ['provider' => $providerSlug]);
    }

    /**
     * Attempt a minimal chat request to verify the provider's configured credentials work.
     * Never throws — returns false on any failure (bad key, network error, unsupported provider, etc).
     */
    public function testConnection(string $providerSlug): bool
    {
        try {
            $this->chatJson($providerSlug, 'You are a test.', 'Reply with {"ok": true} as JSON.');
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * OpenAI-compatible chat completions API — used by OpenRouter, OpenAI itself, and DeepSeek
     * (all three share this request/response shape).
     */
    private function callOpenAiCompatible(string $baseUrl, string $apiKey, string $model, string $system, string $user, int $maxTokens, int $timeout): array
    {
        $response = Http::withToken($apiKey)
            ->timeout($timeout)
            ->baseUrl($baseUrl)
            ->post('/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $user],
                ],
                'response_format' => ['type' => 'json_object'],
                'max_tokens' => $maxTokens,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('AI request failed: ' . $response->body());
        }

        $body = $response->json();
        $content = $body['choices'][0]['message']['content'] ?? null;
        $decoded = $content ? json_decode($content, true) : null;

        if (json_last_error() !== JSON_ERROR_NONE || $decoded === null) {
            throw new RuntimeException('AI provider returned invalid JSON content.');
        }

        return [
            'content' => $decoded,
            'usage' => [
                'prompt_tokens' => $body['usage']['prompt_tokens'] ?? null,
                'completion_tokens' => $body['usage']['completion_tokens'] ?? null,
                'total_tokens' => $body['usage']['total_tokens'] ?? null,
            ],
            'model' => $body['model'] ?? $model,
            'raw' => $body,
        ];
    }

    /**
     * Anthropic Messages API — different request/response shape from OpenAI-compatible APIs.
     */
    private function callAnthropic(string $baseUrl, string $apiKey, string $model, string $system, string $user, int $maxTokens, int $timeout): array
    {
        $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
            ])
            ->timeout($timeout)
            ->baseUrl($baseUrl)
            ->post('/messages', [
                'model' => $model,
                'max_tokens' => $maxTokens,
                'system' => $system . "\n\nRespond ONLY with valid JSON, no other text.",
                'messages' => [
                    ['role' => 'user', 'content' => $user],
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('AI request failed: ' . $response->body());
        }

        $body = $response->json();
        $content = $body['content'][0]['text'] ?? null;
        $decoded = $content ? json_decode($this->stripJsonFences($content), true) : null;

        if (json_last_error() !== JSON_ERROR_NONE || $decoded === null) {
            throw new RuntimeException('AI provider returned invalid JSON content.');
        }

        return [
            'content' => $decoded,
            'usage' => [
                'prompt_tokens' => $body['usage']['input_tokens'] ?? null,
                'completion_tokens' => $body['usage']['output_tokens'] ?? null,
                'total_tokens' => ($body['usage']['input_tokens'] ?? 0) + ($body['usage']['output_tokens'] ?? 0),
            ],
            'model' => $body['model'] ?? $model,
            'raw' => $body,
        ];
    }

    /**
     * Google Gemini generateContent API — different request/response shape again.
     */
    private function callGemini(string $baseUrl, string $apiKey, string $model, string $system, string $user, int $maxTokens, int $timeout): array
    {
        $response = Http::timeout($timeout)
            ->baseUrl($baseUrl)
            ->post("/models/{$model}:generateContent?key={$apiKey}", [
                'system_instruction' => ['parts' => [['text' => $system]]],
                'contents' => [
                    ['role' => 'user', 'parts' => [['text' => $user]]],
                ],
                'generationConfig' => [
                    'maxOutputTokens' => $maxTokens,
                    'responseMimeType' => 'application/json',
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('AI request failed: ' . $response->body());
        }

        $body = $response->json();
        $content = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;
        $decoded = $content ? json_decode($this->stripJsonFences($content), true) : null;

        if (json_last_error() !== JSON_ERROR_NONE || $decoded === null) {
            throw new RuntimeException('AI provider returned invalid JSON content.');
        }

        return [
            'content' => $decoded,
            'usage' => [
                'prompt_tokens' => $body['usageMetadata']['promptTokenCount'] ?? null,
                'completion_tokens' => $body['usageMetadata']['candidatesTokenCount'] ?? null,
                'total_tokens' => $body['usageMetadata']['totalTokenCount'] ?? null,
            ],
            'model' => $model,
            'raw' => $body,
        ];
    }

    private function stripJsonFences(string $text): string
    {
        $text = trim($text);
        $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);
        return trim($text);
    }
}
