<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap for search engines
     */
    public function index()
    {
        // Cache the sitemap for 24 hours
        $sitemap = Cache::remember('sitemap.xml', 86400, function () {
            $urls = [];

            // Add static pages
            $urls[] = $this->createSitemapUrl(
                url('/'),
                now()->subDays(1),
                'weekly',
                0.9
            );

            $urls[] = $this->createSitemapUrl(
                route('tools.index'),
                now()->subDays(1),
                'weekly',
                0.8
            );

            // Add all tool pages
            $tools = Tool::all();
            foreach ($tools as $tool) {
                $urls[] = $this->createSitemapUrl(
                    route('tools.' . $tool->slug()),
                    $tool->updated_at,
                    'monthly',
                    0.7
                );
            }

            // Add authentication pages (but exclude from sitemap)
            // These are handled by noindex robots directive

            return $this->generateSitemapXml($urls);
        });

        return Response::make($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * Generate sitemap index for large sitemaps
     */
    public function sitemapIndex()
    {
        $sitemapIndex = Cache::remember('sitemap-index.xml', 86400, function () {
            $sitemaps = [
                ['loc' => route('sitemap.index'), 'lastmod' => now()->toAtomString()],
                ['loc' => route('sitemap.tools'), 'lastmod' => now()->toAtomString()],
            ];

            return $this->generateSitemapIndexXml($sitemaps);
        });

        return Response::make($sitemapIndex, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * Create individual sitemap URL entry
     */
    private function createSitemapUrl($loc, $lastmod, $changefreq, $priority)
    {
        return [
            'loc' => $loc,
            'lastmod' => $lastmod->toAtomString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    /**
     * Generate sitemap XML
     */
    private function generateSitemapXml($urls)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url['loc'], ENT_XML1) . "</loc>\n";
            $xml .= "    <lastmod>" . htmlspecialchars($url['lastmod']) . "</lastmod>\n";
            $xml .= "    <changefreq>" . htmlspecialchars($url['changefreq']) . "</changefreq>\n";
            $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate sitemap index XML
     */
    private function generateSitemapIndexXml($sitemaps)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($sitemaps as $sitemap) {
            $xml .= "  <sitemap>\n";
            $xml .= "    <loc>" . htmlspecialchars($sitemap['loc'], ENT_XML1) . "</loc>\n";
            $xml .= "    <lastmod>" . htmlspecialchars($sitemap['lastmod']) . "</lastmod>\n";
            $xml .= "  </sitemap>\n";
        }

        $xml .= '</sitemapindex>';

        return $xml;
    }
}
