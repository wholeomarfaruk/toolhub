<?php

namespace App\Tools\Analyzer\WordCounter;

use App\Enums\Feature;
use App\Enums\PlanTier;
use App\Enums\ToolCategory;
use App\Tools\BaseTool;

class WordCounterTool extends BaseTool
{
    public function slug(): string        { return 'word-counter'; }
    public function name(): string        { return 'Word Counter'; }
    public function description(): string { return 'Analyze text with word count, character count, reading time, and more.'; }
    public function category(): ToolCategory { return ToolCategory::Analyzer; }
    public function icon(): string        { return 'bx bx-bar-chart'; }
    public function requiredPlan(): PlanTier { return PlanTier::Free; }
    public function dailyLimit(): ?int    { return 20; }

    public function livewireComponent(): string
    {
        return \App\Livewire\Tools\Analyzer\WordCounter::class;
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

        // Basic counts
        $words = str_word_count(strip_tags($text));
        $characters = mb_strlen($text);
        $charactersNoSpaces = mb_strlen(preg_replace('/\s+/', '', $text));
        $sentences = preg_match_all('/[.!?]+/', $text);
        $paragraphs = count(array_filter(
            preg_split('/\n\s*\n/', trim($text))
        ));

        // Time estimates
        $readingTime = $words > 0 ? ceil($words / 200) : 0;
        $speakingTime = $words > 0 ? ceil($words / 130) : 0;

        // Advanced metrics
        $result = [
            'words' => $words,
            'characters' => $characters,
            'characters_no_spaces' => $charactersNoSpaces,
            'sentences' => $sentences,
            'paragraphs' => $paragraphs,
            'reading_time_minutes' => $readingTime,
            'speaking_time_minutes' => $speakingTime,
            'avg_word_length' => $words > 0 ? round($charactersNoSpaces / $words, 2) : 0,
            'avg_sentence_length' => $sentences > 0 ? round($words / $sentences, 2) : 0,
        ];

        // Keyword density and readability (premium features)
        $result['keyword_density'] = $this->calculateKeywordDensity($text, $words);
        $result['readability_score'] = $this->calculateReadability($text, $words, $sentences);
        $result['top_keywords'] = $this->extractTopKeywords($text);

        return $result;
    }

    private function calculateKeywordDensity(string $text, int $totalWords): array
    {
        if ($totalWords === 0) {
            return [];
        }

        $words = str_word_count(mb_strtolower($text), 1);
        $stopWords = $this->getStopWords();
        $keywordCounts = array_count_values($words);

        // Filter out stop words and single characters
        $filtered = array_filter($keywordCounts, function ($count, $word) use ($stopWords) {
            return !in_array($word, $stopWords) && mb_strlen($word) > 2;
        }, ARRAY_FILTER_USE_BOTH);

        // Sort by frequency
        arsort($filtered);

        $density = [];
        foreach (array_slice($filtered, 0, 10) as $word => $count) {
            $percentage = round(($count / $totalWords) * 100, 2);
            $density[$word] = [
                'count' => $count,
                'percentage' => $percentage,
            ];
        }

        return $density;
    }

    private function calculateReadability(string $text, int $words, int $sentences): array
    {
        if ($words === 0 || $sentences === 0) {
            return [
                'flesch_kincaid_grade' => 0,
                'flesch_reading_ease' => 100,
                'difficulty' => 'Very Easy',
            ];
        }

        $syllables = $this->countSyllables($text);

        // Flesch Reading Ease: 206.835 - 1.015(words/sentences) - 84.6(syllables/words)
        $fre = 206.835 - (1.015 * ($words / $sentences)) - (84.6 * ($syllables / $words));
        $fre = max(0, min(100, $fre));

        // Flesch-Kincaid Grade Level
        $fkg = (0.39 * ($words / $sentences)) + (11.8 * ($syllables / $words)) - 15.59;
        $fkg = max(0, $fkg);

        return [
            'flesch_reading_ease' => round($fre, 1),
            'flesch_kincaid_grade' => round($fkg, 1),
            'difficulty' => $this->getDifficultyLevel($fre),
        ];
    }

    private function extractTopKeywords(string $text): array
    {
        $words = str_word_count(mb_strtolower($text), 1);
        $stopWords = $this->getStopWords();
        $keywordCounts = array_count_values($words);

        // Filter
        $filtered = array_filter($keywordCounts, function ($count, $word) use ($stopWords) {
            return !in_array($word, $stopWords) && mb_strlen($word) > 2 && $count > 1;
        }, ARRAY_FILTER_USE_BOTH);

        arsort($filtered);

        return array_slice(array_keys($filtered), 0, 10);
    }

    private function countSyllables(string $text): int
    {
        $text = mb_strtolower($text);
        $syllableCount = 0;

        // Simple syllable counting (heuristic)
        $words = str_word_count($text, 1);

        foreach ($words as $word) {
            $word = preg_replace('/[^a-z]/', '', $word);
            if (strlen($word) <= 3) {
                $syllableCount += 1;
            } else {
                $vowels = preg_match_all('/[aeiouy]/', $word);
                $syllableCount += $vowels;

                if (preg_match('/e$/', $word)) {
                    $syllableCount -= 1;
                }

                if (preg_match('/le$/', $word) && !preg_match('/[lr]le$/', $word)) {
                    $syllableCount += 1;
                }

                $syllableCount = max(1, $syllableCount);
            }
        }

        return $syllableCount;
    }

    private function getDifficultyLevel(float $score): string
    {
        return match (true) {
            $score >= 90 => 'Very Easy',
            $score >= 80 => 'Easy',
            $score >= 70 => 'Fairly Easy',
            $score >= 60 => 'Standard',
            $score >= 50 => 'Fairly Difficult',
            $score >= 30 => 'Difficult',
            default => 'Very Difficult',
        };
    }

    private function getStopWords(): array
    {
        return [
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
    }
}
