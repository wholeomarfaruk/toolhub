<?php

namespace App\Http\Controllers;

use App\Models\SeoPage;
use App\Services\ToolRegistry;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

class SeoFlatSlugController extends Controller
{
    /**
     * Resolve a globally-unique SEO page slug to its owning tool and render
     * that tool's own nested route ({tool}/{seoPageSlug}) internally.
     *
     * NOTE: a bare `Route::dispatch($request)` does NOT work here — it
     * matches the route and calls the controller/Livewire component, but
     * does not rebind the resolved request into the container the way the
     * full HTTP kernel pipeline does. Livewire's own request-scoped state
     * (used while resolving mount() parameters) ends up reading the
     * *outer* request instead of the inner one, so `$seoPageSlug` comes
     * through as null and the component silently renders its defaults
     * (confirmed via tinker: status 200, valid HTML, wire:id/wire:snapshot
     * present, but none of the SEO page's h1/intro/content appeared).
     *
     * Running the sub-request through `$kernel->handle()` reuses the same
     * pipeline a real HTTP request goes through (including the container
     * rebinding Livewire relies on), so it renders identically to hitting
     * the nested URL directly — confirmed working via tinker.
     */
    public function __invoke(Request $request, string $slug)
    {
        $page = SeoPage::where('slug', $slug)
            ->where('status', 'published')
            ->first();

        abort_unless($page, 404);

        $tool = app(ToolRegistry::class)->tryFind($page->tool_slug);
        abort_unless($tool, 404);

        $subRequest = Request::create(
            route('tools.'.$tool->slug().'.seo', ['seoPageSlug' => $slug]),
            'GET'
        );

        // Carry over cookies/session so auth-aware rendering (e.g. export
        // feature checks) behaves the same as a direct request would.
        $subRequest->cookies = $request->cookies;
        if ($request->hasSession()) {
            $subRequest->setLaravelSession($request->session());
        }

        $kernel = app(Kernel::class);
        $response = $kernel->handle($subRequest);
        $kernel->terminate($subRequest, $response);

        return $response;
    }
}
