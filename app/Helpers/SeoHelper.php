<?php

namespace App\Helpers;

class SeoHelper
{
    /**
     * Get SEO meta data for a tool
     */
    public static function getToolSeoData(string $toolSlug): array
    {
        $config = config('seo.tools.' . $toolSlug, []);

        return [
            'title' => $config['title'] ?? config('app.name'),
            'description' => $config['description'] ?? config('seo.site_description'),
            'keywords' => implode(', ', $config['keywords'] ?? config('seo.default_keywords')),
            'og_title' => $config['title'] ?? config('app.name'),
            'og_description' => $config['description'] ?? config('seo.site_description'),
            'canonical_url' => route('tools.' . $toolSlug),
        ];
    }

    /**
     * Generate structured data breadcrumb schema
     */
    public static function generateBreadcrumbSchema(array $breadcrumbs): string
    {
        $items = [];
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'] ?? '',
                'item' => $breadcrumb['url'] ?? '',
            ];
        }

        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ]);
    }

    /**
     * Generate tool schema structured data
     */
    public static function generateToolSchema(array $tool): string
    {
        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => $tool['name'] ?? '',
            'description' => $tool['description'] ?? '',
            'url' => $tool['url'] ?? '',
            'applicationCategory' => 'Utility',
            'operatingSystem' => 'Web',
            'offers' => [
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'USD',
            ],
        ]);
    }

    /**
     * Generate FAQ schema structured data
     */
    public static function generateFaqSchema(array $faqs): string
    {
        $items = [];
        foreach ($faqs as $faq) {
            $items[] = [
                '@type' => 'Question',
                'name' => $faq['question'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'] ?? '',
                ],
            ];
        }

        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $items,
        ]);
    }

    /**
     * Generate organization schema
     */
    public static function generateOrganizationSchema(): string
    {
        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('seo.site_name'),
            'url' => config('seo.site_url'),
            'description' => config('seo.site_description'),
            'sameAs' => [
                config('seo.social.twitter'),
                config('seo.social.facebook'),
                config('seo.social.linkedin'),
            ],
        ]);
    }

    /**
     * Generate JSON-LD for local business
     */
    public static function generateLocalBusinessSchema(array $business): string
    {
        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $business['name'] ?? config('seo.site_name'),
            'description' => $business['description'] ?? config('seo.site_description'),
            'url' => $business['url'] ?? config('seo.site_url'),
            'address' => $business['address'] ?? [],
            'telephone' => $business['phone'] ?? '',
            'email' => $business['email'] ?? '',
        ]);
    }

    /**
     * Get meta robots directive
     */
    public static function getMetaRobots(bool $index = true, bool $follow = true, bool $noArchive = false): string
    {
        $directives = [];

        $directives[] = $index ? 'index' : 'noindex';
        $directives[] = $follow ? 'follow' : 'nofollow';

        if ($noArchive) {
            $directives[] = 'noarchive';
        }

        return implode(', ', $directives);
    }

    /**
     * Get meta tags string for a page
     */
    public static function generateMetaTags(array $options = []): string
    {
        $tags = [];

        if (isset($options['title'])) {
            $tags[] = '<meta property="og:title" content="' . htmlspecialchars($options['title']) . '">';
        }

        if (isset($options['description'])) {
            $tags[] = '<meta name="description" content="' . htmlspecialchars($options['description']) . '">';
            $tags[] = '<meta property="og:description" content="' . htmlspecialchars($options['description']) . '">';
        }

        if (isset($options['image'])) {
            $tags[] = '<meta property="og:image" content="' . htmlspecialchars($options['image']) . '">';
        }

        if (isset($options['url'])) {
            $tags[] = '<meta property="og:url" content="' . htmlspecialchars($options['url']) . '">';
            $tags[] = '<link rel="canonical" href="' . htmlspecialchars($options['url']) . '">';
        }

        if (isset($options['type'])) {
            $tags[] = '<meta property="og:type" content="' . htmlspecialchars($options['type']) . '">';
        }

        return implode("\n", $tags);
    }
}
