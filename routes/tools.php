<?php

use App\Http\Controllers\CharacterCounterPdfController;
use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\SentenceCounterPdfController;
use App\Http\Controllers\WordCounterPdfController;
use App\Livewire\Tools\Analyzer\CharacterCounter;
use App\Livewire\Tools\Analyzer\SentenceCounter;
use App\Livewire\Tools\Analyzer\WordCounter;
use App\Livewire\Tools\Calculator\AgeCalculator;
use App\Livewire\Tools\Calculator\EmiCalculator;
use App\Livewire\Tools\Generator\InvoiceGenerator;
use App\Livewire\Tools\Generator\SlugGenerator;
use App\Services\ToolRegistry;
use Illuminate\Support\Facades\Route;

// ── Analyzers ───────────────────────────────────────────────────
Route::get('/word-counter',
    WordCounter::class
)->name('word-counter');

Route::get('/character-counter',
    CharacterCounter::class
)->name('character-counter');

Route::get('/sentence-counter',
    SentenceCounter::class
)->name('sentence-counter');

// ── Calculators ──────────────────────────────────────────────────
Route::get('/age-calculator',
    AgeCalculator::class
)->name('age-calculator');

Route::get('/emi-calculator',
    EmiCalculator::class
)->name('emi-calculator');

// ── Generators ──────────────────────────────────────────────────
Route::get('/slug-generator',
    SlugGenerator::class
)->name('slug-generator');

Route::get('/invoice-generator',
    InvoiceGenerator::class
)->name('invoice-generator');

Route::get('/invoice/pdf', [InvoicePdfController::class, 'download'])
    ->name('invoice.pdf');

Route::get('/character-counter/pdf', [CharacterCounterPdfController::class, 'download'])
    ->name('character-counter.pdf');

Route::get('/sentence-counter/pdf', [SentenceCounterPdfController::class, 'download'])
    ->name('sentence-counter.pdf');

// Protected PDF exports
Route::middleware('auth')->group(function () {
    Route::get('/word-counter/pdf', [WordCounterPdfController::class, 'download'])
        ->name('word-counter.pdf');
});

// ── SEO landing pages ──────────────────────────────────────────
//    /tools/{tool_slug}/{seo_page_slug} — same Livewire component as the
//    main tool page. The component reads the slug and loads the matching
//    SeoPage from the DB; if the slug is missing the component renders its
//    built-in defaults.
foreach (app(ToolRegistry::class)->all() as $tool) {
    Route::get('/'.$tool->slug().'/{seo_page_slug}', $tool->livewireComponent())
        ->name($tool->slug().'.seo')
        ->where('seo_page_slug', '[a-z0-9-]+');
}
