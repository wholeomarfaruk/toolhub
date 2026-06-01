<?php

namespace App\Enums;

/**
 * All available feature flag keys.
 *
 * Stored in plan_features.key as strings.
 * Boolean features: value = 'true' | 'false'
 * Quota features:   value = integer string | 'unlimited'
 */
enum Feature: string
{
    // ── Invoice tool features ─────────────────────────────────────────────
    case PdfExport        = 'pdf_export';           // bool
    case SaveInvoice      = 'save_invoice';          // bool
    case InvoiceHistory   = 'invoice_history';       // bool
    case InvoiceWatermark = 'invoice_watermark';     // bool: true = watermark shown
    case ClientManagement = 'client_management';     // bool

    // ── Content features ─────────────────────────────────────────────────
    case Templates           = 'templates';           // bool
    case QuotationGenerator  = 'quotation_generator'; // bool (tool access gate)
    case ReceiptGenerator    = 'receipt_generator';   // bool (tool access gate)

    // ── Platform features ─────────────────────────────────────────────────
    case ApiAccess   = 'api_access';   // bool
    case TeamAccess  = 'team_access';  // bool

    // ── Quota features (value = int string or 'unlimited') ────────────────
    case DailyInvoiceLimit   = 'daily_invoice_limit';   // '3' | '20' | 'unlimited'
    case MonthlyPdfLimit     = 'monthly_pdf_limit';     // '0' | '25' | 'unlimited'
    case MaxSavedInvoices    = 'max_saved_invoices';    // '0' | '100' | 'unlimited'

    public function isBoolean(): bool
    {
        return in_array($this, [
            self::PdfExport,
            self::SaveInvoice,
            self::InvoiceHistory,
            self::InvoiceWatermark,
            self::ClientManagement,
            self::Templates,
            self::QuotationGenerator,
            self::ReceiptGenerator,
            self::ApiAccess,
            self::TeamAccess,
        ]);
    }

    public function isQuota(): bool
    {
        return ! $this->isBoolean();
    }
}
