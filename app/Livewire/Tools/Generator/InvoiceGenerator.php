<?php

namespace App\Livewire\Tools\Generator;

use App\Enums\InvoiceTemplate;
use App\Livewire\Traits\WithToolAccess;
use App\Livewire\Traits\WithToolRateLimit;
use App\Livewire\Traits\WithUsageTracking;
use App\Services\PdfExportService;
use App\Services\SubscriptionService;
use App\Tools\Generator\InvoiceGenerator\InvoiceGeneratorTool;
use Livewire\Component;

class InvoiceGenerator extends Component
{
    use WithToolAccess;
    use WithToolRateLimit;
    use WithUsageTracking;

    // ── Tool identity ────────────────────────────────────────────────────
    // Used by all three traits (access, rate-limit, usage tracking).

    public string $toolSlug = 'invoice-generator';

    // ── Invoice metadata ─────────────────────────────────────────────────
    // Scalars that map 1-to-1 with the tool's validation rules.
    // Initialised in setDefaults() so they're never empty on mount.

    public string $invoice_number = '';
    public string $invoice_date   = '';
    public string $due_date       = '';
    public string $currency       = 'USD';
    public string $notes          = '';
    public string $terms          = '';

    // ── Sender — billed from (your business) ─────────────────────────────
    // Nested array, bound via wire:model="sender.name" etc.
    // Keys mirror tool validation rules (sender.name, sender.email ...).

    public array $sender = [
        'name'    => '',
        'email'   => '',
        'address' => '',
        'phone'   => '',
    ];

    // ── Client — billed to ───────────────────────────────────────────────
    // Same pattern as sender.

    public array $client = [
        'name'    => '',
        'email'   => '',
        'address' => '',
    ];

    // ── Line items ───────────────────────────────────────────────────────
    // Dynamic array — addItem() appends, removeItem() splices.
    // Always at least one row. Each row maps to items.*.* validation rules.
    // wire:model="items.0.description", "items.0.qty", "items.0.unit_price".

    public array $items = [
        ['description' => '', 'qty' => 1, 'unit_price' => 0],
    ];

    // ── Tax & discount ───────────────────────────────────────────────────
    // discount_type switches the discount interpretation in InvoiceCalculator:
    //   'percent' → discount is treated as % of subtotal
    //   'fixed'   → discount is a flat currency amount

    public float  $tax_rate      = 0;
    public float  $discount      = 0;
    public string $discount_type = 'percent';

    // ── Output ───────────────────────────────────────────────────────────
    // Null until generate() succeeds. The blade view guards on $result !== null.
    // Shape is exactly what InvoiceGeneratorTool::handle() returns:
    //   invoice_number, invoice_date, due_date, currency,
    //   sender[], client[], lines[], subtotal, discount_amount,
    //   taxable_amount, tax_amount, total, notes, terms.

    public ?array $result = null;

    // ── Templates ────────────────────────────────────────────────────────
    // User selects which template to use when exporting.
    // Available templates depend on plan tier.

    public string $template = 'basic';

    // ── PDF Export Quota (Free plan only) ────────────────────────────────
    // Tracks remaining monthly PDF exports for Free users.
    // Null for Pro/Enterprise (unlimited).

    public ?int $pdfExportsRemaining = null;
    public ?int $pdfExportsLimit     = null;

    // ════════════════════════════════════════════════════════════════════
    // Lifecycle
    // ════════════════════════════════════════════════════════════════════

    public function mount(): void
    {
        // Page loads without auth check (SEO friendly)
        $this->setDefaults();
        // Only load PDF quota if authenticated
        if (auth()->check()) {
            $this->loadPdfQuota();
        }
    }

    /**
     * Load PDF export quota for Free users (Pro/Enterprise have unlimited).
     */
    private function loadPdfQuota(): void
    {
        if (!auth()->check()) {
            return;
        }

        $quota = app(PdfExportService::class)->checkQuota(auth()->user());

        if ($quota['limit'] !== null) {
            $this->pdfExportsLimit     = $quota['limit'];
            $this->pdfExportsRemaining = $quota['remaining'];
        }
    }

    // ════════════════════════════════════════════════════════════════════
    // Item management
    // ════════════════════════════════════════════════════════════════════

    /**
     * Append a blank line item.
     * Livewire re-renders the items loop automatically.
     */
    public function addItem(): void
    {
        $this->items[] = ['description' => '', 'qty' => 1, 'unit_price' => 0];
    }

