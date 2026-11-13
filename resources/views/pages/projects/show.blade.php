@extends('layouts.app')

@php
    $gallery = $project->galleryImages();
    $ongoing = $project->status !== 'completed';
    $hasMap = $project->latitude && $project->longitude;
    $lat = (float) $project->latitude; $lng = (float) $project->longitude;
    $mapSrc = $hasMap ? 'https://www.openstreetmap.org/export/embed.html?bbox=' . ($lng - 0.02) . '%2C' . ($lat - 0.012) . '%2C' . ($lng + 0.02) . '%2C' . ($lat + 0.012) . '&layer=mapnik&marker=' . $lat . '%2C' . $lng : null;
    $description = strip_tags((string) $project->description) ?: ($project->name . ' by Kedia Builders & Colonizers in ' . $project->location . ', Jaipur.');
@endphp

@section('title', $project->title . ' · ' . $project->location . ' | Kedia Builders & Colonizers')
@section('meta_description', \Illuminate\Support\Str::limit($description, 155))
@section('og_image', $project->hero_image)

@push('structured_data')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'Residence',
    'name' => $project->title,
    'description' => \Illuminate\Support\Str::limit($description, 300),
    'url' => url()->current(),
    'image' => url($project->hero_image),
    'identifier' => $project->rera_number ? 'RERA ' . $project->rera_number : null,
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => $project->address ?: $project->location, 'addressLocality' => 'Jaipur', 'addressRegion' => 'Rajasthan', 'addressCountry' => 'IN'],
    'geo' => $hasMap ? ['@type' => 'GeoCoordinates', 'latitude' => $lat, 'longitude' => $lng] : null,
    'amenityFeature' => collect($project->amenities ?? [])->map(fn ($a) => ['@type' => 'LocationFeatureSpecification', 'name' => $a, 'value' => true])->all() ?: null,
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endpush

@section('content')
<div data-recent="{{ json_encode($project->shopItem()) }}"></div>

{{-- HERO --}}
<section class="section-navy overflow-hidden">
    <div class="blueprint-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="container-site relative grid items-end gap-10 py-12 lg:grid-cols-12 lg:py-16">
        <div class="lg:col-span-7">
            <nav class="label text-paper/40" aria-label="{{ __('Breadcrumb') }}">
                <a href="{{ route('home') }}" class="hover:text-brass">{{ __('Home') }}</a> /
                <a href="{{ route('projects.index') }}" class="hover:text-brass">{{ __('Projects') }}</a> / {{ $project->name }}
            </nav>
            <div class="mt-6 flex flex-wrap items-center gap-2" data-reveal>
                <span class="label rounded-full px-3 py-1 {{ $ongoing ? 'bg-brass text-navy' : 'bg-paper text-navy' }}">{{ $ongoing ? __('Ongoing') : __('Completed') }}</span>
                <span class="chip border-paper/20 text-paper/70">{{ $project->type }}</span>
                @if($project->rera_number)<span class="chip border-brass/40 text-brass">RERA {{ $project->rera_number }}</span>@endif
            </div>
            <h1 class="display-xl mt-5 text-paper" data-reveal data-reveal-delay="100">{{ $project->name }}</h1>
            <p class="mt-4 flex items-center gap-2 text-lg text-paper/70" data-reveal data-reveal-delay="160">
                <svg class="h-5 w-5 text-brass" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 21c-4-3-7-6.5-7-10a7 7 0 0114 0c0 3.5-3 7-7 10z"/><circle cx="12" cy="11" r="2.5"/></svg>
                {{ $project->address ?: $project->location . ', Jaipur' }}
            </p>
            <p class="mt-5 max-w-2xl leading-relaxed text-paper/70" data-reveal data-reveal-delay="220">{{ $description }}</p>

            <dl class="mt-8 grid max-w-xl grid-cols-3 gap-6 border-t border-paper/10 pt-6" data-reveal data-reveal-delay="280">
                <div><dt class="label text-paper/40">{{ __('Starting') }}</dt><dd class="mt-1 font-serif text-2xl text-brass">{{ str_replace(['Starting at ', '*'], '', $project->price_range) }}</dd></div>
                <div><dt class="label text-paper/40">{{ __('Possession') }}</dt><dd class="mt-1 text-sm text-paper">{{ $project->possession_label ?: '—' }}</dd></div>
                <div><dt class="label text-paper/40">{{ __('Available') }}</dt><dd class="mt-1 font-serif text-2xl text-paper">{{ $unitStats['available'] }}<span class="text-base text-paper/40"> / {{ $unitStats['total'] }}</span></dd></div>
            </dl>

            <div class="mt-8 flex flex-wrap gap-3" data-reveal data-reveal-delay="340">
                @if($unitStats['total'] > 0)<a href="#inventory" class="btn-brass">{{ __('Choose a unit') }}</a>@endif
                <button type="button" data-open="visitModal" class="btn-ghost-light">{{ __('Schedule a visit') }}</button>
                @if($project->brochure_url)
                    <a href="{{ $project->brochure_url }}" download class="btn-ghost-light">{{ __('Download Brochure (PDF)') }}</a>
                @endif
                <button type="button" data-wishlist="{{ json_encode($project->shopItem()) }}" aria-pressed="false" class="btn-ghost-light [&.is-on]:border-terracotta [&.is-on]:text-terracotta">
                    <svg class="h-4 w-4 [.is-on_&]:fill-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M12 20s-7-4.4-7-10a4 4 0 017-2.6A4 4 0 0119 10c0 5.6-7 10-7 10z"/></svg>
                    {{ __('Shortlist') }}
                </button>
            </div>
        </div>
        <div class="lg:col-span-5">
            <div class="arch aspect-[4/5] border border-brass/40 bg-navy-900" data-reveal>
                <img fetchpriority="high" src="{{ $project->hero_image }}" alt="{{ $project->name }}, {{ $project->location }}" class="h-full w-full object-cover" data-parallax="0.08">
            </div>
        </div>
    </div>
