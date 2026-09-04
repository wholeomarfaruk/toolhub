<?php

use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\CheckFeature;
use App\Http\Middleware\CheckPlan;
use App\Http\Middleware\PanelMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            // 0. SEO routes — no `web` middleware group, so no session,
            //    CSRF, or cookies are attached to these responses.
            Route::get('/sitemap.xml', [SitemapController::class, 'index'])
                ->name('sitemap.index');
            Route::get('/sitemap-index.xml', [SitemapController::class, 'sitemapIndex'])
                ->name('sitemap.sitemap');
            Route::get('/robots.txt', [RobotsController::class, 'index'])
                ->name('robots.txt');

            // 1. User Dashboard — all authenticated verified users
            Route::middleware(['web', 'auth', 'verified'])
                ->prefix('dashboard')
                ->name('dashboard.')
                ->group(base_path('routes/user.php'));

            // 2. Admin Panel — auth + admin panel access
            Route::middleware(['web', 'auth', 'panel:admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            // 3. Tools — all routes are public (auth check deferred to component actions via modal)
            Route::middleware(['web'])
                ->prefix('tools')
                ->name('tools.')
                ->group(base_path('routes/tools.php'));

            // 4. Programmatic SEO landing pages — nested under /tools/{tool_slug}/{seo_page_slug}
            Route::middleware(['web'])
                ->prefix('tools')
                ->name('tools.seo-pages.')
                ->group(base_path('routes/seo-pages.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'panel' => PanelMiddleware::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'plan' => CheckPlan::class,
            'feature' => CheckFeature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
