<?php

use App\Http\Controllers\SeoPageController;
use Illuminate\Support\Facades\Route;

Route::get('/{tool_slug}/{seo_page_slug}', [SeoPageController::class, 'show'])
    ->name('show')
    ->where('tool_slug', '[a-z0-9-]+')
    ->where('seo_page_slug', '[a-z0-9-]+');
