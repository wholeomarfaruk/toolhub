<?php

namespace App\Livewire\Admin\Seo;

use App\Models\SeoKeyword;
use App\Models\SeoKeywordGroup;
use App\Rules\ValidToolSlug;
use App\Services\ToolRegistry;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class KeywordEdit extends Component
{
    use WithFileUploads;

    public ?SeoKeyword $keyword = null;
    public bool $isCreating = false;

    public string $toolSlug = '';
    public string $keywordText = '';
    public string $searchIntent = 'informational';
    public ?string $country = '';
    public string $language = 'en';
    public int $priority = 3;
    public ?int $seoKeywordGroupId = null;
    public string $status = 'pending';
    public ?string $notes = '';

    // CSV bulk import
    public $csvFile = null;

    public function mount(?SeoKeyword $keyword = null)
    {
        $this->keyword = $keyword;
        $this->isCreating = !$keyword;

        if ($keyword) {
            $this->toolSlug = $keyword->tool_slug;
            $this->keywordText = $keyword->keyword;
            $this->searchIntent = $keyword->search_intent;
            $this->country = $keyword->country;
            $this->language = $keyword->language;
            $this->priority = $keyword->priority;
            $this->seoKeywordGroupId = $keyword->seo_keyword_group_id;
            $this->status = $keyword->status;
            $this->notes = $keyword->notes;
        }
    }

    public function save()
    {
        $this->validate([
            'toolSlug' => ['required', new ValidToolSlug()],
            'keywordText' => 'required|string|max:255',
            'searchIntent' => 'required|in:informational,transactional,navigational,commercial',
            'country' => 'nullable|string|size:2',
            'language' => 'required|string|max:5',
            'priority' => 'required|integer|min:1|max:5',
            'seoKeywordGroupId' => 'nullable|exists:seo_keyword_groups,id',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        SeoKeyword::updateOrCreate(
            ['id' => $this->keyword?->id],
            [
                'tool_slug' => $this->toolSlug,
                'seo_keyword_group_id' => $this->seoKeywordGroupId,
                'keyword' => $this->keywordText,
                'search_intent' => $this->searchIntent,
                'country' => $this->country ?: null,
                'language' => $this->language,
                'priority' => $this->priority,
                'status' => $this->status,
                'source' => $this->keyword?->source ?? 'manual',
                'notes' => $this->notes ?: null,
            ]
        );

        $this->dispatch('toast', message: 'Keyword saved successfully!');
        return redirect()->route('admin.seo.keywords.list');
    }

    public function importCsv()
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt',
        ]);

        $handle = fopen($this->csvFile->getRealPath(), 'r');

        if (!$handle) {
            $this->dispatch('toast', type: 'error', message: 'Could not read the uploaded file.');
            return;
        }

        $created = 0;
        $skipped = 0;
        $seen = [];
        $isFirstRow = true;

        while (($row = fgetcsv($handle)) !== false) {
            if ($isFirstRow) {
                $isFirstRow = false;
                // Skip header row
                continue;
            }

            $keywordText = trim($row[0] ?? '');
            if ($keywordText === '') {
                continue;
            }

            $searchIntent = trim($row[1] ?? '') ?: 'informational';
            $country = trim($row[2] ?? '') ?: null;
            $language = trim($row[3] ?? '') ?: 'en';
            $priority = is_numeric($row[4] ?? null) ? (int) $row[4] : 3;

            $normalized = SeoKeyword::normalize($keywordText);
            $seenKey = $normalized . '|' . $country . '|' . $language;

            if ($normalized === '' || isset($seen[$seenKey])) {
                $skipped++;
                continue;
            }
            $seen[$seenKey] = true;

            $exists = SeoKeyword::where('tool_slug', $this->toolSlug)
                ->where('keyword_normalized', $normalized)
                ->where('country', $country)
                ->where('language', $language)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            SeoKeyword::create([
                'tool_slug' => $this->toolSlug,
                'seo_keyword_group_id' => $this->seoKeywordGroupId,
                'keyword' => $keywordText,
                'search_intent' => in_array($searchIntent, ['informational', 'transactional', 'navigational', 'commercial'], true)
                    ? $searchIntent
                    : 'informational',
                'country' => $country,
                'language' => $language,
                'priority' => max(1, min(5, $priority)),
                'status' => 'pending',
                'source' => 'csv_import',
            ]);

            $created++;
        }

        fclose($handle);

        $this->dispatch('toast', message: "CSV import complete: {$created} created, {$skipped} skipped.");
        $this->reset('csvFile');
    }

    public function render()
    {
        return view('livewire.admin.seo.keyword-edit', [
            'tools' => app(ToolRegistry::class)->all(),
            'groups' => SeoKeywordGroup::orderBy('name')->get(),
        ])->layout('layouts.admin.admin');
    }
}
