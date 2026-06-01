<?php

use Illuminate\Support\Facades\Route;

// ── Calculators ──────────────────────────────────────────────────
Route::get('/emi-calculator',
    \App\Livewire\Tools\Calculator\EmiCalculator::class
)->name('emi-calculator');

// ── Generators ───────────────────────────────────────────────────
Route::get('/invoice-generator',
    \App\Livewire\Tools\Generator\InvoiceGenerator::class
)->name('invoice-generator');

Route::get('/invoice/pdf', [\App\Http\Controllers\InvoicePdfController::class, 'download'])
    ->name('invoice.pdf');
