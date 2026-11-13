@extends('layouts.app')

@section('content')
<x-page-hero :eyebrow="__('Legal')" :title="$heading" :crumb="$heading" />

<section class="section-paper py-16">
    <div class="container-site grid gap-12 lg:grid-cols-12">
        <nav class="min-w-0 lg:col-span-3" aria-label="{{ __('Legal pages') }}">
            <ul class="space-y-1 lg:sticky lg:top-28">
                @foreach(['legal' => __('RERA disclosures'), 'legal.privacy' => __('Privacy Policy'), 'legal.terms' => __('Terms of Use'), 'legal.refund' => __('Refund & Booking Policy')] as $r => $label)
                    <li><a href="{{ route($r) }}" class="block rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs($r) ? 'bg-navy text-paper' : 'text-ink/70 hover:bg-paper-200' }}" @if(request()->routeIs($r)) aria-current="page" @endif>{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>
        <article class="min-w-0 max-w-3xl space-y-6 leading-relaxed text-ink/80 lg:col-span-8 [&_h2]:mt-10 [&_h2]:font-serif [&_h2]:text-2xl [&_h2]:text-navy [&_li]:ml-5 [&_li]:list-disc [&_ul]:space-y-2">
            @hasSection('draft_notice')
                <p class="flex items-start gap-3 rounded-2xl border border-dashed border-terracotta/50 bg-terracotta/5 p-4 text-sm"><x-sample-tag class="mt-0.5 flex-none" />@yield('draft_notice')</p>
            @endif
            @yield('legal')
            <p class="border-t border-navy/10 pt-6 text-sm text-ink/50">{{ __('Questions:') }} <a href="mailto:{{ config('kedia.company.email') }}" class="underline">{{ config('kedia.company.email') }}</a> · {{ config('kedia.company.phone') }}</p>
        </article>
    </div>
</section>
@endsection