</section>

{{-- IN-PAGE NAV --}}
<nav class="no-print sticky top-[76px] z-30 border-b border-navy/10 bg-paper/95 backdrop-blur" aria-label="{{ __('On this page') }}">
    <div class="container-site flex gap-6 overflow-x-auto py-3 text-xs font-semibold uppercase tracking-[0.14em] text-ink/60">
        @foreach(array_filter(['gallery' => $gallery ? __('Gallery') : null, 'inventory' => __('Units'), 'amenities' => __('Amenities'), 'plans' => __('Plans & specs'), 'location' => __('Location'), 'progress' => $ongoing ? __('Progress') : null, 'rera' => __('RERA')]) as $id => $label)
            <a href="#{{ $id }}" class="flex-none transition hover:text-terracotta">{{ $label }}</a>
        @endforeach
    </div>
</nav>

{{-- GALLERY + VIDEO + 360 --}}
@if($gallery || $project->video_url)
<section id="gallery" class="section-paper scroll-mt-32 py-20">
    <div class="container-site">
        <x-section-head :eyebrow="__('Gallery')" :title="__('See it before you visit')" />
        <div class="mt-10 grid gap-4 lg:grid-cols-12">
            @if($project->video_url)
                <div class="overflow-hidden rounded-[1.5rem] bg-navy lg:col-span-7" data-reveal>
                    <video class="aspect-video w-full object-cover" controls muted playsinline preload="none" poster="{{ $project->hero_image }}" aria-label="{{ __('Drone flyover of') }} {{ $project->name }}">
                        <source src="{{ $project->video_url }}" type="video/mp4">
                    </video>
                    <div class="flex items-center justify-between px-5 py-3 text-paper"><span class="label text-brass">{{ __('Drone flyover') }}</span><span class="text-xs text-paper/50">{{ $project->name }}</span></div>
                </div>
            @endif
            <div class="relative overflow-hidden rounded-[1.5rem] bg-navy {{ $project->video_url ? 'lg:col-span-5' : 'lg:col-span-12' }}" data-reveal>
                <img src="{{ $project->aerial_backdrop ?: $project->hero_image }}" alt="" loading="lazy" decoding="async" class="h-full max-h-[26rem] w-full object-cover opacity-60">
                <div class="absolute inset-0 grid place-items-center p-6 text-center text-paper">
                    <div>
                        <div class="mx-auto grid h-16 w-16 place-items-center rounded-full border border-brass text-brass font-mono text-sm">360°</div>
                        <div class="mt-4 font-serif text-2xl">{{ __('360° site tour') }}</div>
                        <p class="mx-auto mt-2 max-w-xs text-sm text-paper/70">{{ __('The interactive walkthrough is being produced. Ask for a live video tour in the meantime.') }}</p>
                        <div class="mt-4 flex justify-center gap-2"><x-sample-tag /><button type="button" data-open="visitModal" class="btn-brass btn-sm">{{ __('Request a video tour') }}</button></div>
                    </div>
                </div>
            </div>
        </div>
        @if($gallery)
            <div class="masonry mt-4">
                @foreach($gallery as $i => $img)
                    <a href="{{ $img['src'] }}" data-lightbox data-src="{{ $img['src'] }}" data-caption="{{ $img['caption'] }}" class="group block overflow-hidden rounded-2xl bg-navy" data-reveal>
                        <img src="{{ $img['thumb'] }}" alt="{{ $img['caption'] }}" loading="lazy" decoding="async" class="w-full transition duration-700 group-hover:scale-105 group-hover:opacity-80">
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif

