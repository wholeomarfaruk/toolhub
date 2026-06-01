<?php

namespace App\Tools\Generator\InvoiceGenerator;

use App\Enums\PlanTier;
use App\Enums\ToolCategory;
use App\Tools\BaseTool;
use App\Tools\Generator\InvoiceGenerator\Support\InvoiceCalculator;

class InvoiceGeneratorTool extends BaseTool
{
    // ── Tool identity ────────────────────────────────────────────────────

    public function slug(): string
    {
        return 'invoice-generator';
    }

    public function name(): string
    {
        return 'Invoice Generator';
    }

    public function description(): string
    {
        return 'Create professional invoices with itemised lines, tax, and discount. PDF export and saving are Pro features.';
    }

    public function category(): ToolCategory
    {
        return ToolCategory::Generator;
    }

    public function icon(): string
    {
        return 'bx bx-receipt';
    }

    /**
     * Free plan can access and preview invoices.
     * PDF export and saving are gated behind CheckFeature middleware.
     */
    public function requiredPlan(): PlanTier
    {
        return PlanTier::Free;
    }

    /**
     * @deprecated Use dailyLimitFor() for plan-aware limits.
     */
    public function dailyLimit(): ?int
    {
        return 20;
    }

    /**
     * Plan-aware daily generation limits.
     * These can be overridden via plan_features.key='daily_invoice_generator_limit'
     * in the database, but these defaults apply if not configured.
     */
    public function dailyLimitFor(PlanTier $plan): ?int
    {
        return match($plan) {
            PlanTier::Free       => 3,          // Free: 3 per day
            PlanTier::Pro        => 20,         // Pro: 20 per day
            PlanTier::Enterprise => null,       // Enterprise: unlimited
        };
    }

    public function livewireComponent(): string
    {
        return \App\Livewire\Tools\Generator\InvoiceGenerator::class;
    }

    // ── Validation ───────────────────────────────────────────────────────

    public function rules(): array
    {
        return [
            // Invoice metadata
            'invoice_number' => ['required', 'string', 'max:50'],
            'invoice_date'   => ['required', 'date'],
            'due_date'       => ['required', 'date', 'after_or_equal:invoice_date'],
            'currency'       => ['required', 'string', 'size:3'],
            'notes'          => ['nullable', 'string', 'max:1000'],
            'terms'          => ['nullable', 'string', 'max:1000'],

            // Sender (your business)
            'sender.name'    => ['required', 'string', 'max:100'],
            'sender.email'   => ['nullable', 'email', 'max:100'],
            'sender.address' => ['nullable', 'string', 'max:255'],
            'sender.phone'   => ['nullable', 'string', 'max:30'],

            // Client (bill-to)
            'client.name'    => ['required', 'string', 'max:100'],
            'client.email'   => ['nullable', 'email', 'max:100'],
            'client.address' => ['nullable', 'string', 'max:255'],

            // Line items
            'items'               => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.qty'         => ['required', 'numeric', 'min:0.01', 'max:9999'],
            'items.*.unit_price'  => ['required', 'numeric', 'min:0'],

            // Tax & discount
            'tax_rate'      => ['required', 'numeric', 'min:0', 'max:100'],
            'discount'      => ['required', 'numeric', 'min:0'],
            'discount_type' => ['required', 'in:percent,fixed'],
        ];
    }

    // ── Business logic ───────────────────────────────────────────────────

    /**
     * Receives validated input, returns a fully-computed invoice array.
     * No side effects. No PDF. No storage. Pure transformation.
     */
    public function handle(array $input): array
    {
        $calc = InvoiceCalculator::compute(
            items:        $input['items'],
            taxRate:      (float) $input['tax_rate'],
            discount:     (float) $input['discount'],
            discountType: $input['discount_type'],
        );

        return [
            // ── Metadata ─────────────────────────────────────────────
            'invoice_number' => trim($input['invoice_number']),
            'invoice_date'   => $input['invoice_date'],
            'due_date'       => $input['due_date'],
            'currency'       => strtoupper(trim($input['currency'])),
            'notes'          => $input['notes']  ?? null,
            'terms'          => $input['terms']  ?? null,

            // ── Parties ──────────────────────────────────────────────
            'sender' => $input['sender'],
            'client' => $input['client'],

            // ── Tax / discount settings (echoed back for PDF) ─────────
            'tax_rate'      => (float) $input['tax_rate'],
            'discount'      => (float) $input['discount'],
            'discount_type' => $input['discount_type'],

            // ── Calculated figures (from InvoiceCalculator) ───────────
            'lines'           => $calc['lines'],
            'subtotal'        => $calc['subtotal'],
            'discount_amount' => $calc['discount_amount'],
            'taxable_amount'  => $calc['taxable_amount'],
            'tax_amount'      => $calc['tax_amount'],
            'total'           => $calc['total'],
        ];
    }
}
