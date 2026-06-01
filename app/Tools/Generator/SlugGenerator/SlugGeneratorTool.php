<?php

namespace App\Tools\Generator\SlugGenerator;

use App\Enums\PlanTier;
use App\Enums\ToolCategory;
use App\Tools\BaseTool;
use Illuminate\Support\Str;

class SlugGeneratorTool extends BaseTool
{
    public function slug(): string        { return 'slug-generator'; }
    public function name(): string        { return 'Slug Generator'; }
    public function description(): string { return 'Generate SEO-friendly URL slugs from text with advanced options.'; }
    public function category(): ToolCategory { return ToolCategory::Generator; }
    public function icon(): string        { return 'bx bx-link'; }
    public function requiredPlan(): PlanTier { return PlanTier::Free; }
    public function dailyLimit(): ?int    { return 20; }

    public function livewireComponent(): string
    {
        return \App\Livewire\Tools\Generator\SlugGenerator::class;
    }

    public function rules(): array
    {
        return [
            'text'       => ['required', 'string', 'max:5000'],
            'separator'  => ['nullable', 'in:-,_,.'],
            'stop_words' => ['nullable', 'boolean'],
            'unicode'    => ['nullable', 'boolean'],
            'bulk_text'  => ['nullable', 'string', 'max:10000'],
            'is_bulk'    => ['nullable', 'boolean'],
        ];
    }

    public function handle(array $input): array
    {
        $isBulk = (bool) ($input['is_bulk'] ?? false);
        $separator = $input['separator'] ?? '-';
        $useStopWords = (bool) ($input['stop_words'] ?? false);
        $useUnicode = (bool) ($input['unicode'] ?? false);

        if ($isBulk) {
            return $this->handleBulk(
                $input['bulk_text'] ?? '',
                $separator,
                $useStopWords,
                $useUnicode
            );
        }

        return $this->handleSingle(
            $input['text'],
            $separator,
            $useStopWords,
            $useUnicode
        );
    }

    private function handleSingle(string $text, string $separator, bool $useStopWords, bool $useUnicode): array
    {
        $original = trim($text);
        $cleaned = $original;

        if ($useStopWords) {
            $cleaned = $this->removeStopWords($cleaned);
        }

        if ($useUnicode) {
            $cleaned = Str::transliterate($cleaned);
        }

        $slug = Str::slug($cleaned, $separator);

        return [
            'slug' => $slug,
            'original' => $original,
            'char_count' => mb_strlen($original),
            'word_count' => str_word_count($original),
            'separator' => $separator,
        ];
    }

    private function handleBulk(string $bulkText, string $separator, bool $useStopWords, bool $useUnicode): array
    {
        $lines = array_filter(
            array_map('trim', explode("\n", $bulkText)),
            fn($line) => !empty($line)
        );

        // Limit to 10 lines
        $lines = array_slice($lines, 0, 10);

        $results = [];
        foreach ($lines as $text) {
            $result = $this->handleSingle($text, $separator, $useStopWords, $useUnicode);
            $results[] = $result;
        }

        return [
            'bulk' => $results,
            'count' => count($results),
        ];
    }

    private function removeStopWords(string $text): string
    {
        $stopWords = [
            'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for',
            'of', 'with', 'by', 'from', 'is', 'was', 'are', 'be', 'been', 'have',
            'has', 'had', 'do', 'does', 'did', 'can', 'could', 'will', 'would',
            'should', 'may', 'might', 'must', 'shall', 'as', 'that', 'this',
            'what', 'which', 'who', 'when', 'where', 'why', 'how', 'all', 'each',
            'every', 'both', 'few', 'more', 'most', 'other', 'some', 'such',
            'no', 'nor', 'not', 'only', 'own', 'same', 'so', 'than', 'too',
            'very', 'your', 'my', 'you', 'me', 'he', 'she', 'it', 'we', 'they',
            'him', 'her', 'us', 'them', 'his', 'hers', 'its', 'their',
        ];

        $words = str_word_count(mb_strtolower($text), 1);
        $filtered = array_filter($words, fn($word) => !in_array($word, $stopWords));

        return implode(' ', $filtered);
    }
}
