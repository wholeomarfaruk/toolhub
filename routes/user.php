<?php

use Illuminate\Support\Facades\Route;

Route::get('/',          \App\Livewire\User\Dashboard\Overview::class)->name('overview');
Route::get('/history',   \App\Livewire\User\Dashboard\ToolHistory::class)->name('history');
Route::get('/subscription', \App\Livewire\User\Subscription\Subscription::class)->name('subscription');
Route::get('/billing',   \App\Livewire\Dashboard\Billing::class)->name('billing');
Route::get('/profile',   \App\Livewire\User\Profile\UserProfile::class)->name('profile');
