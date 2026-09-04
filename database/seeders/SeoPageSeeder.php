<?php

namespace Database\Seeders;

use App\Models\SeoPage;
use Illuminate\Database\Seeder;

class SeoPageSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAgeCalculatorPages();
    }

    private function seedAgeCalculatorPages(): void
    {
        $this->upsertPage([
            'tool_slug' => 'age-calculator',
            'slug' => 'accurate-age-calculator-for-legal-purposes',
            'meta_title' => 'Accurate Age Calculator for Legal Purposes | ToolsHub',
            'meta_description' => 'Calculate your exact legal age for court documents, contracts, benefits, and compliance. Precise age in years, months, and days with certified accuracy.',
            'h1' => 'Accurate Age Calculator for Legal Purposes',
            'variables' => [
                'purpose' => 'legal',
                'use_cases' => ['age of majority', 'contract eligibility', 'retirement', 'benefits', 'compliance'],
            ],
            'tool_preset' => ['dob' => '2000-01-01'],
            'intro' => 'When legal, financial, or regulatory matters depend on an exact age calculation, precision matters. Our Age Calculator gives you the exact age in years, months, and days — down to the day — for any date of birth. Whether you need to verify age of majority, contract eligibility, retirement benefits, or compliance reporting, our tool provides the certified-accurate results you can rely on.',
            'content' => '<p>Determining the exact age for legal, financial, or regulatory purposes requires more than a rough estimate. Courts, government agencies, insurance companies, and financial institutions all rely on precise age calculations to determine eligibility, benefits, contractual obligations, and compliance with applicable laws.</p>'.
                '<p>Our Age Calculator computes your age with pinpoint accuracy using the <strong>actual number of days between two dates</strong>, accounting for leap years and month-length variations. This ensures that every day of life is counted correctly — an essential requirement when a single day can determine legal standing or benefit eligibility.</p>'.
                '<h2 id="legal-use-cases">Common Legal Use Cases</h2>'.
                '<p>The following scenarios frequently require an officially accurate age calculation:</p>'.
                '<ul><li><strong>Age of majority:</strong> Verifying whether an individual has reached the legal age of 18 (or 19/21 depending on jurisdiction) to enter contracts, vote, or consent to medical treatment.</li>'.
                '<li><strong>Employment law:</strong> Checking minimum age for hiring, retirement eligibility, and overtime restrictions for minors.</li>'.
                '<li><strong>Insurance underwriting:</strong> Determining age brackets that affect premium rates and coverage terms.</li>'.
                '<li><strong>Retirement &amp; benefits:</strong> Calculating pension vesting, Social Security claiming ages, and annuity payouts.</li>'.
                '<li><strong>Court proceedings:</strong> Establishing age for guardianship, custody, juvenile jurisdiction, and testamentary capacity.</li></ul>'.
                '<h2 id="how-it-works">How It Works</h2>'.
                '<p>Enter any date of birth into the calculator and receive an instant, precise breakdown of age expressed in years, months, days, total weeks, total hours, and total seconds. The results also include the next birthday date and a countdown to that milestone — information frequently requested in legal correspondence and benefit applications.</p>'.
                '<h2 id="accuracy-notes">Accuracy for Legal Documentation</h2>'.
                '<p>All calculations use <strong>Gregorian calendar rules</strong> with full leap-year accounting. The tool is suitable for drafting affidavits, supporting legal briefs, and generating documentation where age must be stated with mathematical certainty. For formal legal proceedings, always cross-reference with official records, but use this calculator for quick, verifiable preliminary assessments.</p>'.
                '<h2 id="related-tools">Related Tools</h2>'.
                '<p>Need more? Explore our <a href="'.config('app.url').'/tools/age-calculator">full Age Calculator</a> for additional features including date-of-birth weekday lookup and birthday countdown.</p>',
            'faqs' => [
                [
                    'question' => 'Is the age output from this calculator legally admissible?',
                    'answer' => 'The calculator provides mathematically accurate results based on standard Gregorian calendar rules. For formal legal proceedings, notarized age verification or certified birth certificate copies remain the authoritative sources. Use our result for preliminary assessments, not as a standalone legal exhibit.',
                ],
                [
                    'question' => 'How is "age" defined for legal purposes?',
                    'answer' => 'Legal age is typically measured from the date of birth to a specific reference date — most commonly the date the calculation is performed. Courts often use the exact day, so our calculator reports both the anniversary-based age and the precise day count.',
                ],
                [
                    'question' => 'Does the calculator account for leap years?',
                    'answer' => 'Yes. The calculator uses full calendar arithmetic including all leap days. A person born on February 29 is counted correctly every four years, and February 29 is included in the total day count whenever it occurs in the period.',
                ],
                [
                    'question' => 'What if the reference date differs from today?',
                    'answer' => 'For legal matters you may need the age as of a specific past date (e.g., the date of a contract or the filing date of a case). The underlying tool accepts any reference date; if you need a different reference date, contact support for a tailored calculation.',
                ],
                [
                    'question' => 'Is my date of birth stored after I calculate?',
                    'answer' => 'No. All calculations are performed in your browser or transiently on the server and are never persisted. Your date of birth is not stored, logged, or associated with your account.',
                ],
            ],
            'examples' => [
                [
                    'label' => 'Age of Majority (18)',
                    'input' => 'DOB: 2007-03-15',
                    'output' => 'Age on 2025-03-15 = 18 years, 0 months, 0 days — eligible to sign contracts',
                ],
                [
                    'label' => 'Retirement Age (65)',
                    'input' => 'DOB: 1960-09-01',
                    'output' => 'Age on 2025-09-01 = 65 years, 0 months, 0 days — eligible for full benefits',
                ],
                [
                    'label' => 'Drinking Age (21)',
                    'input' => 'DOB: 1998-11-22',
                    'output' => 'Age = 21 years, 0 months, 0 days — now of legal drinking age in most jurisdictions',
                ],
            ],
            'status' => 'published',
            'is_indexable' => true,
            'published_at' => now(),
        ]);

        $this->upsertPage([
            'tool_slug' => 'age-calculator',
            'slug' => 'overview',
            'meta_title' => 'Age Calculator | Calculate Your Age in Years, Months & Days',
            'meta_description' => 'Free online age calculator. Calculate your exact age in years, months, days, hours, and seconds. Find your next birthday and discover your zodiac sign instantly.',
            'h1' => 'Age Calculator',
            'variables' => [
                'purpose' => 'general',
                'features' => ['years', 'months', 'days', 'hours', 'seconds', 'next_birthday', 'zodiac'],
            ],
            'tool_preset' => ['dob' => '2000-01-01'],
            'intro' => 'Calculate your precise age, days lived, next birthday, and more with just your date of birth. Our free online age calculator gives you instant, accurate results.',
            'content' => '<p>Welcome to the most accurate free online age calculator. Enter your date of birth and get an instant breakdown of your exact age in years, months, days, hours, minutes, and seconds.</p>'.
                '<p>Our calculator also tells you how many total days you\'ve lived, when your next birthday is, and how many days are left until then. It\'s perfect for personal curiosity, legal reference, or just satisfying your curiosity about the exact number of days you\'ve been alive.</p>'.
                '<h2 id="features">What You Get</h2>'.
                '<ul><li><strong>Precise age:</strong> Years, months, and days with leap-year accuracy</li>'.
                '<li><strong>Total days lived:</strong> The exact number of days since your birth</li>'.
                '<li><strong>Next birthday countdown:</strong> Know exactly how many days until your next birthday</li>'.
                '<li><strong>Birthday weekday:</strong> Find out what day of the week you were born on</li></ul>',
            'faqs' => [
                [
                    'question' => 'How accurate is this age calculator?',
                    'answer' => 'Our calculator uses exact calendar arithmetic, accounting for leap years and varying month lengths. It provides the precise number of years, months, and days between two dates.',
                ],
                [
                    'question' => 'Can I use this for legal purposes?',
                    'answer' => 'While our calculator is mathematically accurate, for official legal documentation you should always cross-reference with certified records. See our dedicated legal age calculator page for more details.',
                ],
                [
                    'question' => 'Does it account for leap years?',
                    'answer' => 'Yes, all leap days are correctly included in the total day count.',
                ],
            ],
            'examples' => [
                [
                    'label' => 'Young Adult',
                    'input' => 'DOB: 2005-06-15',
                    'output' => '20 years, 2 months, 18 days old',
                ],
                [
                    'label' => 'Middle Age',
                    'input' => 'DOB: 1990-01-01',
                    'output' => '35 years, 7 months, 1 day old',
                ],
            ],
            'status' => 'published',
            'is_indexable' => true,
            'published_at' => now(),
        ]);
    }

    private function upsertPage(array $data): void
    {
        SeoPage::updateOrCreate(
            [
                'tool_slug' => $data['tool_slug'],
                'slug' => $data['slug'],
            ],
            $data
        );
    }
}
