@extends('layouts.app')

@section('title', 'Kedia Builders & Colonizers | Plots, Villas & Apartments in Jaipur since 1980')
@section('meta_description', 'Browse, compare and book JDA-approved plots, villas and apartments in Jaipur online. Kedia Builders & Colonizers — 200+ land development projects since 1980.')

@php
    $company = config('kedia.company');
    $field = 'w-full bg-transparent py-1 text-sm font-medium text-navy focus:outline-none';
@endphp

@section('content')

{{-- 1. HERO — the haveli doorway opens onto the drone film --}}
<section class="section-navy overflow-hidden" data-haveli>
    <div class="blueprint-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <x-blueprint class="absolute -left-24 bottom-0 hidden h-[26rem] w-[38rem] text-brass/20 lg:block" />

    <div class="container-site relative grid items-center gap-12 pb-40 pt-12 lg:grid-cols-12 lg:pb-44 lg:pt-16">
        <div class="lg:col-span-6 xl:col-span-6">
            <div class="eyebrow flex items-center gap-3" data-reveal>
                <span class="h-px w-8 bg-brass" aria-hidden="true"></span>{{ __('Jaipur') }} · {{ __('Est.') }} {{ $company['founded'] }}
            </div>
            <h1 class="display-xl mt-6 text-paper" data-reveal data-reveal-delay="120">
                {{ __('Homes drawn') }} <em class="font-light italic text-brass">{{ __('with care,') }}</em><br>{{ __('built to last.') }}
            </h1>
            <p class="mt-6 max-w-xl font-serif text-xl italic leading-snug text-paper/70" data-reveal data-reveal-delay="220">
                “{{ __($company['tagline']) }}”
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-3" data-reveal data-reveal-delay="320">
                <a href="{{ route('projects.index') }}" class="btn-brass">{{ __('Browse projects') }}</a>
                <a href="{{ route('projects.index', ['view' => 'units', 'unit_status' => 'available']) }}" class="btn-ghost-light">{{ $inventory['available'] }} {{ __('units available now') }}</a>
            </div>
        </div>

        <div class="relative mx-auto w-full max-w-md lg:col-span-6 lg:max-w-lg">
            <div class="arch relative aspect-[4/5] border border-brass/40 bg-navy-900 shadow-[0_40px_80px_-40px_rgba(0,0,0,0.8)]">
                <video class="h-full w-full object-cover" autoplay muted loop playsinline preload="metadata" poster="/media/video/jaipur-poster.webp" aria-label="{{ __('Drone film of Kedia townships in Jaipur') }}">
                    <source src="/media/video/jaipur.webm" type="video/webm">
                    <source src="/media/video/jaipur.mp4" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-gradient-to-t from-navy/70 via-transparent to-transparent" aria-hidden="true"></div>
                {{-- the two doors --}}
                <div class="haveli-door haveli-door-left jaali-panel absolute inset-y-0 left-0 w-1/2 border-r border-brass/50 bg-navy-700" aria-hidden="true"></div>
                <div class="haveli-door haveli-door-right jaali-panel absolute inset-y-0 right-0 w-1/2 border-l border-brass/50 bg-navy-700" aria-hidden="true"></div>
                <div class="absolute inset-x-0 bottom-0 p-6 text-center">
                    <div class="label text-brass">{{ __('Now booking') }}</div>
                    <div class="mt-1 font-serif text-2xl text-paper">{{ $featured->first()->name ?? '' }}</div>
                </div>
            </div>
            <div class="absolute -left-6 top-10 hidden rounded-2xl border border-brass/30 bg-navy/90 px-5 py-4 backdrop-blur sm:block" data-reveal data-reveal-delay="900">
                <div class="font-serif text-4xl text-brass"><span data-count="200" data-suffix="+">200+</span></div>
                <div class="label mt-1 text-paper/60">{{ __('Projects delivered') }}</div>
            </div>
        </div>
    </div>

    {{-- product-style search bar --}}
    <div class="container-site absolute inset-x-0 bottom-0 translate-y-1/2">
        <form action="{{ route('projects.index') }}" method="GET" role="search" aria-label="{{ __('Find a home') }}"
              class="grid gap-px overflow-hidden rounded-[1.75rem] border border-navy/10 bg-navy/10 shadow-[0_30px_60px_-30px_rgba(14,21,32,0.55)] sm:grid-cols-2 lg:grid-cols-[repeat(6,minmax(0,1fr))_auto]" data-reveal data-reveal-delay="500">
            <input type="hidden" name="view" value="units">
            <div class="bg-paper-50 px-5 py-4">
                <label for="hs_status" class="label block text-ink/50">{{ __('Status') }}</label>
                <select id="hs_status" name="status" class="{{ $field }}">
                    <option value="">{{ __('Any') }}</option>
                    <option value="ongoing">{{ __('Ongoing') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                </select>
            </div>
            <div class="bg-paper-50 px-5 py-4">
                <label for="hs_location" class="label block text-ink/50">{{ __('Location') }}</label>
                <select id="hs_location" name="location" class="{{ $field }}">
                    <option value="">{{ __('All Jaipur') }}</option>
                    @foreach($search['locations'] as $loc)
                        <option value="{{ $loc }}">{{ $loc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="bg-paper-50 px-5 py-4">
                <label for="hs_beds" class="label block text-ink/50">{{ __('Bedrooms') }}</label>
                <select id="hs_beds" name="bedrooms" class="{{ $field }}">
                    <option value="">{{ __('Any') }}</option>
                    @foreach([2, 3, 4] as $n)<option value="{{ $n }}">{{ $n }} {{ __('BHK') }}</option>@endforeach
                </select>
            </div>
            <div class="bg-paper-50 px-5 py-4">
                <label for="hs_area" class="label block text-ink/50">{{ __('Min. area') }}</label>
                <select id="hs_area" name="min_area" class="{{ $field }}">
                    <option value="">{{ __('Any') }}</option>
                    @foreach([1000, 1500, 2000] as $a)<option value="{{ $a }}">{{ \App\Support\Format::inr($a) }}+ {{ __('sq ft') }}</option>@endforeach
                </select>
            </div>
            <div class="bg-paper-50 px-5 py-4">
                <label for="hs_baths" class="label block text-ink/50">{{ __('Baths') }}</label>
                <select id="hs_baths" name="bathrooms" class="{{ $field }}">
                    <option value="">{{ __('Any') }}</option>
                    @foreach([2, 3, 4] as $n)<option value="{{ $n }}">{{ $n }}+</option>@endforeach
                </select>
            </div>
            <div class="bg-paper-50 px-5 py-4">
                <label for="hs_budget" class="label block text-ink/50">{{ __('Budget') }}</label>
                <select id="hs_budget" name="max_price" class="{{ $field }}">
                    <option value="">{{ __('Any') }}</option>
                    <option value="4000000">{{ __('Up to ₹40 L') }}</option>
                    <option value="6000000">{{ __('Up to ₹60 L') }}</option>
                    <option value="10000000">{{ __('Up to ₹1 Cr') }}</option>
                    <option value="15000000">{{ __('Up to ₹1.5 Cr') }}</option>
                </select>
            </div>
            <button type="submit" class="flex items-center justify-center gap-2 bg-brass px-8 py-4 text-xs font-semibold uppercase tracking-[0.14em] text-navy transition hover:bg-brass-400 sm:col-span-2 lg:col-span-1">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="M20 20l-4.2-4.2"/></svg>
                {{ __('Search') }}
            </button>
        </form>
    </div>
</section>

{{-- 2. FEATURED PROJECTS — carousel of architectural plates --}}
<section class="section-paper pb-24 pt-64 sm:pt-52 lg:pt-36">
    <div class="container-site">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <x-section-head :eyebrow="__('Signature townships')" :title="__('Featured projects')">
                {{ __('Every project is a product: open it to see live availability, plans and price, then shortlist, compare and book online.') }}
            </x-section-head>
            <div class="flex items-center gap-2">
                <button type="button" class="btn-ghost btn-sm" onclick="document.getElementById('featuredTrack').scrollBy({left: -360, behavior: 'smooth'})" aria-label="{{ __('Previous') }}">←</button>
                <button type="button" class="btn-ghost btn-sm" onclick="document.getElementById('featuredTrack').scrollBy({left: 360, behavior: 'smooth'})" aria-label="{{ __('Next') }}">→</button>
                <a href="{{ route('projects.index') }}" class="btn-navy btn-sm">{{ __('All projects') }}</a>
            </div>
        </div>
        <div id="featuredTrack" class="timeline-track -mx-4 mt-12 flex gap-6 overflow-x-auto px-4 pb-6 sm:-mx-8 sm:px-8" tabindex="0" aria-label="{{ __('Featured projects') }}">
            @foreach($featured as $i => $project)
                <x-project-plate :project="$project" :index="$i" class="w-[80vw] flex-none sm:w-[22rem]" />
            @endforeach
        </div>
    </div>
</section>

<div class="jaali bg-paper" aria-hidden="true"></div>

{{-- 3. WHY INVEST — six cards --}}
<section class="section-navy overflow-hidden py-24">
    <div class="jaali-panel pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="container-site relative">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <x-section-head :eyebrow="__('Why invest with Kedia')" :title="__('Six reasons families choose us')" dark>
                    {{ __('Four decades of clear titles, approved layouts and homes handed over as promised.') }}
                </x-section-head>
                <a href="{{ route('about.why') }}" class="btn-ghost-light mt-8" data-reveal>{{ __('Read the full story') }}</a>
            </div>
            <div class="grid gap-px overflow-hidden rounded-[1.75rem] border border-paper/10 bg-paper/10 sm:grid-cols-2 lg:col-span-8">
                @foreach(config('kedia.why_invest') as $i => $why)
                    <div class="group bg-navy p-7 transition hover:bg-navy-700" data-reveal data-reveal-delay="{{ $i * 70 }}">
                        <div class="flex items-center justify-between">
                            <svg class="h-8 w-8 text-brass" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $why['icon'] }}"/></svg>
                            <span class="font-mono text-xs text-paper/30">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h3 class="mt-6 font-serif text-2xl text-paper transition group-hover:text-brass">{{ __($why['title']) }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-paper/60">{{ __($why['text']) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- 4. STATS — oversized count-up numerals --}}
<section class="section-paper py-20">
    <div class="container-site grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
        @foreach(config('kedia.stats') as $i => $stat)
            <div class="border-l border-brass/50 pl-6" data-reveal data-reveal-delay="{{ $i * 90 }}">
                <div class="font-serif text-7xl font-light leading-none tracking-tight text-navy xl:text-8xl">
                    <span data-count="{{ $stat['value'] }}" data-suffix="{{ $stat['suffix'] }}" data-plain="{{ !empty($stat['plain']) ? 'true' : 'false' }}">{{ $stat['value'] }}{{ $stat['suffix'] }}</span>
                </div>
                <div class="label mt-4 text-ink/60">{{ __($stat['label']) }}</div>
            </div>
        @endforeach
    </div>
</section>

{{-- 5. ONGOING vs COMPLETED --}}
<section class="section-paper pb-24">
    <div class="container-site" data-tabs>
        <div class="flex flex-wrap items-end justify-between gap-6 border-t border-navy/10 pt-16">
            <x-section-head :eyebrow="__('Portfolio')" :title="__('Ongoing and completed')" />
            <div class="inline-flex rounded-full border border-navy/15 p-1" role="tablist" aria-label="{{ __('Project status') }}">
                <button type="button" role="tab" id="tab-ongoing" aria-controls="panel-ongoing" aria-selected="true" class="is-active rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-ink/60 transition [&.is-active]:bg-navy [&.is-active]:text-paper">{{ __('Ongoing') }} ({{ $ongoing->count() }})</button>
                <button type="button" role="tab" id="tab-completed" aria-controls="panel-completed" aria-selected="false" class="rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-ink/60 transition [&.is-active]:bg-navy [&.is-active]:text-paper">{{ __('Completed') }} ({{ $completed->count() }})</button>
            </div>
        </div>
        @foreach(['ongoing' => $ongoing, 'completed' => $completed] as $key => $list)
            <div id="panel-{{ $key }}" role="tabpanel" aria-labelledby="tab-{{ $key }}" @if($key !== 'ongoing') hidden @endif class="mt-10 divide-y divide-navy/10 border-y border-navy/10">
                @foreach($list as $project)
                    <a href="{{ route('projects.show', $project->slug) }}" class="group grid items-center gap-4 py-5 transition hover:bg-paper-200/50 sm:grid-cols-12 sm:px-4">
                        <span class="font-serif text-2xl text-navy transition group-hover:text-terracotta sm:col-span-4">{{ $project->name }}</span>
                        <span class="text-sm text-ink/60 sm:col-span-3">{{ $project->location }} · {{ $project->type }}</span>
                        <span class="label text-ink/50 sm:col-span-3">RERA {{ $project->rera_number }}</span>
                        <span class="label text-right sm:col-span-2 {{ $key === 'ongoing' ? 'text-status-available' : 'text-ink/40' }}">
                            @if($key === 'ongoing'){{ $project->available_units_count }} {{ __('units available') }}@else{{ __('Delivered') }}@endif →
                        </span>
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</section>

{{-- 6. LEADERSHIP --}}
<section class="section-navy overflow-hidden py-24">
    <x-blueprint variant="plots" class="absolute -right-20 top-10 hidden h-[24rem] w-[34rem] text-brass/15 lg:block" />
    <div class="container-site relative">
        <x-section-head :eyebrow="__('Leadership')" :title="__('Three generations of builders')" dark>
            {{ __(config('kedia.about.intro')) }}
        </x-section-head>
        <div class="mt-14 grid gap-10 md:grid-cols-3">
            @foreach(config('kedia.leaders') as $i => $leader)
                <article data-reveal data-reveal-delay="{{ $i * 120 }}" class="{{ $i === 1 ? 'md:mt-12' : '' }}">
                    <div class="arch aspect-[4/5] border border-brass/30 bg-navy-700">
                        <img src="{{ $leader['photo'] }}" alt="{{ $leader['name'] }}" loading="lazy" decoding="async" class="h-full w-full object-cover grayscale transition duration-700 hover:grayscale-0">
                    </div>
                    <div class="label mt-6 text-brass">{{ __($leader['role']) }}</div>
                    <h3 class="mt-1 font-serif text-2xl text-paper">{{ __($leader['name']) }}</h3>
                    <p class="label mt-1 text-paper/40">{{ __($leader['meta']) }}</p>
                    <p class="mt-3 text-sm leading-relaxed text-paper/60">{{ __($leader['bio']) }}</p>
                </article>
            @endforeach
        </div>
        <a href="{{ route('about.chairman') }}" class="btn-ghost-light mt-12" data-reveal>{{ __('Meet the leadership') }}</a>
    </div>
</section>

{{-- 7. TESTIMONIALS (sample content) --}}
<section class="section-paper py-24">
    <div class="container-site">
        <x-section-head :eyebrow="__('In their words')" :title="__('What buyers say')" />
        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach(config('kedia.testimonials') as $i => $item)
                <figure class="card flex flex-col" data-reveal data-reveal-delay="{{ $i * 100 }}">
                    <div class="flex items-center justify-between">
                        <span class="font-serif text-6xl leading-none text-brass" aria-hidden="true">“</span>
                        @if(!empty($item['placeholder']))<x-sample-tag />@endif
                    </div>
                    <blockquote class="mt-2 flex-1 font-serif text-xl leading-snug text-navy">{{ __($item['quote']) }}</blockquote>
                    <figcaption class="mt-6 border-t border-navy/10 pt-4">
                        <div class="text-sm font-semibold text-navy">{{ __($item['name']) }}</div>
                        <div class="label text-ink/50">{{ $item['project'] }}</div>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>

{{-- 8. LATEST OFFERS (sample content) --}}
<section class="section-paper pb-24">
    <div class="container-site">
        <div class="overflow-hidden rounded-[2rem] bg-navy text-paper">
            <div class="grid lg:grid-cols-12">
                <div class="relative p-8 sm:p-12 lg:col-span-4">
                    <div class="jaali-panel absolute inset-0" aria-hidden="true"></div>
                    <div class="relative">
                        <div class="eyebrow">{{ __('Latest offers') }}</div>
                        <h2 class="display-md mt-4">{{ __('Open this month') }}</h2>
                        <p class="mt-4 text-sm text-paper/60">{{ __('Ask our team which offers apply to the unit you like.') }}</p>
                        <button type="button" data-open="visitModal" class="btn-brass mt-8">{{ __('Book a Visit') }}</button>
                    </div>
                </div>
                <div class="divide-y divide-paper/10 border-t border-paper/10 lg:col-span-8 lg:border-l lg:border-t-0">
                    @foreach(config('kedia.offers') as $offer)
                        <a href="{{ route('projects.show', $offer['project']) }}" class="group flex items-start justify-between gap-6 p-8 transition hover:bg-white/[0.03]">
                            <div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="font-serif text-2xl transition group-hover:text-brass">{{ __($offer['title']) }}</h3>
                                    @if(!empty($offer['placeholder']))<x-sample-tag />@endif
                                </div>
                                <p class="mt-2 max-w-xl text-sm text-paper/60">{{ __($offer['text']) }}</p>
                            </div>
                            <span class="mt-2 flex-none text-brass transition group-hover:translate-x-1" aria-hidden="true">→</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 9. CTA --}}
<section class="section-paper pb-28">
    <div class="container-site text-center">
        <div class="jaali mx-auto mb-12 max-w-md" aria-hidden="true"></div>
        <h2 class="display-lg mx-auto max-w-3xl text-navy" data-reveal>{{ __('Find your address in Jaipur.') }}</h2>
        <p class="mx-auto mt-5 max-w-xl text-ink/70" data-reveal>{{ __('Browse live inventory, hold a unit with a small online token, and track everything from your account.') }}</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3" data-reveal>
            <a href="{{ route('projects.index', ['view' => 'units']) }}" class="btn-navy">{{ __('Browse all units') }}</a>
            <button type="button" data-open="visitModal" class="btn-ghost">{{ __('Book a Visit') }}</button>
            <a href="tel:{{ $company['phone_href'] }}" class="btn-ghost">{{ $company['phone'] }}</a>
        </div>
    </div>
</section>

@endsection
