<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SEO Configuration
    |--------------------------------------------------------------------------
    |
    | Global SEO settings for the application
    |
    */

    'site_name' => env('APP_NAME', 'ToolsHub'),
    'site_url' => env('APP_URL', 'https://toolshub.com'),
    'site_description' => 'ToolsHub - 50+ Free Online Tools. Calculators, generators, converters and more. Instant results, no signup required.',

    'social' => [
        'twitter' => env('TWITTER_HANDLE', '@toolshub'),
        'facebook' => env('FACEBOOK_URL', ''),
        'linkedin' => env('LINKEDIN_URL', ''),
    ],

    'og_image' => env('OG_IMAGE_URL', '/images/og-default.jpg'),

    'default_keywords' => [
        'online tools',
        'free calculator',
        'generator',
        'converter',
        'productivity tools',
        'utility tools',
        'web tools',
    ],

    'tools' => [
        'age-calculator' => [
            'name' => 'Age Calculator',
            'title' => 'Age Calculator | Calculate Your Age in Years, Months & Days',
            'description' => 'Free online age calculator. Calculate your exact age in years, months, days, hours, and seconds. Find your next birthday and discover your zodiac sign instantly.',
            'keywords' => [
                'age calculator',
                'calculate age',
                'age in days',
                'birthday calculator',
                'how old am I',
                'age calculator online',
                'zodiac sign calculator',
            ],
            'category' => 'calculator',
        ],
        'word-counter' => [
            'name' => 'Word Counter',
            'title' => 'Word Counter | Analyze Text & Content Metrics',
            'description' => 'Free online word counter and text analyzer. Count words, characters, sentences, paragraphs, reading time, and get detailed text statistics instantly.',
            'keywords' => [
                'word counter',
                'character counter',
                'text analyzer',
                'word count tool',
                'reading time calculator',
                'text statistics',
                'word density',
            ],
            'category' => 'analyzer',
        ],
        'emi-calculator' => [
            'name' => 'EMI Calculator',
            'title' => 'EMI Calculator | Loan Payment & Repayment Schedule',
            'description' => 'Free online EMI calculator. Calculate monthly loan payments, total interest, and get detailed amortization schedule instantly.',
            'keywords' => [
                'EMI calculator',
                'loan calculator',
                'monthly EMI',
                'interest calculator',
                'loan repayment',
                'amortization schedule',
            ],
            'category' => 'calculator',
        ],
        'invoice-generator' => [
            'name' => 'Invoice Generator',
            'title' => 'Invoice Generator | Create Professional Invoices Instantly',
            'description' => 'Free online invoice generator. Create professional invoices with multiple templates, customization options, and instant PDF export.',
            'keywords' => [
                'invoice generator',
                'invoice creator',
                'invoice template',
                'invoice maker',
                'professional invoice',
                'free invoice generator',
            ],
            'category' => 'generator',
        ],
        'slug-generator' => [
            'name' => 'Slug Generator',
            'title' => 'Slug Generator | Create SEO-Friendly URL Slugs',
            'description' => 'Free online slug generator. Convert text to SEO-friendly URL slugs instantly. Support for separators, stop words removal, and Unicode characters.',
            'keywords' => [
                'slug generator',
                'URL slug creator',
                'SEO slug',
                'slug maker',
                'permalink generator',
                'url generator',
                'slug converter',
            ],
            'category' => 'generator',
        ],
    ],

    'structured_data' => [
        'enable_breadcrumbs' => true,
        'enable_rich_snippets' => true,
        'enable_faq' => true,
    ],

    'sitemap' => [
        'enable_sitemap' => true,
        'cache_duration' => 3600,
    ],

    'analytics' => [
        'google_analytics' => env('GOOGLE_ANALYTICS_ID', ''),
        'google_site_verification' => env('GOOGLE_SITE_VERIFICATION', ''),
    ],
];
