@extends('layouts.app')

@section('title', 'Our Story · Since 1980 | Kedia Builders & Colonizers')
@section('meta_description', 'Kedia Builders & Colonizers: a pioneering name in Jaipur real estate since 1980, with 200+ land development projects in 41 years.')

@php $about = config('kedia.about'); @endphp

@section('content')
<x-page-hero :eyebrow="__('Our story') . ' · ' . __('Est.') . ' 1980'" :title="__('Building Jaipur, one address at a time')" image="/media/img/2021/04/Kedia-Nikunj-1-1920.webp" :crumb="__('About')">
    {{ __($about['intro']) }}
</x-page-hero>

<section class="section-paper py-24">
    <div class="container-site grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <p class="font-serif text-3xl leading-snug text-navy sm:text-4xl" data-reveal>“{{ __(config('kedia.company.tagline')) }}”</p>
            <div class="jaali mt-8 max-w-xs" aria-hidden="true"></div>
        </div>
        <div class="space-y-6 text-lg leading-relaxed text-ink/75 lg:col-span-6 lg:col-start-7" data-reveal>
            <p>{{ __($about['intro']) }}</p>
            <p>{{ __($about['portfolio']) }}</p>
            <div class="flex flex-wrap gap-3 pt-2">
                <a href="{{ route('about.chairman') }}" class="btn-navy">{{ __('Chairman & leadership') }}</a>
                <a href="{{ route('about.vision') }}" class="btn-ghost">{{ __('Vision & mission') }}</a>
            </div>
        </div>
    </div>
</section>

<section class="section-paper pb-20">
    <div class="container-site grid gap-10 border-y border-navy/10 py-14 sm:grid-cols-2 lg:grid-cols-4">
        @foreach(config('kedia.stats') as $i => $stat)
            <div data-reveal data-reveal-delay="{{ $i * 80 }}">
                <div class="font-serif text-7xl font-light leading-none text-navy"><span data-count="{{ $stat['value'] }}" data-suffix="{{ $stat['suffix'] }}" data-plain="{{ !empty($stat['plain']) ? 'true' : 'false' }}">{{ $stat['value'] }}{{ $stat['suffix'] }}</span></div>
                <div class="label mt-3 text-ink/60">{{ __($stat['label']) }}</div>
            </div>
        @endforeach
    </div>
</section>

{{-- Heritage timeline (horizontal) --}}
<section class="section-navy overflow-hidden py-24">
    <div class="jaali-panel pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="container-site relative">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <x-section-head :eyebrow="__('Heritage timeline')" :title="__('Four decades, drawn to scale')" dark />
            <div class="flex gap-2">
                <button type="button" class="btn-ghost-light btn-sm" onclick="document.getElementById('timelineTrack').scrollBy({left: -380, behavior: 'smooth'})" aria-label="{{ __('Previous') }}">←</button>
                <button type="button" class="btn-ghost-light btn-sm" onclick="document.getElementById('timelineTrack').scrollBy({left: 380, behavior: 'smooth'})" aria-label="{{ __('Next') }}">→</button>
            </div>
        </div>
        <ol id="timelineTrack" class="timeline-track -mx-4 mt-14 flex gap-0 overflow-x-auto px-4 pb-6 sm:-mx-8 sm:px-8" tabindex="0" aria-label="{{ __('Heritage timeline') }}">
            @foreach(config('kedia.timeline') as $i => $item)
                <li class="relative w-[78vw] flex-none border-t border-brass/40 pr-10 pt-8 sm:w-[22rem]" data-reveal data-reveal-delay="{{ $i * 90 }}">
                    <span class="absolute -top-[7px] left-0 h-3 w-3 rotate-45 border border-brass bg-navy" aria-hidden="true"></span>
                    <div class="font-serif text-6xl font-light text-brass">{{ $item['year'] }}</div>
                    <h3 class="mt-4 font-serif text-2xl text-paper">{{ __($item['title']) }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-paper/60">{{ __($item['text']) }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

<section class="section-paper py-24">
    <div class="container-site">
        <x-section-head :eyebrow="__('Portfolio')" :title="__('Property development & management')">
            {{ __('From plotted colonies to gated villa communities and apartments — a selection of the townships we have built.') }}
        </x-section-head>
        <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach($projects->take(8) as $project)
                <a href="{{ route('projects.show', $project->slug) }}" class="group relative block overflow-hidden rounded-2xl bg-navy" data-reveal>
                    <img src="{{ \App\Models\Project::media($project->cover_image, 600) }}" alt="" loading="lazy" decoding="async" class="aspect-[4/5] w-full object-cover opacity-80 transition duration-700 group-hover:scale-105 group-hover:opacity-60">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-navy p-4 pt-12">
                        <div class="font-serif text-lg text-paper">{{ $project->name }}</div>
                        <div class="label text-paper/50">{{ $project->location }}</div>
                    </div>
                </a>
            @endforeach
        </div>
        <a href="{{ route('projects.index') }}" class="btn-navy mt-10">{{ __('See all projects') }}</a>
    </div>
</section>
@endsection