{{-- INVENTORY --}}
<section id="inventory" class="section-paper scroll-mt-32 pb-20 {{ $gallery || $project->video_url ? '' : 'pt-20' }}">
    <div class="container-site">
        <x-section-head :eyebrow="__('Live availability')" :title="__('Choose your unit')">
            {{ __('Colours show what is available right now. Select a unit to see its price, then book it online or open the full details.') }}
        </x-section-head>
        <x-inventory :project="$project" :units="$project->units" class="mt-10" />

        @if($project->units->isNotEmpty())
            <div class="mt-12 flex flex-wrap items-end justify-between gap-4">
                <h3 class="font-serif text-3xl text-navy">{{ __('All units in this project') }}</h3>
                <a href="{{ route('projects.index', ['view' => 'units', 'q' => $project->name]) }}" class="label text-ink/50 underline hover:text-terracotta">{{ __('Open in the shop with filters') }}</a>
            </div>
            <div class="mt-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($project->units->sortBy(fn ($u) => ['available' => 0, 'on_hold' => 1, 'sold' => 2][$u->status] ?? 3)->take(9) as $unit)
                    <x-unit-card :unit="$unit" />
                @endforeach
            </div>
            @if($project->units->count() > 9)
                <div class="mt-8 text-center"><a href="{{ route('projects.index', ['view' => 'units', 'q' => $project->name]) }}" class="btn-ghost">{{ __('See all') }} {{ $project->units->count() }} {{ __('units') }}</a></div>
            @endif
        @endif
    </div>
</section>

<div class="jaali bg-paper" aria-hidden="true"></div>

{{-- AMENITIES --}}
<section id="amenities" class="section-navy scroll-mt-32 overflow-hidden py-20">
    <div class="jaali-panel pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="container-site relative grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <x-section-head :eyebrow="__('Amenities')" :title="__('Life inside the gates')" dark />
        </div>
        <div class="lg:col-span-8">
            @php $amenities = $project->amenities ?: ['JDA approved layout', 'Wide internal roads', 'Gated entrance', 'Street lighting', 'Water & electricity connections']; @endphp
            <ul class="grid gap-px overflow-hidden rounded-[1.5rem] border border-paper/10 bg-paper/10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($amenities as $i => $amenity)
                    <li class="flex items-center gap-3 bg-navy px-5 py-4 text-sm text-paper/85" data-reveal data-reveal-delay="{{ ($i % 6) * 50 }}">
                        <span class="font-mono text-[10px] text-brass">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>{{ $amenity }}
                    </li>
                @endforeach
            </ul>
            @if(empty($project->amenities))<p class="mt-3 flex items-center gap-2 text-xs text-paper/40"><x-sample-tag /> {{ __('Typical township amenities shown; project-specific list to be confirmed.') }}</p>@endif
        </div>
    </div>
</section>

