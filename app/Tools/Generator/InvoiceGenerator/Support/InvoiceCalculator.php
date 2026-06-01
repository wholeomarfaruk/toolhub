<?php

namespace App\Tools\Generator\InvoiceGenerator\Support;

/**
 * Pure PHP invoice math.
 * No Laravel, no HTTP, no side effects — fully unit-testable.
 *
 * Computation order:
 *   1. qty × unit_price  → line_total  (per item)
 *   2. Σ line_total      → subtotal
 *   3. subtotal - discount → taxable_amount
 *   4. taxable × tax%   → tax_amount
 *   5. taxable + tax     → total
 *
 * Tax is always applied on the post-discount amount (standard accounting).
 */
final class InvoiceCalculator
{
    /**
     * @param  array  $items        [['description','qty','unit_price'], ...]
     * @param  float  $taxRate      0–100 (percentage)
     * @param  float  $discount     Amount or percentage depending on $discountType
     * @param  string $discountType 'percent' | 'fixed'
     * @return array
     */
    public static function compute(
        array  $items,
        float  $taxRate,
        float  $discount,
        string $discountType
    ): array {
        // ── 1. Line totals ───────────────────────────────────────────────
        $lines    = [];
        $subtotal = 0.0;

        foreach ($items as $item) {
            $qty       = (float) $item['qty'];
            $unitPrice = (float) $item['unit_price'];
            $lineTotal = $qty * $unitPrice;
            $subtotal += $lineTotal;

            $lines[] = [
                'description' => $item['description'],
                'qty'         => $qty,
                'unit_price'  => $unitPrice,
                'line_total'  => round($lineTotal, 2),
            ];
        }

        // ── 2. Discount ──────────────────────────────────────────────────
        $discountAmount = match ($discountType) {
            'percent' => $subtotal * ($discount / 100),
            'fixed'   => min((float) $discount, $subtotal), // never exceed subtotal
            default   => 0.0,
        };

        // ── 3. Taxable base (after discount) ────────────────────────────
        $taxableAmount = max(0.0, $subtotal - $discountAmount);

        // ── 4. Tax ───────────────────────────────────────────────────────
        $taxAmount = $taxableAmount * ($taxRate / 100);

        // ── 5. Grand total ───────────────────────────────────────────────
        $total = $taxableAmount + $taxAmount;

        // Round at the very end — avoids compounding rounding errors
        return [
            'lines'           => $lines,
            'subtotal'        => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'taxable_amount'  => round($taxableAmount, 2),
            'tax_amount'      => round($taxAmount, 2),
            'total'           => round($total, 2),
        ];
    }
}
