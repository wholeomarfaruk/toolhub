<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class RobotsController extends Controller
{
    /**
     * Generate robots.txt for search engines
     */
    public function index()
    {
        $isProduction = app()->environment('production');

        if ($isProduction) {
            $robots = $this->generateProductionRobots();
        } else {
            $robots = $this->generateDevelopmentRobots();
        }

        return Response::make($robots, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * Generate robots.txt for production environment
     */
    private function generateProductionRobots()
    {
        $sitemapUrl = route('sitemap.index');
        $appUrl = config('app.url');

        return <<<EOT
# ═══════════════════════════════════════════════════════════════
# Robots.txt for ToolsHub
# ═══════════════════════════════════════════════════════════════
# This file tells search engines which pages to crawl and which to ignore

# Allow all bots
User-agent: *
Allow: /

# Disallow private/admin pages
Disallow: /admin/
Disallow: /api/
Disallow: /dashboard/
Disallow: /auth/
Disallow: /checkout/

# Disallow duplicate content
Disallow: /*?*sort=
Disallow: /*?*filter=
Disallow: /*?*page=

# Allow public crawling of all other pages
Allow: /

# Specific bots
User-agent: Googlebot
Allow: /

User-agent: Bingbot
Allow: /

User-agent: Slurp
Allow: /

User-agent: DuckDuckBot
Allow: /

# Sitemap location
Sitemap: {$appUrl}/sitemap.xml
Sitemap: {$appUrl}/sitemap-index.xml

# Crawl delay (delay in seconds between requests)
Crawl-delay: 1

# Request rate (requests per second)
Request-rate: 10/1s

# Comment out specific bot restrictions if needed
# User-agent: AhrefsBot
# Disallow: /

# User-agent: SemrushBot
# Disallow: /
EOT;
    }

    /**
     * Generate robots.txt for development environment
     */
    private function generateDevelopmentRobots()
    {
        return <<<EOT
# ═══════════════════════════════════════════════════════════════
# Robots.txt for ToolsHub (Development)
# ═══════════════════════════════════════════════════════════════

# Disallow all bots in development
User-agent: *
Disallow: /

# Allow Google to verify site (optional)
# User-agent: Googlebot
# Allow: /
EOT;
    }
}
