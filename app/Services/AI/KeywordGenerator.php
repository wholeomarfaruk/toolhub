<?php

namespace App\Services\AI;

class KeywordGenerator
{
    public function __construct(private readonly AiProviderService $aiProvider)
    {
    }

    public function generate(string $providerSlug, string $toolSlug, string $toolName, string $seedTopic, int $count = 15, ?string $model = null): array
    {
        $system = <<<PROMPT
        You are an SEO keyword researcher. Given a tool and a seed topic, propose {$count}
        realistic, non-duplicate long-tail keyword ideas a user might search on Google.
        Respond ONLY with JSON: {"keywords": [{"keyword": "...", "search_intent": "informational|transactional|navigational|commercial", "priority": 1-5}]}
        Priority 1 = highest commercial/traffic value.
        PROMPT;

        $user = "Tool: {$toolName} ({$toolSlug})\nSeed topic: {$seedTopic}\nCount: {$count}";

        return $this->aiProvider->chatJson($providerSlug, $system, $user, $model);
    }
}
