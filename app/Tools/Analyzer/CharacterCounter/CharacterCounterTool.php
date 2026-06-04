<?php

namespace App\Tools\Analyzer\CharacterCounter;

use App\Enums\PlanTier;
use App\Enums\ToolCategory;
use App\Tools\BaseTool;

class CharacterCounterTool extends BaseTool
{
    public function slug(): string        { return 'character-counter'; }
    public function name(): string        { return 'Character Counter'; }
    public function description(): string { return 'Count characters, non-space characters, words, sentences, and more in your text.'; }
    public function category(): ToolCategory { return ToolCategory::Analyzer; }
    public function icon(): string        { return 'bx bx-list-check'; }
    public function requiredPlan(): PlanTier { return PlanTier::Free; }
    public function dailyLimit(): ?int    { return 20; }

    public function livewireComponent(): string
    {
        return \App\Livewire\Tools\Analyzer\CharacterCounter::class;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:100000'],
        ];
    }

    public function handle(array $input): array
    {
        $text = $input['text'];

        $words = str_word_count(strip_tags($text));
        $characters = mb_strlen($text);
        $charactersNoSpaces = mb_strlen(preg_replace('/\s+/', '', $text));
        $sentences = preg_match_all('/[.!?]+/', $text);
        $paragraphs = count(array_filter(
            preg_split('/\n\s*\n/', trim($text))
        ));

        return [
            'characters' => $characters,
            'characters_no_spaces' => $charactersNoSpaces,
            'words' => $words,
            'sentences' => $sentences,
            'paragraphs' => $paragraphs,
            'avg_word_length' => $words > 0 ? round($charactersNoSpaces / $words, 2) : 0,
            'reading_time_minutes' => $words > 0 ? ceil($words / 200) : 0,
        ];
    }
}
