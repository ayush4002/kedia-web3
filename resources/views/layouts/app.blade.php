@php
    $company = config('kedia.company');
    $pageTitle = html_entity_decode(trim($__env->yieldContent('title', 'Kedia Builders & Colonizers | Jaipur Real Estate since 1980')), ENT_QUOTES);
    $pageDescription = html_entity_decode(trim($__env->yieldContent('meta_description', 'Browse, compare and book JDA-approved plots, villas and apartments by Kedia Builders & Colonizers, Jaipur — building since 1980.')), ENT_QUOTES);
    $pageImage = asset(ltrim(trim($__env->yieldContent('og_image', 'assets/og-default.jpg')), '/'));
    $navProjects = \App\Models\Project::whereIn('slug', config('kedia.nav_projects'))->get(['slug', 'name', 'location', 'status', 'rera_number'])->sortBy(fn ($p) => array_search($p->slug, config('kedia.nav_projects')));
    $locale = app()->getLocale();

    // Strings written by site.js, translated for the current locale.
    $jsI18nKeys = ['View', 'Remove', 'Unit', 'Project', 'Projects', 'Units', 'Add a unit', 'Saved to your shortlist', 'Removed from your shortlist',
        'You can compare up to 3 units', 'Your shortlist is empty. Tap the heart on any project or unit to save it here.',
        'No matches. Try a project name, location or unit code.', 'Finding the best matches…', 'See all matches', 'Something went wrong. Please try again.',
        'Plot', 'Available', 'On Hold', 'Sold', 'Unit Reserved / Sold', 'View Available Units →'];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0E1520">
    <script>document.documentElement.classList.add('js');</script>

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="@yield('robots', 'index, follow')">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $company['name'] }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:locale" content="{{ $locale === 'hi' ? 'hi_IN' : 'en_IN' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'RealEstateAgent',
        'name' => $company['name'],
        'slogan' => $company['tagline'],
        'url' => url('/'),
        'logo' => asset('assets/logo-navy.png'),
        'image' => asset('assets/og-default.jpg'),
        'telephone' => $company['phone_href'],
        'email' => $company['email'],
        'foundingDate' => (string) $company['founded'],
        'sameAs' => array_column(config('kedia.socials'), 'url'),
        'address' => ['@type' => 'PostalAddress', 'streetAddress' => 'Sikar Road & Bani Park', 'addressLocality' => 'Jaipur', 'addressRegion' => 'Rajasthan', 'postalCode' => '302016', 'addressCountry' => 'IN'],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>
    @stack('structured_data')

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon-32.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/favicon-512.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..700;1,9..144,300..600&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/site.css') }}?v={{ @filemtime(public_path('assets/site.css')) }}">
    @stack('head')
</head>
<body class="min-h-screen @yield('body_class')">
    <a href="#main" class="sr-only z-[100] rounded-full bg-brass px-4 py-2 text-navy focus:not-sr-only focus:fixed focus:left-4 focus:top-4">{{ __('Skip to content') }}</a>

    @include('components.header', ['navProjects' => $navProjects])

    @if(session('success'))
        <div role="status" class="no-print fixed right-4 top-24 z-[75] flex max-w-sm items-start gap-3 rounded-2xl border border-brass/40 bg-navy px-5 py-4 text-sm text-paper shadow-2xl">
            <span class="mt-1.5 h-2 w-2 flex-none rounded-full bg-status-available"></span>
            <div class="flex-1">{{ session('success') }}</div>
            <button type="button" onclick="this.parentElement.remove()" class="text-paper/50 hover:text-paper" aria-label="{{ __('Dismiss') }}">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div role="alert" class="no-print fixed right-4 top-24 z-[75] flex max-w-sm items-start gap-3 rounded-2xl border border-terracotta/60 bg-navy px-5 py-4 text-sm text-paper shadow-2xl">
            <span class="mt-1.5 h-2 w-2 flex-none rounded-full bg-terracotta"></span>
            <div class="flex-1">{{ session('error') }}</div>
            <button type="button" onclick="this.parentElement.remove()" class="text-paper/50 hover:text-paper" aria-label="{{ __('Dismiss') }}">&times;</button>
        </div>
    @endif
    @if($errors->any())
        <div role="alert" class="no-print fixed right-4 top-24 z-[75] max-w-sm rounded-2xl border border-terracotta/60 bg-navy px-5 py-4 text-sm text-paper shadow-2xl">
            <div class="flex items-start justify-between gap-3">
                <div class="font-semibold">{{ __('Please check the form and try again:') }}</div>
                <button type="button" onclick="this.closest('[role=alert]').remove()" class="text-paper/50 hover:text-paper" aria-label="{{ __('Dismiss') }}">&times;</button>
            </div>
            <ul class="mt-2 list-disc space-y-0.5 pl-5 text-paper/80">
                @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main id="main">
        @yield('content')
    </main>

    @include('components.footer')
    @include('components.overlays', ['navProjects' => $navProjects])

    <script>
        window.KEDIA_I18N = @json(collect($jsI18nKeys)->mapWithKeys(fn ($k) => [$k => __($k)]));
    </script>
    <script src="{{ asset('assets/vendor/gsap.min.js') }}" defer></script>
    <script src="{{ asset('assets/vendor/ScrollTrigger.min.js') }}" defer></script>
    <script src="{{ asset('assets/vendor/lenis.min.js') }}" defer></script>
    <script src="{{ asset('assets/site.js') }}?v={{ @filemtime(public_path('assets/site.js')) }}" defer></script>
    @stack('scripts')
</body>
</html>
