<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
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

            // 3. Tools — index is public; individual tools require auth
            Route::middleware(['web', 'auth', 'verified'])
                ->prefix('tools')
                ->name('tools.')
                ->group(base_path('routes/tools.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'panel'      => \App\Http\Middleware\PanelMiddleware::class,
            'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'plan'       => \App\Http\Middleware\CheckPlan::class,
            'feature'    => \App\Http\Middleware\CheckFeature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
