@extends('layouts.app')

@php
    $project = $unit->project;
    $gallery = $project ? array_slice($project->galleryImages(), 0, 6) : [];
    $cover = $project?->hero_image ?? '/media/img/2021/04/Kedia-Nikunj-1-1920.webp';
    $facing = stripos((string) $unit->facing, 'facing') !== false ? $unit->facing : $unit->facing . ' facing';
    $isPlot = $unit->unit_type === 'Plot';
    $specs = array_filter([
        __('Property type') => $unit->unit_type,
        __('Unit code') => $unit->unit_code,
        __('Block / phase') => $unit->tower_or_block,
        __('Super built-up area') => \App\Support\Format::inr($unit->area_sqft) . ' ' . __('sq ft'),
        __('Plot size') => (float) $unit->area_sqyd > 0 ? rtrim(rtrim(number_format((float) $unit->area_sqyd, 2), '0'), '.') . ' ' . __('sq yd') : null,
        __('Bedrooms / baths') => $isPlot ? null : $unit->bedrooms . ' / ' . $unit->bathrooms,
        __('Floor') => $unit->floor_level,
        __('Facing') => $unit->facing,
        __('Possession') => $unit->possession,
        'RERA' => $project?->rera_number,
    ]);
@endphp

@section('title', $unit->title . ' (' . $unit->unit_code . ') at ' . ($project->title ?? 'Kedia Builders') . ' | Kedia Builders & Colonizers')
@section('meta_description', $unit->title . ' in ' . ($project->title ?? 'a Kedia township') . ', ' . ($project->location ?? 'Jaipur') . '. ' . number_format($unit->area_sqft) . ' sq ft, ' . $facing . ', all-inclusive ' . $unit->price_formatted . '. Check live availability and book online.')
@section('og_image', $cover)

