<?php

namespace App\Services\AI;

use App\Models\SeoKeyword;

class SeoContentGenerator
{
    public function __construct(private readonly OpenRouterService $openRouter)
    {
    }

    public function generate(SeoKeyword|string $keywordOrTopic, string $toolName, string $toolSlug, array $variables = [], array $toolPreset = []): array
    {
        $system = <<<PROMPT
        You are an SEO content writer for a free online tool called "{$toolName}".
        Write genuinely useful, differentiated landing-page content for one specific
        keyword/use-case — do not produce generic boilerplate that could apply to any
        other page on this site. Respond ONLY with JSON:
        {
          "meta_title": "...", "meta_description": "...", "h1": "...",
          "intro": "2-3 sentences", "content": "3-6 paragraphs of unique body copy, plain HTML allowed",
          "faqs": [{"question": "...", "answer": "..."}],
          "examples": [{"label": "...", "input": "...", "output": "..."}]
        }
        PROMPT;

        $keyword = $keywordOrTopic instanceof SeoKeyword ? $keywordOrTopic->keyword : $keywordOrTopic;
        $user = "Keyword: {$keyword}\nTool: {$toolName} ({$toolSlug})\nVariables: " . json_encode($variables) . "\nTool preset: " . json_encode($toolPreset);

        return $this->openRouter->chatJson($system, $user);
    }
}
