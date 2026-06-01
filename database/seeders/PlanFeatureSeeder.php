<?php

namespace Database\Seeders;

use App\Enums\Feature;
use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Database\Seeder;

class PlanFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $free = Plan::where('slug', 'free')->first();
        $pro = Plan::where('slug', 'pro')->first();
        $enterprise = Plan::where('slug', 'enterprise')->first();

        if (!$free || !$pro || !$enterprise) {
            throw new \RuntimeException('Plans must be seeded first via PlanSeeder');
        }

        // ── FREE PLAN ────────────────────────────────────────────────────

        $this->seedFeatures($free, [
            Feature::PdfExport->value        => 'true',          // Can export (with limit + watermark)
            Feature::SaveInvoice->value      => 'false',
            Feature::InvoiceHistory->value   => 'false',
            Feature::InvoiceWatermark->value => 'true',          // Shows "Free Plan" watermark
            Feature::ClientManagement->value => 'false',
            Feature::Templates->value        => 'true',          // Has templates access
            Feature::QuotationGenerator->value => 'false',
            Feature::ReceiptGenerator->value => 'false',
            Feature::ApiAccess->value        => 'false',
            Feature::TeamAccess->value       => 'false',
            Feature::DailyInvoiceLimit->value => '3',
            Feature::MonthlyPdfLimit->value => '5',              // 5 PDFs per month
            Feature::MaxSavedInvoices->value => '0',
            // Word Counter features
            Feature::KeywordDensity->value => 'false',
            Feature::ReadabilityScore->value => 'false',
            Feature::TopKeywords->value => 'false',
            Feature::ExportFeature->value => 'false',
            Feature::MaxCharLimit->value => '5000',
            Feature::DailyWordCounterLimit->value => '20',
            // Slug Generator features
            Feature::SlugCustomSeparator->value => 'false',
            Feature::SlugStopWords->value => 'false',
            Feature::SlugBulkMode->value => 'false',
            Feature::SlugUnicode->value => 'false',
            Feature::DailySlugLimit->value => '20',
            // Age Calculator features
            Feature::AgeCalculatorExport->value => 'false',
            Feature::DailyAgeCalculatorLimit->value => '20',
        ]);

        // ── PRO PLAN ─────────────────────────────────────────────────────

        $this->seedFeatures($pro, [
            Feature::PdfExport->value        => 'true',
            Feature::SaveInvoice->value      => 'true',
            Feature::InvoiceHistory->value   => 'true',
            Feature::InvoiceWatermark->value => 'false',        // No watermark
            Feature::ClientManagement->value => 'true',
            Feature::Templates->value        => 'true',
            Feature::QuotationGenerator->value => 'true',
            Feature::ReceiptGenerator->value => 'false',        // Pro doesn't have receipts
            Feature::ApiAccess->value        => 'false',
            Feature::TeamAccess->value       => 'false',
            Feature::DailyInvoiceLimit->value => '20',
            Feature::MonthlyPdfLimit->value => '250',
            Feature::MaxSavedInvoices->value => '100',
            // Word Counter features
            Feature::KeywordDensity->value => 'true',
            Feature::ReadabilityScore->value => 'true',
            Feature::TopKeywords->value => 'true',
            Feature::ExportFeature->value => 'true',
            Feature::MaxCharLimit->value => '100000',
            Feature::DailyWordCounterLimit->value => 'unlimited',
            // Slug Generator features
            Feature::SlugCustomSeparator->value => 'true',
            Feature::SlugStopWords->value => 'true',
            Feature::SlugBulkMode->value => 'true',
            Feature::SlugUnicode->value => 'false',
            Feature::DailySlugLimit->value => 'unlimited',
            // Age Calculator features
            Feature::AgeCalculatorExport->value => 'true',
            Feature::DailyAgeCalculatorLimit->value => '100',
        ]);

        // ── ENTERPRISE PLAN ──────────────────────────────────────────────

        $this->seedFeatures($enterprise, [
            Feature::PdfExport->value        => 'true',
            Feature::SaveInvoice->value      => 'true',
            Feature::InvoiceHistory->value   => 'true',
            Feature::InvoiceWatermark->value => 'false',
            Feature::ClientManagement->value => 'true',
            Feature::Templates->value        => 'true',
            Feature::QuotationGenerator->value => 'true',
            Feature::ReceiptGenerator->value => 'true',
            Feature::ApiAccess->value        => 'true',
            Feature::TeamAccess->value       => 'true',
            Feature::DailyInvoiceLimit->value => 'unlimited',
            Feature::MonthlyPdfLimit->value => 'unlimited',
            Feature::MaxSavedInvoices->value => 'unlimited',
            // Word Counter features
            Feature::KeywordDensity->value => 'true',
            Feature::ReadabilityScore->value => 'true',
            Feature::TopKeywords->value => 'true',
            Feature::ExportFeature->value => 'true',
            Feature::MaxCharLimit->value => 'unlimited',
            Feature::DailyWordCounterLimit->value => 'unlimited',
            // Slug Generator features
            Feature::SlugCustomSeparator->value => 'true',
            Feature::SlugStopWords->value => 'true',
            Feature::SlugBulkMode->value => 'true',
            Feature::SlugUnicode->value => 'true',
            Feature::DailySlugLimit->value => 'unlimited',
            // Age Calculator features
            Feature::AgeCalculatorExport->value => 'true',
            Feature::DailyAgeCalculatorLimit->value => 'unlimited',
        ]);
    }

    /**
     * Seed features for a plan, using updateOrCreate for idempotency.
     */
    private function seedFeatures(Plan $plan, array $features): void
    {
        foreach ($features as $key => $value) {
            PlanFeature::updateOrCreate(
                ['plan_id' => $plan->id, 'key' => $key],
                ['value' => $value]
            );
        }
    }
}
