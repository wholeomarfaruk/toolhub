<?php

namespace App\Http\Controllers;

use App\Models\SeoPage;
use App\Services\ToolRegistry;

class SeoPageController extends Controller
{
    public function show(string $tool_slug, string $seo_page_slug)
    {
        $tool = app(ToolRegistry::class)->tryFind($tool_slug);
        abort_unless($tool, 404);

        $page = SeoPage::where('tool_slug', $tool_slug)
            ->where('slug', $seo_page_slug)
            ->where('status', 'published')
            ->firstOrFail();

        $related = SeoPage::where('tool_slug', $tool_slug)
            ->where('id', '!=', $page->id)
            ->where('status', 'published')
            ->when($page->seo_keyword_group_id, fn ($q) => $q->where('seo_keyword_group_id', $page->seo_keyword_group_id))
            ->limit(6)
            ->get();

        return view('seo-pages.show', [
            'page' => $page,
            'tool' => $tool,
            'related' => $related,
        ])->layout('layouts.website.website', [
            'title' => $page->meta_title ?: $tool->name(),
            'description' => $page->meta_description ?: $tool->description(),
        ]);
    }
}
