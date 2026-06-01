<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Website\Home\Home::class)->name('home');

// Public tools index — no auth required
Route::get('/tools', \App\Livewire\Website\Tools\ToolIndex::class)->name('tools.index');

// Tool routes (protected by authentication)
Route::prefix('/tools')->middleware('auth')->group(function () {
    require __DIR__ . '/tools.php';
});

// Checkout routes (protected by authentication)
Route::middleware('auth')->group(function () {
    Route::get('/checkout/{plan}', \App\Livewire\Checkout\Show::class)->name('checkout.show');
    Route::get('/checkout/payment/{payment}', \App\Livewire\Checkout\PaymentPage::class)->name('checkout.payment');
    Route::get('/checkout/success', [\App\Http\Controllers\CheckoutController::class, 'completeCheckout'])->name('checkout.success');
});

// Word Counter PDF export (protected by authentication)
Route::middleware('auth')->group(function () {
    Route::get('/word-counter/pdf', [\App\Http\Controllers\WordCounterPdfController::class, 'download'])->name('word-counter.pdf');
});

// Webhooks (public, CSRF exempt)
Route::post('/webhook/nowpayments', [\App\Http\Controllers\WebhookController::class, 'handleNOWPayments'])
    ->name('webhook.nowpayments')
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
