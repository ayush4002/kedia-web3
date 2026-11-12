@extends('layouts.app')

@section('title', ($view === 'units' ? 'Plots, Villas & Apartments for Sale in Jaipur' : 'Projects & Townships in Jaipur') . ' | Kedia Builders & Colonizers')
@section('meta_description', 'Shop Kedia Builders projects and units in Jaipur: filter by status, location, bedrooms, area, baths, type and budget. Shortlist, compare up to 3 and book online.')

@php
    // Build a URL that keeps the current filters but changes some of them.
    $with = fn (array $changes) => route('projects.index', array_filter(array_merge(request()->except('page'), $changes), fn ($v) => $v !== null && $v !== ''));
    $classLabel = fn ($c) => $c === 'Single-Family' ? __('Single Family') : __($c);
    $filterLabels = [
        'q' => __('Search'), 'status' => __('Status'), 'location' => __('Location'), 'type' => __('Type'), 'classification' => __('Property type'),
        'bedrooms' => __('Bedrooms'), 'bathrooms' => __('Baths'), 'min_area' => __('Min. area'), 'max_area' => __('Max. area'),
        'min_price' => __('Min. price'), 'max_price' => __('Max. price'), 'unit_status' => __('Availability'),
    ];
    $pill = 'rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] transition';
@endphp

@section('content')

<section class="section-navy overflow-hidden">
    <div class="blueprint-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <x-blueprint variant="plots" class="absolute -right-16 -top-10 hidden h-80 w-[30rem] text-brass/20 md:block" />
    <div class="container-site relative py-14 lg:py-20">
        <nav class="label text-paper/40" aria-label="{{ __('Breadcrumb') }}"><a href="{{ route('home') }}" class="hover:text-brass">{{ __('Home') }}</a> / {{ __('Projects') }}</nav>
        <h1 class="display-lg mt-4 max-w-3xl text-paper">{{ $view === 'units' ? __('Every plot, villa and flat') : __('Projects & townships') }}</h1>
        <p class="mt-4 max-w-2xl text-paper/70">{{ __('Filter like a catalogue. Save what you like, compare up to three units side by side, then hold one online with a token.') }}</p>
    </div>
</section>

