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

    // ── Word Counter features ──────────────────────────────────────────────
    case KeywordDensity = 'keyword_density';        // bool
    case ReadabilityScore = 'readability_score';    // bool
    case TopKeywords = 'top_keywords';              // bool
    case ExportFeature = 'export_feature';          // bool
    case MaxCharLimit = 'max_char_limit';           // '5000' | '100000' | 'unlimited'

    // ── Slug Generator features ──────────────────────────────────────────
    case SlugCustomSeparator = 'slug_custom_separator'; // bool
    case SlugStopWords = 'slug_stop_words';              // bool
    case SlugBulkMode = 'slug_bulk_mode';                // bool
    case SlugUnicode = 'slug_unicode';                    // bool

    // ── Age Calculator features ──────────────────────────────────────────
    case AgeCalculatorExport = 'age_calculator_export'; // bool

    // ── Quota features (value = int string or 'unlimited') ────────────────
    case DailyInvoiceLimit   = 'daily_invoice_limit';   // '3' | '20' | 'unlimited'
    case DailyWordCounterLimit = 'daily_word_counter_limit'; // '20' | 'unlimited'
    case DailySlugLimit = 'daily_slug_generator_limit'; // '20' | 'unlimited'
    case DailyAgeCalculatorLimit = 'daily_age_calculator_limit'; // '20' | '100' | 'unlimited'
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
            self::KeywordDensity,
            self::ReadabilityScore,
            self::TopKeywords,
            self::ExportFeature,
            self::SlugCustomSeparator,
            self::SlugStopWords,
            self::SlugBulkMode,
            self::SlugUnicode,
            self::AgeCalculatorExport,
        ]);
    }

    public function isQuota(): bool
    {
        return ! $this->isBoolean();
    }
}
