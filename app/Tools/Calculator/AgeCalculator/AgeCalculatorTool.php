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
    public function description(): string { return 'Calculate your precise age, days lived, next birthday, zodiac sign, and more.'; }
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

        // Get zodiac
        $zodiac = $this->getZodiac($dob->month, $dob->day);

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
            'zodiac_sign' => $zodiac['sign'],
            'zodiac_emoji' => $zodiac['emoji'],
            'is_birthday_today' => $isBirthdayToday,
            'dob_formatted' => $dob->format('F j, Y'),
        ];
    }

    private function getZodiac(int $month, int $day): array
    {
        $zodiacSigns = [
            ['sign' => 'Capricorn', 'emoji' => '♑', 'month' => 12, 'start_day' => 22, 'end_day' => 31],
            ['sign' => 'Capricorn', 'emoji' => '♑', 'month' => 1, 'start_day' => 1, 'end_day' => 19],
            ['sign' => 'Aquarius', 'emoji' => '♒', 'month' => 1, 'start_day' => 20, 'end_day' => 31],
            ['sign' => 'Aquarius', 'emoji' => '♒', 'month' => 2, 'start_day' => 1, 'end_day' => 18],
            ['sign' => 'Pisces', 'emoji' => '♓', 'month' => 2, 'start_day' => 19, 'end_day' => 29],
            ['sign' => 'Pisces', 'emoji' => '♓', 'month' => 3, 'start_day' => 1, 'end_day' => 20],
            ['sign' => 'Aries', 'emoji' => '♈', 'month' => 3, 'start_day' => 21, 'end_day' => 31],
            ['sign' => 'Aries', 'emoji' => '♈', 'month' => 4, 'start_day' => 1, 'end_day' => 19],
            ['sign' => 'Taurus', 'emoji' => '♉', 'month' => 4, 'start_day' => 20, 'end_day' => 30],
            ['sign' => 'Taurus', 'emoji' => '♉', 'month' => 5, 'start_day' => 1, 'end_day' => 20],
            ['sign' => 'Gemini', 'emoji' => '♊', 'month' => 5, 'start_day' => 21, 'end_day' => 31],
            ['sign' => 'Gemini', 'emoji' => '♊', 'month' => 6, 'start_day' => 1, 'end_day' => 20],
            ['sign' => 'Cancer', 'emoji' => '♋', 'month' => 6, 'start_day' => 21, 'end_day' => 30],
            ['sign' => 'Cancer', 'emoji' => '♋', 'month' => 7, 'start_day' => 1, 'end_day' => 22],
            ['sign' => 'Leo', 'emoji' => '♌', 'month' => 7, 'start_day' => 23, 'end_day' => 31],
            ['sign' => 'Leo', 'emoji' => '♌', 'month' => 8, 'start_day' => 1, 'end_day' => 22],
            ['sign' => 'Virgo', 'emoji' => '♍', 'month' => 8, 'start_day' => 23, 'end_day' => 31],
            ['sign' => 'Virgo', 'emoji' => '♍', 'month' => 9, 'start_day' => 1, 'end_day' => 22],
            ['sign' => 'Libra', 'emoji' => '♎', 'month' => 9, 'start_day' => 23, 'end_day' => 30],
            ['sign' => 'Libra', 'emoji' => '♎', 'month' => 10, 'start_day' => 1, 'end_day' => 22],
            ['sign' => 'Scorpio', 'emoji' => '♏', 'month' => 10, 'start_day' => 23, 'end_day' => 31],
            ['sign' => 'Scorpio', 'emoji' => '♏', 'month' => 11, 'start_day' => 1, 'end_day' => 21],
            ['sign' => 'Sagittarius', 'emoji' => '♐', 'month' => 11, 'start_day' => 22, 'end_day' => 30],
            ['sign' => 'Sagittarius', 'emoji' => '♐', 'month' => 12, 'start_day' => 1, 'end_day' => 21],
        ];

        foreach ($zodiacSigns as $sign) {
            if ($sign['month'] === $month && $day >= $sign['start_day'] && $day <= $sign['end_day']) {
                return ['sign' => $sign['sign'], 'emoji' => $sign['emoji']];
            }
        }

        return ['sign' => 'Unknown', 'emoji' => '?'];
    }
}
