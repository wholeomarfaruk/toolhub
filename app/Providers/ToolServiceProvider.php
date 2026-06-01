<?php

namespace App\Providers;

use App\Services\ToolRegistry;
use App\Tools\Calculator\EmiCalculator\EmiCalculatorTool;
use App\Tools\Generator\InvoiceGenerator\InvoiceGeneratorTool;
use Illuminate\Support\ServiceProvider;

class ToolServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ToolRegistry::class, function () {
            $registry = new ToolRegistry();

            // ── Calculators ──────────────────────────────────────
            $registry->register(new EmiCalculatorTool());

            // ── Generators ───────────────────────────────────────
            $registry->register(new InvoiceGeneratorTool());

            // ── Converters ───────────────────────────────────────
            // $registry->register(new UnitConverterTool());

            return $registry;
        });
    }
}