<section class="section-paper py-10 lg:py-14">
    <div class="container-site grid gap-10 lg:grid-cols-[18rem_minmax(0,1fr)]">

        {{-- FILTERS --}}
        <aside aria-label="{{ __('Filters') }}">
            <details class="group rounded-2xl border border-navy/10 bg-paper-50 lg:sticky lg:top-28" open>
                <summary class="flex cursor-pointer list-none items-center justify-between px-5 py-4 lg:cursor-default">
                    <span class="font-serif text-xl text-navy">{{ __('Filters') }}</span>
                    <span class="label text-ink/40 lg:hidden">{{ __('Show / hide') }}</span>
                </summary>
                <form action="{{ route('projects.index') }}" method="GET" class="space-y-5 border-t border-navy/10 p-5">
                    <input type="hidden" name="view" value="{{ $view }}">
                    <input type="hidden" name="layout" value="{{ $layout }}">
                    <input type="hidden" name="sort" value="{{ $sort }}">

                    <div>
                        <label for="f_q" class="field-label">{{ __('Search') }}</label>
                        <input id="f_q" name="q" type="search" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('Name, location, unit code') }}" class="field">
                    </div>
                    <div>
                        <label for="f_status" class="field-label">{{ __('Status') }}</label>
                        <select id="f_status" name="status" class="field">
                            <option value="">{{ __('Any') }}</option>
                            <option value="ongoing" @selected(($filters['status'] ?? '') === 'ongoing')>{{ __('Ongoing') }}</option>
                            <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>{{ __('Completed') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="f_location" class="field-label">{{ __('Location') }}</label>
                        <select id="f_location" name="location" class="field">
                            <option value="">{{ __('All Jaipur') }}</option>
                            @foreach($options['locations'] as $loc)
                                <option value="{{ $loc }}" @selected(($filters['location'] ?? '') === $loc)>{{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="f_class" class="field-label">{{ __('Property type') }}</label>
                        <select id="f_class" name="classification" class="field">
                            <option value="">{{ __('Any') }}</option>
                            @foreach(collect(['Residential', 'Commercial', 'Single-Family'])->merge($options['classifications'])->unique() as $c)
                                <option value="{{ $c }}" @selected(($filters['classification'] ?? '') === $c)>{{ $classLabel($c) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <fieldset>
                        <legend class="field-label">{{ __('Unit type') }}</legend>
                        <div class="grid grid-cols-3 gap-1.5 text-xs">
                            @foreach(['plot' => __('Plot'), 'villa' => __('Villa'), 'apartment' => __('Flat')] as $value => $label)
                                <label class="cursor-pointer rounded-xl border border-navy/15 px-2 py-2.5 text-center text-ink/70 has-[:checked]:border-navy has-[:checked]:bg-navy has-[:checked]:text-paper">
                                    <input type="radio" name="type" value="{{ $value }}" class="sr-only" @checked(($filters['type'] ?? '') === $value)>{{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="f_beds" class="field-label">{{ __('Bedrooms') }}</label>
                            <select id="f_beds" name="bedrooms" class="field">
                                <option value="">{{ __('Any') }}</option>
                                @foreach([2, 3, 4] as $n)<option value="{{ $n }}" @selected((int) ($filters['bedrooms'] ?? 0) === $n)>{{ $n }} {{ __('BHK') }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label for="f_baths" class="field-label">{{ __('Baths') }}</label>
                            <select id="f_baths" name="bathrooms" class="field">
                                <option value="">{{ __('Any') }}</option>
                                @foreach([2, 3, 4] as $n)<option value="{{ $n }}" @selected((int) ($filters['bathrooms'] ?? 0) === $n)>{{ $n }}+</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <fieldset>
                        <legend class="field-label">{{ __('Area (sq ft)') }} <span class="normal-case tracking-normal text-ink/40">{{ \App\Support\Format::inr($options['area']['min']) }}–{{ \App\Support\Format::inr($options['area']['max']) }}</span></legend>
                        <div class="grid grid-cols-2 gap-3">
                            <input name="min_area" type="number" min="0" step="50" inputmode="numeric" value="{{ $filters['min_area'] ?? '' }}" placeholder="{{ __('Min') }}" aria-label="{{ __('Min. area') }}" class="field">
                            <input name="max_area" type="number" min="0" step="50" inputmode="numeric" value="{{ $filters['max_area'] ?? '' }}" placeholder="{{ __('Max') }}" aria-label="{{ __('Max. area') }}" class="field">
                        </div>
                    </fieldset>
                    <div>
                        <label for="f_budget" class="field-label">{{ __('Budget') }}</label>
                        <select id="f_budget" name="max_price" class="field">
                            <option value="">{{ __('Any') }}</option>
                            @foreach([4000000 => '₹40 L', 6000000 => '₹60 L', 10000000 => '₹1 Cr', 15000000 => '₹1.5 Cr'] as $value => $label)
                                <option value="{{ $value }}" @selected((int) ($filters['max_price'] ?? 0) === $value)>{{ __('Up to') }} {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="f_avail" class="field-label">{{ __('Availability') }}</label>
                        <select id="f_avail" name="unit_status" class="field">
                            <option value="">{{ __('Any') }}</option>
                            <option value="available" @selected(($filters['unit_status'] ?? '') === 'available')>{{ __('Available') }}</option>
                            <option value="on_hold" @selected(($filters['unit_status'] ?? '') === 'on_hold')>{{ __('On Hold') }}</option>
                            <option value="sold" @selected(($filters['unit_status'] ?? '') === 'sold')>{{ __('Sold') }}</option>
                        </select>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="btn-navy flex-1">{{ __('Apply filters') }}</button>
                        <a href="{{ route('projects.index', ['view' => $view]) }}" class="btn-ghost">{{ __('Reset') }}</a>
                    </div>
                </form>
            </details>
        </aside>

        {{-- RESULTS --}}
        <div>
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-navy/10 pb-5">
                <div class="inline-flex rounded-full border border-navy/15 p-1" role="group" aria-label="{{ __('Show') }}">
                    <a href="{{ $with(['view' => 'projects']) }}" class="{{ $pill }} {{ $view === 'projects' ? 'bg-navy text-paper' : 'text-ink/60 hover:text-navy' }}" @if($view === 'projects') aria-current="true" @endif>{{ __('Projects') }}</a>
                    <a href="{{ $with(['view' => 'units']) }}" class="{{ $pill }} {{ $view === 'units' ? 'bg-navy text-paper' : 'text-ink/60 hover:text-navy' }}" @if($view === 'units') aria-current="true" @endif>{{ __('Units') }}</a>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="label text-ink/50" aria-live="polite">{{ $resultCount }} {{ $view === 'units' ? __('units') : __('projects') }}</span>
                    <form action="{{ route('projects.index') }}" method="GET" class="flex items-center gap-2">
                        @foreach(request()->except(['sort', 'page']) as $k => $v)
                            @if(is_scalar($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                        @endforeach
                        <label for="sortSelect" class="sr-only">{{ __('Sort') }}</label>
                        <select id="sortSelect" name="sort" onchange="this.form.submit()" class="rounded-full border border-navy/15 bg-paper-50 px-4 py-2 text-xs font-medium text-navy focus:border-brass focus:outline-none">
                            @foreach(['featured' => __('Featured'), 'price-low' => __('Price: low to high'), 'price-high' => __('Price: high to low'), 'newest' => __('Newest'), 'oldest' => __('Oldest')] as $value => $label)
                                <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <noscript><button class="btn-ghost btn-sm">{{ __('Sort') }}</button></noscript>
                    </form>
                    <div class="inline-flex rounded-full border border-navy/15 p-1" role="group" aria-label="{{ __('Layout') }}">
                        <a href="{{ $with(['layout' => 'grid']) }}" class="grid h-8 w-8 place-items-center rounded-full {{ $layout === 'grid' ? 'bg-navy text-paper' : 'text-ink/50 hover:text-navy' }}" aria-label="{{ __('Grid view') }}" @if($layout === 'grid') aria-current="true" @endif>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg>
                        </a>
                        <a href="{{ $with(['layout' => 'list']) }}" class="grid h-8 w-8 place-items-center rounded-full {{ $layout === 'list' ? 'bg-navy text-paper' : 'text-ink/50 hover:text-navy' }}" aria-label="{{ __('List view') }}" @if($layout === 'list') aria-current="true" @endif>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            @if($activeFilters->isNotEmpty())
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    @foreach($activeFilters as $key => $value)
                        <a href="{{ $with([$key => null]) }}" class="chip hover:border-terracotta hover:text-terracotta">
                            {{ $filterLabels[$key] ?? $key }}: {{ in_array($key, ['min_price', 'max_price']) ? \App\Support\Format::lakhCrore($value) : (in_array($key, ['min_area', 'max_area']) ? \App\Support\Format::inr($value) : str_replace('_', ' ', $value)) }}
                            <span aria-hidden="true">×</span><span class="sr-only">{{ __('Remove filter') }}</span>
                        </a>
                    @endforeach
                    <a href="{{ route('projects.index', ['view' => $view]) }}" class="label text-ink/50 underline hover:text-terracotta">{{ __('Clear all') }}</a>
                </div>
            @endif

            @if($resultCount === 0)
                <div class="mt-10 rounded-[2rem] border border-dashed border-navy/20 p-12 text-center">
                    <x-blueprint class="mx-auto h-32 w-48 text-brass/60" />
                    <h2 class="mt-6 font-serif text-3xl text-navy">{{ __('Nothing matches those filters') }}</h2>
                    <p class="mt-2 text-ink/60">{{ __('Try widening the budget or area, or clear the filters to see everything.') }}</p>
                    <div class="mt-6 flex justify-center gap-3">
                        <a href="{{ route('projects.index', ['view' => $view]) }}" class="btn-navy">{{ __('Clear filters') }}</a>
                        <button type="button" data-open="assistantPanel" class="btn-ghost">{{ __('Ask our assistant') }}</button>
                    </div>
                </div>
            @elseif($view === 'units')
                <div class="mt-8 grid gap-6 {{ $layout === 'list' ? '' : 'sm:grid-cols-2 xl:grid-cols-3' }}">
                    @foreach($units as $unit)
                        <x-unit-card :unit="$unit" :layout="$layout" />
                    @endforeach
                </div>
                <div class="mt-10">{{ $units->links('components.pagination') }}</div>
            @elseif($layout === 'list')
                <div class="mt-8 divide-y divide-navy/10 border-y border-navy/10">
                    @foreach($projects as $project)
                        <article class="group grid items-center gap-5 py-5 sm:grid-cols-[9rem_minmax(0,1fr)_auto]" data-reveal>
                            <a href="{{ route('projects.show', $project->slug) }}" class="arch-soft block aspect-[4/5] w-28 overflow-hidden bg-navy sm:w-36" tabindex="-1" aria-hidden="true">
                                <img src="{{ $project->card_image }}" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            </a>
                            <div class="min-w-0">
                                <div class="label text-brass-700">{{ $project->status === 'completed' ? __('Completed') : __('Ongoing') }} · {{ $classLabel($project->classification) }}</div>
                                <h2 class="mt-1 font-serif text-3xl text-navy"><a href="{{ route('projects.show', $project->slug) }}" class="hover:text-terracotta">{{ $project->name }}</a></h2>
                                <p class="mt-1 text-sm text-ink/60">{{ $project->location }} · {{ $project->type }}</p>
                                <p class="label mt-2 text-ink/40">RERA {{ $project->rera_number }}</p>
                            </div>
                            <div class="flex items-center gap-4 sm:flex-col sm:items-end">
                                <div class="font-serif text-xl text-navy">{{ $project->price_range }}</div>
                                @if($project->status !== 'completed')<div class="label text-status-available">{{ $project->available_units_count }} {{ __('units available') }}</div>@endif
                                <a href="{{ route('projects.show', $project->slug) }}" class="btn-navy btn-sm">{{ __('View project') }}</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($projects as $i => $project)
                        <x-project-plate :project="$project" :index="$i" />
                    @endforeach
                </div>
            @endif

            {{-- Recently viewed (filled from localStorage by site.js) --}}
            <section class="mt-16 border-t border-navy/10 pt-10" data-recent-section hidden aria-label="{{ __('Recently viewed') }}">
                <h2 class="eyebrow">{{ __('Recently viewed') }}</h2>
                <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4" data-recent-list></div>
            </section>
        </div>
    </div>
</section>

@endsection
