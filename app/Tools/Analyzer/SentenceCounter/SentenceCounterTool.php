<?php

namespace App\Tools\Analyzer\SentenceCounter;

use App\Enums\PlanTier;
use App\Enums\ToolCategory;
use App\Tools\BaseTool;

class SentenceCounterTool extends BaseTool
{
    public function slug(): string        { return 'sentence-counter'; }
    public function name(): string        { return 'Sentence Counter'; }
    public function description(): string { return 'Count sentences, words, characters, paragraphs, and more with a single text analyzer.'; }
    public function category(): ToolCategory { return ToolCategory::Analyzer; }
    public function icon(): string        { return 'bx bx-paragraph'; }
    public function requiredPlan(): PlanTier { return PlanTier::Free; }
    public function dailyLimit(): ?int    { return 20; }

    public function livewireComponent(): string
    {
        return \App\Livewire\Tools\Analyzer\SentenceCounter::class;
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
            'sentences' => $sentences,
            'words' => $words,
            'characters' => $characters,
            'characters_no_spaces' => $charactersNoSpaces,
            'paragraphs' => $paragraphs,
            'avg_sentence_length' => $sentences > 0 ? round($words / $sentences, 2) : 0,
            'reading_time_minutes' => $words > 0 ? ceil($words / 200) : 0,
        ];
    }
}
