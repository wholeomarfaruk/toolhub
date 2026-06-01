<?php

namespace App\Tools\Calculator\AgeCalculator;

use App\Enums\PlanTier;
use App\Enums\ToolCategory;
use App\Tools\BaseTool;
use Carbon\Carbon;

class AgeCalculatorTool extends BaseTool
{
    public function slug(): string        { return 'age-calculator'; }
    public function name(): string        { return 'Age Calculator'; }
    public function description(): string { return 'Calculate your precise age, days lived, next birthday, and more.'; }
    public function category(): ToolCategory { return ToolCategory::Calculator; }
    public function icon(): string        { return 'bx bx-calendar-event'; }
    public function requiredPlan(): PlanTier { return PlanTier::Free; }
    public function dailyLimit(): ?int    { return 20; }

    public function livewireComponent(): string
    {
        return \App\Livewire\Tools\Calculator\AgeCalculator::class;
    }

    public function rules(): array
    {
        return [
            'dob' => ['required', 'date', 'before:today', 'after:1900-01-01'],
        ];
    }

    public function handle(array $input): array
    {
        $dob = Carbon::parse($input['dob']);
        $now = Carbon::now();

        // Calculate precise age
        $years = (int)$dob->diffInYears($now);
        $months = (int)$dob->copy()->addYears($years)->diffInMonths($now);
        $days = (int)$dob->copy()->addYears($years)->addMonths($months)->diffInDays($now);

        // Calculate totals
        $totalDays = (int)$dob->diffInDays($now);
        $totalWeeks = (int)floor($totalDays / 7);
        $totalMonths = (int)$dob->diffInMonths($now);
        $totalHours = (int)$dob->diffInHours($now);
        $totalSeconds = (int)$dob->diffInSeconds($now);

        // Calculate next birthday
        $isBirthdayToday = $now->format('m-d') === $dob->format('m-d');
        $nextBirthday = $dob->copy()->addYears($isBirthdayToday ? $years : $years + 1);
        $daysUntilNext = $isBirthdayToday ? 0 : (int)$now->diffInDays($nextBirthday);

        return [
            'years' => $years,
            'months' => $months,
            'days' => $days,
            'total_days' => $totalDays,
            'total_weeks' => $totalWeeks,
            'total_months' => $totalMonths,
            'total_hours' => $totalHours,
            'age_in_seconds' => $totalSeconds,
            'next_birthday' => $nextBirthday->format('Y-m-d'),
            'next_birthday_fmt' => $nextBirthday->format('F j, Y'),
            'days_until_next' => $daysUntilNext,
            'dob_weekday' => $dob->format('l'),
            'next_bday_weekday' => $nextBirthday->format('l'),
            'is_birthday_today' => $isBirthdayToday,
            'dob_formatted' => $dob->format('F j, Y'),
        ];
    }
}
