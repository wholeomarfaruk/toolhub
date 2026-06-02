# SEO Fixes Required - Implementation Guide

## 🔴 CRITICAL FIX #1: Pass SEO Data to Layout

### Problem
Livewire components set `$this->title` but layout expects `$title` variable.

### Solution

Each Livewire component needs to properly pass SEO data when rendering. Here's the pattern:

#### Step 1: Update Livewire Component Class

```php
// Example: app/Livewire/Website/Home/Home.php

class Home extends Component
{
    // Public properties for SEO (will be passed to view)
    public string $title = '';
    public string $description = '';
    public string $keywords = '';
    public string $og_title = '';
    public string $og_description = '';
    public string $og_url = '';
    public string $og_image = '';
    public string $canonical_url = '';
    public string $twitter_title = '';
    public string $twitter_description = '';
    public string $twitter_image = '';

    public function mount()
    {
        // Set SEO data in mount, not in view
        $this->title = 'Free Online Tools | ' . config('app.name');
        $this->description = 'Access 50+ free online tools...';
        $this->keywords = 'free tools, online calculator...';
        $this->og_title = 'Free Online Tools | ' . config('app.name');
        $this->og_description = 'Discover 50+ free online tools...';
        $this->og_url = route('home');
        $this->og_image = asset('images/og-home.jpg');
        $this->canonical_url = route('home');
        $this->twitter_title = $this->og_title;
        $this->twitter_description = $this->og_description;
        $this->twitter_image = $this->og_image;
    }

    public function render()
    {
        // Pass SEO data to layout
        return view('livewire.website.home.home')
            ->layout('layouts.website.website', [
                'title' => $this->title,
                'description' => $this->description,
                'keywords' => $this->keywords,
                'og_type' => 'website',
                'og_title' => $this->og_title,
                'og_description' => $this->og_description,
                'og_url' => $this->og_url,
                'og_image' => $this->og_image,
                'canonical_url' => $this->canonical_url,
                'twitter_title' => $this->twitter_title,
                'twitter_description' => $this->twitter_description,
                'twitter_image' => $this->twitter_image,
            ]);
    }
}
```

#### Step 2: Remove SEO Config from Blade View

Remove the @php section that sets `$this->title`:

```blade
{{-- REMOVE THIS FROM home.blade.php --}}
@php
    // SEO Configuration
    if (empty($this->title)) {
        $this->title = 'Free Online Tools | ' . config('app.name');
        // ... etc
    }
@endphp

{{-- INSTEAD: Set in mount() method of component class --}}
```

#### Step 3: Update Layout Title

```blade
{{-- In layout/website.blade.php, change: --}}
<title>{{ $title ?? config('app.name') }} — Online Tools</title>

{{-- TO: --}}
<title>{{ $title ?? config('app.name') }}</title>
```

---

## 🔴 CRITICAL FIX #2: Verify Tool Model

### Check if Tool Model Exists

```bash
# In your terminal
php artisan tinker
> App\Models\Tool::count()
> App\Models\Tool::first()->slug()
```

### If Model Doesn't Exist, Create It

```bash
php artisan make:model Tool -m
```

Then create migration with slug field:
```php
Schema::create('tools', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description');
    $table->timestamps();
});
```

---

## 🟡 HIGH FIX #3: Set Up SeoHelper Autoload

### Update composer.json

```json
"autoload": {
    "classmap": [
        "database/seeders"
    ],
    "psr-4": {
        "App\\": "app/",
        "App\\Helpers\\": "app/Helpers/"
    }
}
```

Then run:
```bash
composer dump-autoload
```

---

## 🟡 HIGH FIX #4: Create Missing Image Files

Create or add these files to `/public/images/`:

```bash
# Create directory
mkdir public/images

# Create OG images (1200x630px recommended)
# You can use:
# 1. Canva (canva.com) - Free tool to create images
# 2. ImageMagick or similar to generate programmatically
# 3. Placeholder service like placeholder.com
```

### Quick Placeholder Solution

```php
// In config/seo.php, use placeholder for now:
'og_image' => env('OG_IMAGE_URL', 'https://via.placeholder.com/1200x630/4f46e5/ffffff?text=ToolsHub'),
```

### Permanent Solution - Create Real Images

You can automate this:

```php
// Create a seeder to generate placeholder images
php artisan make:seeder GenerateOgImagesSeeder

// Or manually add images to public/images/:
public/images/og-home.jpg
public/images/og-tool-list.jpg
public/images/og-age-calculator.jpg
// etc.
```

---

## 🟡 HIGH FIX #5: Create Favicon Files

Add these files to `/public/`:

```
public/favicon.ico         (16x16 or 32x32)
public/apple-touch-icon.png (180x180)
public/manifest.json        (optional, for PWA)
```

Quick solution using favicon generator:
```html
<!-- Generate at: https://favicon-generator.org/ -->
<!-- Or use ImageMagick: -->
<!-- convert -background none icon.png -define icon:auto-resize=16,32,48,64,128,256 favicon.ico -->
```

---

## 🟢 MEDIUM FIX #6: Verify Routes

Check that all tool route names match:

```php
// In routes/tools.php, routes should be named:
Route::get('/{slug}', [ToolPage::class])->name('show');  // ❌ WRONG

// Should be:
Route::get('/{slug}', [ToolPage::class])->name('tools.age-calculator'); // ✅ CORRECT
// OR in web.php under tools group:
Route::get('/age-calculator', AgeCalculatorComponent::class)->name('age-calculator');
```

Verify with:
```bash
php artisan route:list | grep tools
```

---

## 🟢 MEDIUM FIX #7: Fix Duplicate Title Suffix

```blade
{{-- Change in layout/website.blade.php line 85 --}}
<!-- FROM: -->
<title>{{ $title ?? config('app.name') }} — Online Tools</title>

<!-- TO: -->
<title>{{ $title ?? config('app.name') }}</title>

<!-- Since $title already includes the suffix -->
```

---

## 🟢 MEDIUM FIX #8: Create Logo File

Add logo to `/public/images/`:

```bash
public/images/logo.png (150x150 or 200x200)
```

---

## Priority Order to Fix

1. ✅ **FIRST:** Fix #1 - Pass SEO data to layout (CRITICAL)
2. ✅ **SECOND:** Fix #2 - Verify Tool model exists
3. ✅ **THIRD:** Fix #3 - Set up autoload
4. ✅ **FOURTH:** Fix #4 & #5 - Create image/favicon files
5. ✅ **FIFTH:** Fix #7 - Fix title format

---

## Verification Script

After making fixes, run this to verify:

```php
// Create a test route or artisan command
php artisan tinker

// Check layout rendering
view('layouts.website.website', ['title' => 'Test'])->render();

// Check routes
Route::getRoutes()->getByName('home');
Route::getRoutes()->getByName('tools.index');

// Check controllers
app(SitemapController::class);
app(RobotsController::class);

// Check helper
SeoHelper::getToolSeoData('age-calculator');
```

---

## Next Steps

1. ✅ Implement fixes in priority order
2. ✅ Run verification checks
3. ✅ Test each page with browser dev tools
4. ✅ Submit sitemap to Google Search Console
5. ✅ Monitor search console for errors

---

**Last Updated:** 2026-06-02
