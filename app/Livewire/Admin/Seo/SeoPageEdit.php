<?php

namespace App\Livewire\Admin\Seo;

use App\Jobs\GenerateSeoPageContentJob;
use App\Models\AiProvider;
use App\Models\SeoKeyword;
use App\Models\SeoKeywordGroup;
use App\Models\SeoPage;
use App\Rules\ValidToolSlug;
use App\Services\ToolRegistry;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class SeoPageEdit extends Component
{
    public ?SeoPage $seoPage = null;
    public bool $isCreating = false;

    public string $toolSlug = '';
    public string $slug = '';
    public ?string $metaTitle = '';
    public ?string $metaDescription = '';
    public ?string $h1 = '';
    public ?string $intro = '';
    public ?string $content = '';
    public string $toolPresetJson = '';
    public ?int $seoKeywordId = null;
    public ?int $seoKeywordGroupId = null;
    public string $status = 'draft';
    public bool $isIndexable = false;

    public array $variableRows = [['key' => '', 'value' => '']];
    public array $faqRows = [['question' => '', 'answer' => '']];
    public array $exampleRows = [['label' => '', 'input' => '', 'output' => '']];

    public string $aiProviderSlug = '';
    public string $aiModel = '';

    public function mount(?SeoPage $seoPage = null)
    {
        $this->seoPage = $seoPage;
        $this->isCreating = !$seoPage;

        if ($seoPage) {
            $this->toolSlug = $seoPage->tool_slug;
            $this->slug = $seoPage->slug;
            $this->metaTitle = $seoPage->meta_title;
            $this->metaDescription = $seoPage->meta_description;
            $this->h1 = $seoPage->h1;
            $this->intro = $seoPage->intro;
            $this->content = $seoPage->content;
            $this->toolPresetJson = $seoPage->tool_preset ? json_encode($seoPage->tool_preset, JSON_PRETTY_PRINT) : '';
            $this->seoKeywordId = $seoPage->seo_keyword_id;
            $this->seoKeywordGroupId = $seoPage->seo_keyword_group_id;
            $this->status = $seoPage->status;
            $this->isIndexable = $seoPage->is_indexable;

            $this->variableRows = $this->arrayToRows($seoPage->variables ?? [], ['key', 'value'], 'key', 'value');
            $this->faqRows = $this->arrayToRows($seoPage->faqs ?? [], ['question', 'answer']);
            $this->exampleRows = $this->arrayToRows($seoPage->examples ?? [], ['label', 'input', 'output']);
        }
    }

    private function arrayToRows(array $data, array $fields, ?string $keyField = null, ?string $valueField = null): array
    {
        if (empty($data)) {
            return [array_fill_keys($fields, '')];
        }

        if ($keyField && $valueField) {
            // associative array (variables): key => value
            $rows = [];
            foreach ($data as $k => $v) {
                $rows[] = [$keyField => $k, $valueField => $v];
            }
            return $rows ?: [array_fill_keys($fields, '')];
        }

        // list of associative rows (faqs/examples)
        return array_values($data) ?: [array_fill_keys($fields, '')];
    }

    public function addVariableRow()
    {
        $this->variableRows[] = ['key' => '', 'value' => ''];
    }

    public function removeVariableRow(int $i)
    {
        unset($this->variableRows[$i]);
        $this->variableRows = array_values($this->variableRows);
        if (empty($this->variableRows)) {
            $this->variableRows = [['key' => '', 'value' => '']];
        }
    }

    public function addFaqRow()
    {
        $this->faqRows[] = ['question' => '', 'answer' => ''];
    }

    public function removeFaqRow(int $i)
    {
        unset($this->faqRows[$i]);
        $this->faqRows = array_values($this->faqRows);
        if (empty($this->faqRows)) {
            $this->faqRows = [['question' => '', 'answer' => '']];
        }
    }

    public function addExampleRow()
    {
        $this->exampleRows[] = ['label' => '', 'input' => '', 'output' => ''];
    }

    public function removeExampleRow(int $i)
    {
        unset($this->exampleRows[$i]);
        $this->exampleRows = array_values($this->exampleRows);
        if (empty($this->exampleRows)) {
            $this->exampleRows = [['label' => '', 'input' => '', 'output' => '']];
        }
    }

    public function save()
    {
        $this->validate([
            'toolSlug' => ['required', new ValidToolSlug()],
            'slug' => 'required|alpha_dash|unique:seo_pages,slug,' . ($this->seoPage?->id ?? 'NULL') . ',id,tool_slug,' . $this->toolSlug,
            'metaTitle' => 'nullable|string|max:255',
            'metaDescription' => 'nullable|string',
            'h1' => 'nullable|string|max:255',
            'intro' => 'nullable|string',
            'content' => 'nullable|string',
            'seoKeywordId' => 'nullable|exists:seo_keywords,id',
            'seoKeywordGroupId' => 'nullable|exists:seo_keyword_groups,id',
            'status' => 'required|in:draft,ai_generated,published',
        ]);

        if (trim($this->toolPresetJson) !== '') {
            json_decode($this->toolPresetJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->addError('toolPresetJson', 'Tool preset must be valid JSON.');
                return;
            }
        }

        $variables = [];
        foreach ($this->variableRows as $row) {
            if (trim($row['key'] ?? '') !== '') {
                $variables[$row['key']] = $row['value'];
            }
        }

        $faqs = [];
        foreach ($this->faqRows as $row) {
            if (trim($row['question'] ?? '') !== '' || trim($row['answer'] ?? '') !== '') {
                $faqs[] = ['question' => $row['question'], 'answer' => $row['answer']];
            }
        }

        $examples = [];
        foreach ($this->exampleRows as $row) {
            if (trim($row['label'] ?? '') !== '' || trim($row['input'] ?? '') !== '' || trim($row['output'] ?? '') !== '') {
                $examples[] = ['label' => $row['label'], 'input' => $row['input'], 'output' => $row['output']];
            }
        }

        $toolPreset = trim($this->toolPresetJson) !== '' ? json_decode($this->toolPresetJson, true) : null;

        $this->seoPage = SeoPage::updateOrCreate(
            ['id' => $this->seoPage?->id],
            [
                'tool_slug' => $this->toolSlug,
                'seo_keyword_id' => $this->seoKeywordId,
                'seo_keyword_group_id' => $this->seoKeywordGroupId,
                'slug' => $this->slug,
                'meta_title' => $this->metaTitle ?: null,
                'meta_description' => $this->metaDescription ?: null,
                'h1' => $this->h1 ?: null,
                'variables' => $variables ?: null,
                'tool_preset' => $toolPreset,
                'intro' => $this->intro ?: null,
                'content' => $this->content ?: null,
                'faqs' => $faqs ?: null,
                'examples' => $examples ?: null,
                'status' => $this->status,
                'is_indexable' => $this->isIndexable,
            ]
        );

        $this->dispatch('toast', message: 'Page saved successfully!');
        return redirect()->route('admin.seo.pages.list');
    }

    public function publish()
    {
        if (!$this->seoPage) {
            $this->dispatch('toast', type: 'error', message: 'Save the page before publishing.');
            return;
        }

        $this->seoPage->update([
            'status' => 'published',
            'is_indexable' => true,
            'published_at' => now(),
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        Cache::forget('sitemap.xml');
        Cache::forget('sitemap-index.xml');

        $this->dispatch('toast', message: 'Page published!');
        return redirect()->route('admin.seo.pages.list');
    }

    public function generateContent()
    {
        if (!$this->seoPage) {
            $this->dispatch('toast', type: 'error', message: 'Save the page before generating AI content.');
            return;
        }

        if (!$this->aiProviderSlug) {
            $this->dispatch('toast', type: 'error', message: 'Select an AI provider before generating content.');
            return;
        }

        GenerateSeoPageContentJob::dispatch($this->seoPage->id, auth()->id(), $this->aiProviderSlug, $this->aiModel ?: null);
        $this->dispatch('toast', message: 'AI content generation queued.');
    }

    public function render()
    {
        return view('livewire.admin.seo.seo-page-edit', [
            'tools' => app(ToolRegistry::class)->all(),
            'keywords' => $this->toolSlug
                ? SeoKeyword::where('tool_slug', $this->toolSlug)->orderBy('keyword')->get()
                : SeoKeyword::orderBy('keyword')->get(),
            'groups' => $this->toolSlug
                ? SeoKeywordGroup::where('tool_slug', $this->toolSlug)->orderBy('name')->get()
                : SeoKeywordGroup::orderBy('name')->get(),
            'activeProviders' => AiProvider::active()->get(),
        ])->layout('layouts.admin.admin');
    }
}