@push('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $unit->title . ' — ' . $unit->unit_code,
    'description' => $unit->title . ' at ' . ($project->title ?? 'Kedia Builders') . ', ' . ($project->location ?? 'Jaipur'),
    'sku' => $unit->project_slug . '/' . $unit->unit_code,
    'image' => url($cover),
    'brand' => ['@type' => 'Organization', 'name' => 'Kedia Builders & Colonizers'],
    'category' => $unit->unit_type,
    'offers' => [
        '@type' => 'Offer',
        'url' => url()->current(),
        'priceCurrency' => 'INR',
        'price' => $pricing['grand_total'],
        'availability' => 'https://schema.org/' . ($unit->status === 'available' ? 'InStock' : ($unit->status === 'on_hold' ? 'LimitedAvailability' : 'SoldOut')),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endpush

@section('content')
<div data-recent="{{ json_encode($unit->shopItem()) }}"></div>

<section class="section-paper pt-8">
    <div class="container-site">
        <nav class="label text-ink/40" aria-label="{{ __('Breadcrumb') }}">
            <a href="{{ route('home') }}" class="hover:text-terracotta">{{ __('Home') }}</a> /
            <a href="{{ route('projects.index') }}" class="hover:text-terracotta">{{ __('Projects') }}</a> /
            @if($project)<a href="{{ route('projects.show', $project->slug) }}" class="hover:text-terracotta">{{ $project->name }}</a> /@endif
            {{ $unit->unit_code }}
        </nav>
    </div>
</section>

<section class="section-paper pb-20 pt-8">
    <div class="container-site grid gap-10 lg:grid-cols-12">

        {{-- LEFT: gallery, plan, specs --}}
        <div class="space-y-8 lg:col-span-7">
            <div class="grid gap-3 sm:grid-cols-4">
                <a href="{{ $cover }}" data-lightbox data-src="{{ $cover }}" data-caption="{{ $project->name ?? '' }}" class="arch-soft block overflow-hidden bg-navy sm:col-span-4" data-reveal>
                    <img fetchpriority="high" src="{{ $cover }}" alt="{{ $unit->title }} — {{ $project->name ?? '' }}" class="aspect-[16/10] w-full object-cover">
                </a>
                @foreach(array_slice($gallery, 0, 4) as $img)
                    <a href="{{ $img['src'] }}" data-lightbox data-src="{{ $img['src'] }}" data-caption="{{ $img['caption'] }}" class="block overflow-hidden rounded-xl bg-navy">
                        <img src="{{ $img['thumb'] }}" alt="{{ $img['caption'] }}" loading="lazy" decoding="async" class="aspect-square w-full object-cover transition duration-500 hover:scale-105">
                    </a>
                @endforeach
            </div>
            <p class="text-xs text-ink/50">{{ __('Photos show the project. Ask for photos of this specific unit when you book a visit.') }}</p>

            <x-floor-plan :unit="$unit" />

            <div class="rounded-[1.5rem] border border-navy/10 bg-paper-50 p-6">
                <h2 class="font-serif text-2xl text-navy">{{ __('Specifications') }}</h2>
                <dl class="mt-4 grid gap-x-8 sm:grid-cols-2">
                    @foreach($specs as $label => $value)
                        <div class="flex items-center justify-between gap-4 border-b border-navy/10 py-3 text-sm">
                            <dt class="label text-ink/50">{{ $label }}</dt>
                            <dd class="text-right text-navy">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
                @if(!empty($unit->specifications))
                    <ul class="mt-5 grid gap-2 text-sm text-ink/70 sm:grid-cols-2">
                        @foreach($unit->specifications as $label => $value)
                            <li class="flex gap-3"><span class="mt-2 h-1 w-1 flex-none rounded-full bg-brass" aria-hidden="true"></span><span><span class="text-navy">{{ $label }}:</span> {{ $value }}</span></li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- RIGHT: buy box --}}
        <aside class="lg:col-span-5">
            <div class="space-y-6 lg:sticky lg:top-28">
                <div data-reveal>
                    <div class="flex flex-wrap items-center gap-2">
                        <x-status-pill :status="$unit->status" />
                        <span class="chip">{{ $unit->unit_type }}</span>
                        <span class="chip">{{ $unit->unit_code }}</span>
                    </div>
                    <h1 class="display-md mt-4 text-navy">{{ $unit->title }}</h1>
                    <p class="mt-2 text-ink/60">
                        @if($project)<a href="{{ route('projects.show', $project->slug) }}" class="underline decoration-brass underline-offset-4 hover:text-terracotta">{{ $project->name }}</a> · {{ $project->location }}@endif
                    </p>
                    <div class="mt-4 flex flex-wrap gap-x-6 gap-y-1 font-mono text-xs uppercase tracking-wider text-ink/60">
                        <span>{{ \App\Support\Format::inr($unit->area_sqft) }} {{ __('sq ft') }}</span>
                        @unless($isPlot)<span>{{ $unit->bedrooms }} {{ __('BHK') }} · {{ $unit->bathrooms }} {{ __('baths') }}</span>@endunless
                        <span>{{ $facing }}</span>
                    </div>
                </div>

                <div class="rounded-[1.5rem] bg-navy p-6 text-paper sm:p-8" data-reveal>
                    <div class="flex items-center justify-between">
                        <h2 class="eyebrow">{{ __('Price breakup') }}</h2>
                        <a href="{{ route('units.cost-sheet', $unit->id) }}" target="_blank" rel="noopener" class="label text-paper/60 underline hover:text-brass">{{ __('Cost sheet PDF') }}</a>
                    </div>
                    <x-price-breakup :pricing="$pricing" dark class="mt-4" />

                    <div class="mt-6 flex items-center justify-between rounded-2xl border border-brass/30 bg-white/[0.03] px-5 py-4">
                        <div>
                            <div class="label text-brass">{{ __('Online token (5%)') }}</div>
                            <div class="font-serif text-2xl">₹ {{ \App\Support\Format::inr($pricing['token_amount']) }}</div>
                        </div>
                        <span class="label text-right text-paper/50">{{ __('Holds the unit') }}<br>{{ config('kedia.hold_hours') }} {{ __('hours') }}</span>
                    </div>

                    <div class="mt-6 grid gap-2">
                        @if($unit->status === 'available')
                            <a href="{{ route('booking.checkout', $unit->id) }}" class="btn-brass">{{ __('Book now') }}</a>
                        @elseif($unit->status === 'on_hold')
                            <button type="button" disabled class="btn bg-brass/20 text-brass">{{ __('On hold by another buyer') }}</button>
                        @else
                            <button type="button" disabled class="btn bg-white/5 text-paper/50">{{ __('Sold') }}</button>
                        @endif
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" data-wishlist="{{ json_encode($unit->shopItem()) }}" aria-pressed="false" class="btn-ghost-light [&.is-on]:border-terracotta [&.is-on]:text-terracotta">
                                <svg class="h-4 w-4 [.is-on_&]:fill-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M12 20s-7-4.4-7-10a4 4 0 017-2.6A4 4 0 0119 10c0 5.6-7 10-7 10z"/></svg>
                                {{ __('Add to shortlist') }}
                            </button>
                            <button type="button" data-compare="{{ json_encode(['id' => 'unit-' . $unit->id, 'title' => $unit->unit_code . ' · ' . $unit->project_name]) }}" aria-pressed="false" class="btn-ghost-light [&.is-on]:border-brass [&.is-on]:text-brass">{{ __('Compare') }}</button>
                        </div>
                        <button type="button" data-open="visitModal" class="btn-ghost-light">{{ __('Schedule a visit') }}</button>
                    </div>
                </div>

                <ul class="space-y-2 text-sm text-ink/70">
                    @foreach([__('Token is refundable as per the booking policy'), __('A relationship manager calls you after you book'), __('Track payments and documents from your account')] as $line)
                        <li class="flex gap-3"><span class="text-brass" aria-hidden="true">✓</span>{{ $line }}</li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
</section>

@if($similarUnits->isNotEmpty())
<section class="section-paper border-t border-navy/10 py-20">
    <div class="container-site">
        <x-section-head :eyebrow="__('Similar units')" :title="__('Close in price, same project')" />
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($similarUnits as $similar)
                <x-unit-card :unit="$similar" />
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section-paper pb-20" data-recent-section hidden aria-label="{{ __('Recently viewed') }}">
    <div class="container-site border-t border-navy/10 pt-10">
        <h2 class="eyebrow">{{ __('Recently viewed') }}</h2>
        <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4" data-recent-list></div>
    </div>
</section>

@endsection