    /**
     * Remove a line item by its array index.
     * Always keeps at least one row (tool rules require min:1).
     * array_values() re-indexes so Livewire's loop keys stay consecutive.
     */
    public function removeItem(int $index): void
    {
        if (count($this->items) <= 1) {
            return;
        }

        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    // ════════════════════════════════════════════════════════════════════
    // Main action
    // ════════════════════════════════════════════════════════════════════

    /**
     * Entry point bound to the "Generate Invoice" button.
     *
     * Flow:
     *   1. Clear previous errors and result.
     *   2. enforceLimit() — checks daily quota via UsageService + Cache.
     *      Sets $this->limitReached and adds an error bag entry on breach.
     *   3. Call InvoiceGeneratorTool::run($input).
     *        BaseTool::run() → Validator::make($input, rules())->validate()
     *                        → handle($validated)
     *                        → InvoiceCalculator::compute(...)
     *      ValidationException is caught automatically by Livewire 3 and
     *      mapped to the $errors bag — no try/catch needed here.
     *   4. Set $this->result — triggers re-render with the invoice preview.
     *   5. trackUsage() — writes to tool_usages, busts the daily count cache.
     */
    public function generate(): void
    {
        // Check authentication before allowing tool use
        if (!$this->canAccessTool($this->toolSlug)) {
            $this->requireAuth($this->toolSlug);
            return;
        }

        $this->resetErrorBag();
        $this->result       = null;
        $this->limitReached = false;

        $this->enforceLimit($this->toolSlug);

        if ($this->limitReached) {
            return;
        }

        $this->result = app(InvoiceGeneratorTool::class)->run($this->toInput());

        $this->trackUsage($this->toolSlug);
    }

    /**
     * Reset everything back to default state.
     * Bound to a "Start Over" / "Clear" button in the UI.
     */
    public function resetForm(): void
    {
        $this->resetErrorBag();
        $this->result       = null;
        $this->limitReached = false;

        $this->sender        = ['name' => '', 'email' => '', 'address' => '', 'phone' => ''];
        $this->client        = ['name' => '', 'email' => '', 'address' => ''];
        $this->items         = [['description' => '', 'qty' => 1, 'unit_price' => 0]];
        $this->tax_rate      = 0;
        $this->discount      = 0;
        $this->discount_type = 'percent';
        $this->currency      = 'USD';
        $this->notes         = '';
        $this->terms         = '';

        $this->setDefaults();
    }

    // ════════════════════════════════════════════════════════════════════
    // PDF Export
    // ════════════════════════════════════════════════════════════════════

    /**
     * Download invoice as PDF using the selected template.
     * Checks quota for Free users and template authorization.
     */
    public function downloadPdf()
    {
        if (!$this->result) {
            $this->addError('general', 'Generate an invoice first before exporting.');
            return;
        }

        $user = auth()->user();
        $subscriptionService = app(SubscriptionService::class);
        $selectedTemplate = InvoiceTemplate::tryFrom($this->template) ?? InvoiceTemplate::Basic;

        // Check if user can export this template
        $canExportTemplate = $subscriptionService->planFor($user)
            ->includes($selectedTemplate->requiredPlanForExport());

        if (!$canExportTemplate) {
            $this->addError('template', 'The ' . $selectedTemplate->label() . ' template is only available on Pro plans.');
            return;
        }

        // Check PDF export quota (Free plan only)
        $quota = app(PdfExportService::class)->checkQuota($user);

        if (!$quota['can_export']) {
            $this->addError(
                'pdf_quota',
                'You\'ve reached your monthly PDF export limit of ' . $quota['limit']
                . '. Upgrade to Pro for unlimited exports.'
            );
            return;
        }

        // Record the export
        app(PdfExportService::class)->record($user);
        $this->loadPdfQuota();  // Refresh remaining count

        // Store invoice data in session and redirect to PDF download
        session(['invoice_data' => $this->result]);
        $this->redirectRoute('tools.invoice.pdf', ['template' => $selectedTemplate->value]);
    }

    /**
     * Get list of available templates for the user's plan.
     * @return InvoiceTemplate[]
     */
    public function getAvailableTemplates(): array
    {
        $user = auth()->user();
        $plan = app(SubscriptionService::class)->planFor($user);

        // All users can preview Basic and Modern
        $available = [InvoiceTemplate::Basic, InvoiceTemplate::Modern];

        // Pro and Enterprise can export Corporate
        if ($plan->includes(\App\Enums\PlanTier::Pro)) {
            $available[] = InvoiceTemplate::Corporate;
        }

        return $available;
    }

    /**
     * Check if user can export (not just preview) a specific template.
     */
    public function canExportTemplate(InvoiceTemplate $template): bool
    {
        $plan = app(SubscriptionService::class)->planFor(auth()->user());
        return $plan->includes($template->requiredPlanForExport());
    }

    // ════════════════════════════════════════════════════════════════════
    // Render
    // ════════════════════════════════════════════════════════════════════

    public function render()
    {
        return view('livewire.tools.generator.invoice-generator')
            ->layout('layouts.website.website', ['title' => 'Invoice Generator']);
    }

    // ════════════════════════════════════════════════════════════════════
    // Private helpers
    // ════════════════════════════════════════════════════════════════════

    /**
     * Package all component properties into the flat array the tool expects.
     * array_values() on items ensures consecutive integer keys —
     * required because Livewire can leave gaps after removeItem().
     */
    private function toInput(): array
    {
        return [
            'invoice_number' => $this->invoice_number,
            'invoice_date'   => $this->invoice_date,
            'due_date'       => $this->due_date,
            'currency'       => $this->currency,
            'notes'          => $this->notes  ?: null,
            'terms'          => $this->terms  ?: null,
            'sender'         => $this->sender,
            'client'         => $this->client,
            'items'          => array_values($this->items),
            'tax_rate'       => $this->tax_rate,
            'discount'       => $this->discount,
            'discount_type'  => $this->discount_type,
        ];
    }

    /**
     * Sensible defaults so the form is never blank on first load.
     * Called in mount() and after resetForm().
     */
    private function setDefaults(): void
    {
        $this->invoice_number = 'INV-' . now()->format('Ymd') . '-001';
        $this->invoice_date   = now()->toDateString();
        $this->due_date       = now()->addDays(30)->toDateString();
    }
}
