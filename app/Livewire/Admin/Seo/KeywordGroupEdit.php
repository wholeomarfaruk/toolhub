<?php

namespace App\Livewire\Admin\Seo;

use App\Models\SeoKeywordGroup;
use App\Rules\ValidToolSlug;
use App\Services\ToolRegistry;
use Illuminate\Support\Str;
use Livewire\Component;

class KeywordGroupEdit extends Component
{
    public ?SeoKeywordGroup $keywordGroup = null;
    public bool $isCreating = false;

    public string $toolSlug = '';
    public string $name = '';
    public string $slug = '';
    public ?string $description = '';

    public function mount(?SeoKeywordGroup $keywordGroup = null)
    {
        $this->keywordGroup = $keywordGroup;
        $this->isCreating = !$keywordGroup;

        if ($keywordGroup) {
            $this->toolSlug = $keywordGroup->tool_slug;
            $this->name = $keywordGroup->name;
            $this->slug = $keywordGroup->slug;
            $this->description = $keywordGroup->description;
        }
    }

    public function updatedName($value)
    {
        if ($this->isCreating) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate([
            'toolSlug' => ['required', new ValidToolSlug()],
            'name' => 'required|string|max:150',
            'slug' => 'required|alpha_dash|unique:seo_keyword_groups,slug,' . ($this->keywordGroup?->id ?? 'NULL') . ',id,tool_slug,' . $this->toolSlug,
            'description' => 'nullable|string',
        ]);

        SeoKeywordGroup::updateOrCreate(
            ['id' => $this->keywordGroup?->id],
            [
                'tool_slug' => $this->toolSlug,
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description ?: null,
            ]
        );

        $this->dispatch('toast', message: 'Keyword group saved successfully!');
        return redirect()->route('admin.seo.keyword-groups.list');
    }

    public function render()
    {
        return view('livewire.admin.seo.keyword-group-edit', [
            'tools' => app(ToolRegistry::class)->all(),
        ])->layout('layouts.admin.admin');
    }
}
