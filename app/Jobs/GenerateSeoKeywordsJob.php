<?php

namespace App\Jobs;

use App\Models\AiGenerationLog;
use App\Models\SeoKeyword;
use App\Services\AI\KeywordGenerator;
use App\Services\ToolRegistry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateSeoKeywordsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly string $toolSlug,
        public readonly ?int $seoKeywordGroupId,
        public readonly string $seedTopic,
        public readonly int $count,
        public readonly ?int $triggeredByUserId,
        public readonly string $providerSlug,
        public readonly ?string $model = null,
    ) {
    }

    public function handle(KeywordGenerator $generator): void
    {
        $tool = app(ToolRegistry::class)->tryFind($this->toolSlug);
        if (!$tool) {
            return;
        }

        $log = AiGenerationLog::create([
            'type' => 'keyword_generation',
            'seo_keyword_group_id' => $this->seoKeywordGroupId,
            'provider' => $this->providerSlug,
            'model' => $this->model ?? 'default',
            'status' => 'pending',
            'triggered_by' => $this->triggeredByUserId,
        ]);

        try {
            $result = $generator->generate($this->providerSlug, $this->toolSlug, $tool->name(), $this->seedTopic, $this->count, $this->model);

            foreach ($result['content']['keywords'] ?? [] as $row) {
                $normalized = SeoKeyword::normalize($row['keyword'] ?? '');
                if ($normalized === '') {
                    continue;
                }

                $exists = SeoKeyword::where('tool_slug', $this->toolSlug)
                    ->where('keyword_normalized', $normalized)
                    ->exists();
                if ($exists) {
                    continue;
                }

                SeoKeyword::create([
                    'tool_slug' => $this->toolSlug,
                    'seo_keyword_group_id' => $this->seoKeywordGroupId,
                    'keyword' => $row['keyword'],
                    'search_intent' => $row['search_intent'] ?? 'informational',
                    'priority' => $row['priority'] ?? 3,
                    'status' => 'pending',
                    'source' => 'ai_generated',
                ]);
            }

            $log->update([
                'status' => 'success',
                'model' => $result['model'],
                'prompt_tokens' => $result['usage']['prompt_tokens'] ?? null,
                'completion_tokens' => $result['usage']['completion_tokens'] ?? null,
                'total_tokens' => $result['usage']['total_tokens'] ?? null,
                'raw_response' => json_encode($result['raw']),
            ]);
        } catch (\Throwable $e) {
            $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            throw $e;
        }
    }
}
