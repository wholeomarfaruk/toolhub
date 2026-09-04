<?php

namespace App\Jobs;

use App\Models\AiGenerationLog;
use App\Models\SeoPage;
use App\Services\AI\SeoContentGenerator;
use App\Services\ToolRegistry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateSeoPageContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly int $seoPageId,
        public readonly ?int $triggeredByUserId,
        public readonly string $providerSlug,
        public readonly ?string $model = null,
    ) {
    }

    public function handle(SeoContentGenerator $generator): void
    {
        $page = SeoPage::find($this->seoPageId);
        if (!$page || $page->status === 'published') {
            return;
        }

        $tool = app(ToolRegistry::class)->tryFind($page->tool_slug);
        if (!$tool) {
            return;
        }

        $log = AiGenerationLog::create([
            'type' => 'page_content_generation',
            'seo_page_id' => $page->id,
            'provider' => $this->providerSlug,
            'model' => $this->model ?? 'default',
            'status' => 'pending',
            'triggered_by' => $this->triggeredByUserId,
        ]);

        try {
            $result = $generator->generate(
                $this->providerSlug,
                $page->keyword ?? $page->slug,
                $tool->name(),
                $page->tool_slug,
                $page->variables ?? [],
                $page->tool_preset ?? [],
                $this->model
            );

            $content = $result['content'] ?? [];

            $page->update([
                'meta_title' => $content['meta_title'] ?? $page->meta_title,
                'meta_description' => $content['meta_description'] ?? $page->meta_description,
                'h1' => $content['h1'] ?? $page->h1,
                'intro' => $content['intro'] ?? $page->intro,
                'content' => $content['content'] ?? $page->content,
                'faqs' => $content['faqs'] ?? $page->faqs,
                'examples' => $content['examples'] ?? $page->examples,
                'status' => 'ai_generated',
            ]);

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