{{-- PLANS & SPECIFICATIONS --}}
<section id="plans" class="section-paper scroll-mt-32 py-20">
    <div class="container-site">
        <x-section-head :eyebrow="__('Plans & specifications')" :title="__('What you get')" />
        <div class="mt-10 grid gap-8 lg:grid-cols-12">
            <div class="space-y-3 lg:col-span-5">
                <h3 class="label text-ink/50">{{ __('Configurations') }}</h3>
                @forelse($project->configs ?? [] as $config)
                    <div class="card flex items-center justify-between gap-4" data-reveal>
                        <div>
                            <div class="font-serif text-xl text-navy">{{ $config['label'] ?? '' }}</div>
                            <div class="label mt-1 text-ink/50">{{ $config['unit_type'] ?? '' }}</div>
                        </div>
                        <div class="text-right font-mono text-xs text-ink/70">
                            @if(!empty($config['plot_sqyd']))<div>{{ rtrim(rtrim(number_format((float) $config['plot_sqyd'], 2), '0'), '.') }} {{ __('sq yd') }}</div>@endif
                            @if(!empty($config['builtup_sqft']))<div>{{ \App\Support\Format::inr($config['builtup_sqft']) }} {{ __('sq ft') }}</div>@endif
                            @if(!empty($config['carpet_sqft']))<div>{{ __('Carpet') }} {{ \App\Support\Format::inr($config['carpet_sqft']) }}</div>@endif
                        </div>
                    </div>
                @empty
                    @foreach($project->units->groupBy('configuration')->take(4) as $configuration => $group)
                        <div class="card flex items-center justify-between gap-4" data-reveal>
                            <div class="font-serif text-xl text-navy">{{ $configuration }}</div>
                            <div class="label text-ink/50">{{ $group->count() }} {{ __('units') }}</div>
                        </div>
                    @endforeach
                @endforelse
                @if($project->units->isNotEmpty())
                    <x-floor-plan :unit="$project->units->first()" class="mt-4" />
                @endif
            </div>
            <div class="lg:col-span-7">
                <h3 class="label text-ink/50">{{ __('Specifications') }}</h3>
                <div class="mt-3 divide-y divide-navy/10 border-y border-navy/10">
                    @foreach($project->specs ?? [] as $spec)
                        <details class="group py-4" @if($loop->first) open @endif>
                            <summary class="flex cursor-pointer list-none items-center justify-between font-serif text-xl text-navy">
                                {{ $spec['group'] ?? '' }}
                                <span class="text-brass transition group-open:rotate-45" aria-hidden="true">+</span>
                            </summary>
                            <ul class="mt-3 space-y-2 text-sm text-ink/70">
                                @foreach($spec['items'] ?? [] as $item)
                                    <li class="flex gap-3"><span class="mt-2 h-1 w-1 flex-none rounded-full bg-brass" aria-hidden="true"></span>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- LOCATION --}}
<section id="location" class="section-paper scroll-mt-32 pb-20">
    <div class="container-site grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <x-section-head :eyebrow="__('Location')" :title="$project->location" />
            <p class="mt-4 text-ink/70">{{ $project->address ?: $project->location . ', Jaipur, Rajasthan' }}</p>
            <ul class="mt-6 divide-y divide-navy/10 border-y border-navy/10">
                @foreach(config('kedia.landmarks') as $landmark)
                    <li class="flex items-center justify-between gap-4 py-3 text-sm">
                        <span class="text-navy">{{ __($landmark['name']) }}</span>
                        <span class="font-mono text-xs text-ink/60">{{ $landmark['distance'] }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="mt-3 flex items-center gap-2 text-xs text-ink/50"><x-sample-tag /> {{ __('Approximate travel times, to be verified per project.') }}</p>
            @if($hasMap)
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $lat }},{{ $lng }}" target="_blank" rel="noopener" class="btn-navy mt-6">{{ __('Get directions') }}</a>
            @endif
        </div>
        <div class="lg:col-span-7">
            <div class="overflow-hidden rounded-[1.5rem] border border-navy/10 bg-paper-200" data-reveal>
                @if($mapSrc)
                    <iframe src="{{ $mapSrc }}" title="{{ __('Map showing') }} {{ $project->name }}" loading="lazy" referrerpolicy="no-referrer" class="h-[26rem] w-full border-0 grayscale-[35%]"></iframe>
                @else
                    <div class="grid h-[26rem] place-items-center p-8 text-center">
                        <div><x-blueprint variant="plots" class="mx-auto h-32 w-48 text-brass" /><p class="mt-4 text-sm text-ink/60">{{ __('Map coordinates for this project are being added. Call us for directions.') }}</p></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- CONSTRUCTION PROGRESS --}}
