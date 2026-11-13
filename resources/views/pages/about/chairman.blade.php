@extends('layouts.app')

@section('title', 'Chairman & Leadership | Kedia Builders & Colonizers')
@section('meta_description', 'Founder & Chairman Mr Rajendra Kedia, Director Mr Saurabh Kedia and CEO Mr Gaurav Kedia lead Kedia Builders & Colonizers, Jaipur.')

@php [$chairman, $director, $ceo] = config('kedia.leaders'); @endphp

@section('content')
<x-page-hero :eyebrow="__('Leadership')" :title="__('The people behind every plot')" :crumb="__('Chairman & leadership')">
    {{ __('Three leaders, one standard: build what you would want to live in.') }}
</x-page-hero>

{{-- Chairman feature --}}
<section class="section-paper py-24">
    <div class="container-site grid items-center gap-14 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <div class="arch aspect-[4/5] border border-brass/40 bg-navy" data-reveal>
                <img src="{{ $chairman['photo'] }}" alt="{{ $chairman['name'] }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
            </div>
        </div>
        <div class="lg:col-span-6 lg:col-start-7" data-reveal>
            <div class="eyebrow">{{ __($chairman['role']) }}</div>
            <h2 class="display-lg mt-4 text-navy">{{ __($chairman['name']) }}</h2>
            <p class="label mt-3 text-ink/50">{{ __($chairman['meta']) }}</p>
            <p class="mt-8 font-serif text-2xl leading-snug text-navy">“{{ __('Laid the foundation in 1980 — and every project since has been built on the same promise: a clear title and a home delivered as promised.') }}”</p>
            <p class="mt-6 text-lg leading-relaxed text-ink/70">{{ __($chairman['bio']) }}</p>
        </div>
    </div>
</section>

<div class="jaali bg-paper" aria-hidden="true"></div>

<section class="section-navy py-24">
    <div class="container-site">
        <x-section-head :eyebrow="__('Next generation')" :title="__('Director & CEO')" dark />
        <div class="mt-14 grid gap-14 md:grid-cols-2">
            @foreach([$director, $ceo] as $i => $leader)
                <article class="grid gap-8 sm:grid-cols-[12rem_minmax(0,1fr)]" data-reveal data-reveal-delay="{{ $i * 120 }}">
                    <div class="arch aspect-[4/5] w-48 border border-brass/30 bg-navy-700">
                        <img src="{{ $leader['photo'] }}" alt="{{ $leader['name'] }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
                    </div>
                    <div>
                        <div class="label text-brass">{{ __($leader['role']) }}</div>
                        <h3 class="mt-2 font-serif text-3xl text-paper">{{ __($leader['name']) }}</h3>
                        <p class="label mt-2 text-paper/40">{{ __($leader['meta']) }}</p>
                        <p class="mt-4 leading-relaxed text-paper/70">{{ __($leader['bio']) }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section-paper py-20 text-center">
    <div class="container-site">
        <h2 class="display-md mx-auto max-w-2xl text-navy" data-reveal>{{ __('Meet the team on site') }}</h2>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <button type="button" data-open="visitModal" class="btn-navy">{{ __('Book a Visit') }}</button>
            <a href="{{ route('about.vision') }}" class="btn-ghost">{{ __('Vision & mission') }}</a>
        </div>
    </div>
</section>
@endsection
