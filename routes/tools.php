<?php

use Illuminate\Support\Facades\Route;

// ── Analyzers ────────────────────────────────────────────────────
Route::get('/word-counter',
    \App\Livewire\Tools\Analyzer\WordCounter::class
)->name('word-counter');

// ── Calculators ──────────────────────────────────────────────────
Route::get('/age-calculator',
    \App\Livewire\Tools\Calculator\AgeCalculator::class
)->name('age-calculator');

Route::get('/emi-calculator',
    \App\Livewire\Tools\Calculator\EmiCalculator::class
)->name('emi-calculator');

// ── Generators ───────────────────────────────────────────────────
Route::get('/slug-generator',
    \App\Livewire\Tools\Generator\SlugGenerator::class
)->name('slug-generator');

Route::get('/invoice-generator',
    \App\Livewire\Tools\Generator\InvoiceGenerator::class
)->name('invoice-generator');

Route::get('/invoice/pdf', [\App\Http\Controllers\InvoicePdfController::class, 'download'])
    ->name('invoice.pdf');

Route::get('/age-calculator/pdf', [\App\Http\Controllers\AgeCalculatorPdfController::class, 'download'])
    ->name('age-calculator.pdf');
