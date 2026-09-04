<?php

namespace App\Livewire\Admin\Seo;

use App\Models\SeoKeywordGroup;
use Livewire\Component;

class KeywordGroupList extends Component
{
    public $groups = [];
    public ?int $confirmDeleteId = null;

    public function mount()
    {
        $this->loadGroups();
    }

    public function loadGroups()
    {
        $this->groups = SeoKeywordGroup::withCount(['keywords', 'pages'])
            ->orderBy('tool_slug')
            ->orderBy('name')
            ->get();
    }

    public function confirmDelete(int $id)
    {
        $this->confirmDeleteId = $id;
    }

    public function delete(int $id)
    {
        $group = SeoKeywordGroup::findOrFail($id);

        if ($group->keywords()->count() > 0 || $group->pages()->count() > 0) {
            $this->dispatch('toast', type: 'error', message: 'Cannot delete a group that still has keywords or pages attached.');
            $this->confirmDeleteId = null;
            return;
        }

        $group->delete();
        $this->confirmDeleteId = null;

        $this->dispatch('toast', message: 'Keyword group deleted successfully!');
        $this->loadGroups();
    }

    public function render()
    {
        return view('livewire.admin.seo.keyword-group-list')->layout('layouts.admin.admin');
    }
}