@if($ongoing)
<section id="progress" class="section-navy scroll-mt-32 py-20">
    <div class="container-site">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <x-section-head :eyebrow="__('Construction progress')" :title="__('Where the site stands')" dark />
            <x-sample-tag />
        </div>
        <ol class="mt-12 grid gap-6 md:grid-cols-5">
            @foreach(config('kedia.construction') as $i => $phase)
                <li class="relative border-t-2 pt-5 {{ $phase['progress'] >= 100 ? 'border-brass' : 'border-paper/15' }}" data-reveal data-reveal-delay="{{ $i * 90 }}">
                    <span class="absolute -top-[7px] left-0 h-3 w-3 rounded-full {{ $phase['progress'] > 0 ? 'bg-brass' : 'bg-paper/20' }}" aria-hidden="true"></span>
                    <div class="font-serif text-4xl text-paper">{{ $phase['progress'] }}<span class="text-lg text-paper/40">%</span></div>
                    <div class="mt-2 text-sm text-paper/85">{{ __($phase['phase']) }}</div>
                    <div class="label mt-1 text-paper/40">{{ __($phase['date']) }}</div>
                </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

{{-- RERA --}}
<section id="rera" class="section-paper scroll-mt-32 py-20">
    <div class="container-site">
        <div class="grid items-center gap-8 rounded-[2rem] border border-navy/10 bg-paper-50 p-8 sm:p-12 lg:grid-cols-[minmax(0,1fr)_auto]">
            <div>
                <div class="eyebrow">{{ __('RAJ-RERA registration') }}</div>
                <div class="mt-3 font-mono text-3xl text-navy sm:text-4xl">{{ $project->rera_number ?: '—' }}</div>
                <p class="mt-4 max-w-xl text-sm leading-relaxed text-ink/70">
                    {{ __('This project is registered with the Rajasthan Real Estate Regulatory Authority. Verify the registration, approved plans and quarterly updates on the official portal.') }}
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="https://rera.rajasthan.gov.in/" target="_blank" rel="noopener" class="btn-navy">{{ __('Open RAJ-RERA portal') }}</a>
                    <a href="{{ route('legal') }}" class="btn-ghost">{{ __('RERA disclosures') }}</a>
                </div>
            </div>
            <div class="text-center">
                <div id="reraQr" class="mx-auto grid h-40 w-40 place-items-center rounded-2xl border border-navy/10 bg-white p-3" role="img" aria-label="{{ __('QR code linking to the RAJ-RERA portal') }}"></div>
                <div class="label mt-3 text-ink/50">{{ __('Scan to verify') }}</div>
            </div>
        </div>
    </div>
</section>

{{-- RELATED --}}
@if($relatedProjects->isNotEmpty())
<section class="section-paper pb-24">
    <div class="container-site">
        <x-section-head :eyebrow="__('Keep exploring')" :title="__('Similar projects')" />
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($relatedProjects as $i => $related)
                <x-project-plate :project="$related" :index="$i" />
            @endforeach
        </div>
        <section class="mt-16 border-t border-navy/10 pt-10" data-recent-section hidden aria-label="{{ __('Recently viewed') }}">
            <h2 class="eyebrow">{{ __('Recently viewed') }}</h2>
            <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4" data-recent-list></div>
        </section>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/qrcode.js') }}"></script>
<script>
(function () {
    const box = document.getElementById('reraQr');
    if (!box || typeof qrcode !== 'function') return;
    const qr = qrcode(0, 'M');
    qr.addData('https://rera.rajasthan.gov.in/');
    qr.make();
    box.innerHTML = qr.createSvgTag({ cellSize: 4, margin: 0, scalable: true });
    const svg = box.querySelector('svg');
    if (svg) { svg.setAttribute('aria-hidden', 'true'); svg.style.width = '100%'; svg.style.height = '100%'; }
})();
</script>
@endpush
