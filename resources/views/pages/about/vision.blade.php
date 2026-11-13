@extends('layouts.app')

@section('title', 'Vision & Mission | Kedia Builders & Colonizers')
@section('meta_description', 'Traditional wisdom infused with modern concepts: the vision and mission of Kedia Builders & Colonizers, Jaipur.')

@php $about = config('kedia.about'); @endphp

@section('content')
<x-page-hero :eyebrow="__('What guides us')" :title="__('Vision & mission')" :crumb="__('Vision & mission')" />

<section class="section-paper py-24">
    <div class="container-site space-y-24">
        @foreach([['vision', __('Vision'), $about['vision'], '/media/img/2025/04/LANDMARK-003-1920.webp'], ['mission', __('Mission'), $about['mission'], '/media/img/2025/04/DSC07902-1920.webp']] as $i => [$key, $label, $text, $img])
            <article class="grid items-center gap-12 lg:grid-cols-12">
                <div class="{{ $i % 2 ? 'lg:order-2 lg:col-span-5 lg:col-start-8' : 'lg:col-span-5' }}">
                    <div class="arch-soft aspect-[4/5] overflow-hidden bg-navy" data-reveal>
                        <img src="{{ $img }}" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover" data-parallax="0.06">
                    </div>
                </div>
                <div class="{{ $i % 2 ? 'lg:order-1 lg:col-span-6' : 'lg:col-span-6 lg:col-start-7' }}" data-reveal>
                    <div class="font-mono text-sm text-brass">0{{ $i + 1 }}</div>
                    <h2 class="display-lg mt-3 text-navy">{{ $label }}</h2>
                    <p class="mt-6 font-serif text-2xl leading-snug text-navy/90">{{ __($text) }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>

<section class="section-navy py-20">
    <div class="container-site grid gap-6 md:grid-cols-4">
        @foreach([__('World-class construction'), __('Integrity of commitment'), __('Prompt delivery'), __('Security of investment')] as $i => $value)
            <div class="border-t border-brass/40 pt-5" data-reveal data-reveal-delay="{{ $i * 80 }}">
                <div class="font-mono text-xs text-brass">0{{ $i + 1 }}</div>
                <div class="mt-2 font-serif text-2xl text-paper">{{ $value }}</div>
            </div>
        @endforeach
    </div>
</section>
@endsection
