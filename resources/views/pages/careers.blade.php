@extends('layouts.app')

@section('title', 'Careers | Kedia Builders & Colonizers, Jaipur')
@section('meta_description', 'Join Kedia Builders & Colonizers in Jaipur: openings for site engineers, architects, sales advisors and customer relations.')

@php $jobs = config('kedia.careers'); @endphp

@section('content')
<x-page-hero :eyebrow="__('Careers')" :title="__('Build Jaipur with us')" :crumb="__('Careers')">
    {{ __('Engineers, architects, advisors and customer teams who care about doing it properly.') }}
</x-page-hero>

<section class="section-paper py-20">
    <div class="container-site grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-7">
            <h2 class="eyebrow">{{ __('Current openings') }} ({{ count($jobs) }})</h2>
            <div class="mt-6 divide-y divide-navy/10 border-y border-navy/10">
                @foreach($jobs as $job)
                    <article class="grid gap-3 py-6 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center" data-reveal>
                        <div>
                            <div class="label text-brass-700">{{ __($job['team']) }}</div>
                            <h3 class="mt-1 font-serif text-2xl text-navy">{{ __($job['title']) }}</h3>
                            <p class="mt-1 text-sm text-ink/60">{{ __($job['type']) }} · {{ __($job['location']) }} · {{ $job['experience'] }} {{ __('experience') }}</p>
                        </div>
                        <a href="#apply" data-role="{{ $job['title'] }}" class="btn-ghost btn-sm justify-self-start" onclick="document.getElementById('apply_role').value=this.dataset.role">{{ __('Apply') }} →</a>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-5">
            <form id="apply" action="{{ route('leads.store') }}" method="POST" class="scroll-mt-28 space-y-4 rounded-[1.5rem] bg-navy p-6 text-paper sm:p-8 lg:sticky lg:top-28">
                @csrf
                <input type="hidden" name="source" value="career_application">
                <h2 class="font-serif text-3xl">{{ __('Apply') }}</h2>
                <div>
                    <label for="apply_role" class="label mb-1.5 block text-paper/60">{{ __('Role') }}</label>
                    <select id="apply_role" name="project_name" class="field-dark [&>option]:text-ink">
                        @foreach($jobs as $job)<option value="{{ $job['title'] }}">{{ __($job['title']) }}</option>@endforeach
                        <option value="General application">{{ __('General application') }}</option>
                    </select>
                </div>
                <div><label for="apply_name" class="label mb-1.5 block text-paper/60">{{ __('Full name') }} *</label><input id="apply_name" name="name" required autocomplete="name" class="field-dark"></div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label for="apply_phone" class="label mb-1.5 block text-paper/60">{{ __('Mobile') }} *</label><input id="apply_phone" name="phone" type="tel" required autocomplete="tel" class="field-dark"></div>
                    <div><label for="apply_email" class="label mb-1.5 block text-paper/60">{{ __('Email') }}</label><input id="apply_email" name="email" type="email" autocomplete="email" class="field-dark"></div>
                </div>
                <div><label for="apply_msg" class="label mb-1.5 block text-paper/60">{{ __('Portfolio / LinkedIn link and a short note') }}</label><textarea id="apply_msg" name="message" rows="4" maxlength="500" class="field-dark"></textarea></div>
                <button type="submit" class="btn-brass w-full">{{ __('Send application') }}</button>
                <p class="text-xs text-paper/50">{{ __('You can also email your CV to') }} <a href="mailto:{{ config('kedia.company.email') }}" class="underline">{{ config('kedia.company.email') }}</a>.</p>
            </form>
        </div>
    </div>
</section>
@endsection
