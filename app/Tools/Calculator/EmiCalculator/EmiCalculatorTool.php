<?php

namespace App\Tools\Calculator\EmiCalculator;

use App\Enums\PlanTier;
use App\Enums\ToolCategory;
use App\Tools\BaseTool;

class EmiCalculatorTool extends BaseTool
{
    public function slug(): string        { return 'emi-calculator'; }
    public function name(): string        { return 'EMI Calculator'; }
    public function description(): string { return 'Calculate monthly loan EMI, total interest, and a full repayment breakdown.'; }
    public function category(): ToolCategory { return ToolCategory::Calculator; }
    public function icon(): string        { return 'bx bx-calculator'; }
    public function requiredPlan(): PlanTier { return PlanTier::Free; }
    public function dailyLimit(): ?int    { return 50; }

    public function livewireComponent(): string
    {
        return \App\Livewire\Tools\Calculator\EmiCalculator::class;
    }

    public function rules(): array
    {
        return [
            'principal'     => ['required', 'numeric', 'min:1', 'max:999999999'],
            'annual_rate'   => ['required', 'numeric', 'min:0.01', 'max:100'],
            'tenure_months' => ['required', 'integer', 'min:1', 'max:360'],
        ];
    }

    public function handle(array $input): array
    {
        $principal     = (float) $input['principal'];
        $monthlyRate   = (float) $input['annual_rate'] / 12 / 100;
        $months        = (int) $input['tenure_months'];

        if ($monthlyRate === 0.0) {
            $emi = $principal / $months;
        } else {
            $factor = pow(1 + $monthlyRate, $months);
            $emi    = ($principal * $monthlyRate * $factor) / ($factor - 1);
        }

        $totalPayment  = $emi * $months;
        $totalInterest = $totalPayment - $principal;

        return [
            'emi'            => round($emi, 2),
            'total_payment'  => round($totalPayment, 2),
            'total_interest' => round($totalInterest, 2),
            'principal'      => $principal,
            'interest_pct'   => round(($totalInterest / $totalPayment) * 100, 1),
            'principal_pct'  => round(($principal / $totalPayment) * 100, 1),
            'schedule'       => $this->amortizationSchedule($principal, $monthlyRate, $months, $emi),
        ];
    }

    private function amortizationSchedule(float $principal, float $rate, int $months, float $emi): array
    {
        $schedule = [];
        $balance  = $principal;
        $show     = min($months, 12);

        for ($i = 1; $i <= $show; $i++) {
            $interest  = $balance * $rate;
            $principal = $emi - $interest;
            $balance  -= $principal;

            $schedule[] = [
                'month'     => $i,
                'emi'       => round($emi, 2),
                'principal' => round($principal, 2),
                'interest'  => round($interest, 2),
                'balance'   => round(max(0, $balance), 2),
            ];
        }

        return $schedule;
    }
}
