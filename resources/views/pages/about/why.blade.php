@extends('layouts.app')

@section('title', 'Why Invest with Kedia | Kedia Builders & Colonizers')
@section('meta_description', 'Exclusive, connected, comfortable, peaceful, JDA approved and in harmony with nature: six reasons to invest with Kedia Builders & Colonizers.')

@section('content')
<x-page-hero :eyebrow="__('Why invest')" :title="__('Six reasons, one promise')" :crumb="__('Why invest')" image="/media/img/2025/04/LANDMARK-003-1920.webp">
    {{ __('What four decades of Jaipur families have relied on when they chose a Kedia address.') }}
</x-page-hero>

<section class="section-paper py-24">
    <div class="container-site divide-y divide-navy/10 border-y border-navy/10">
        @foreach(config('kedia.why_invest') as $i => $why)
            <article class="group grid items-center gap-6 py-10 md:grid-cols-12" data-reveal>
                <div class="font-serif text-6xl font-light text-brass md:col-span-2">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                <h2 class="font-serif text-4xl text-navy transition group-hover:text-terracotta md:col-span-4">{{ __($why['title']) }}</h2>
                <p class="text-lg text-ink/70 md:col-span-5">{{ __($why['text']) }}</p>
                <svg class="hidden h-10 w-10 justify-self-end text-brass md:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $why['icon'] }}"/></svg>
            </article>
        @endforeach
    </div>
</section>

<section class="section-navy py-20 text-center">
    <div class="container-site">
        <h2 class="display-md mx-auto max-w-2xl text-paper" data-reveal>{{ __('See the difference on site') }}</h2>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ route('projects.index') }}" class="btn-brass">{{ __('Browse projects') }}</a>
            <button type="button" data-open="visitModal" class="btn-ghost-light">{{ __('Book a Visit') }}</button>
        </div>
    </div>
</section>
@endsection
