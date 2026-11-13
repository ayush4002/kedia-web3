@extends('layouts.app')

@section('title', 'Contact Us | Kedia Builders & Colonizers Jaipur')
@section('meta_description', 'Call +91 8302 500 500, WhatsApp +91 98291 84872 or email Kedia Builders & Colonizers, Sikar Road & Bani Park, Jaipur. Schedule a site visit online.')

@php
    $c = config('kedia.company');
    $lat = $c['map']['lat']; $lng = $c['map']['lng'];
    $mapSrc = 'https://www.openstreetmap.org/export/embed.html?bbox=' . ($lng - 0.03) . '%2C' . ($lat - 0.018) . '%2C' . ($lng + 0.03) . '%2C' . ($lat + 0.018) . '&layer=mapnik&marker=' . $lat . '%2C' . $lng;
@endphp

@section('content')
<x-page-hero :eyebrow="__('Contact')" :title="__('Connect With Our Jaipur Headquarters')" :crumb="__('Contact')">
    {{ __('Call, message or drop in. Or pick a slot and we will drive you to the site.') }}
</x-page-hero>

<section class="section-paper py-20">
    <div class="container-site grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach([
            [__('Call'), $c['phone'], 'tel:' . $c['phone_href']],
            ['WhatsApp', $c['whatsapp'], 'https://wa.me/' . $c['whatsapp_href']],
            [__('Email'), $c['email'], 'mailto:' . $c['email']],
            [__('Visit'), __($c['address']), 'https://www.google.com/maps/search/?api=1&query=' . $lat . ',' . $lng],
        ] as $i => [$label, $value, $href])
            <a href="{{ $href }}" @if(str_starts_with($href, 'http')) target="_blank" rel="noopener" @endif class="card group min-w-0 transition hover:border-brass" data-reveal data-reveal-delay="{{ $i * 70 }}">
                <div class="label text-brass-700">{{ $label }}</div>
                <div class="mt-3 break-all font-serif text-xl text-navy transition group-hover:text-terracotta sm:break-words">{{ $value }}</div>
            </a>
        @endforeach
    </div>
    <p class="container-site label mt-4 text-ink/50">{{ __($c['hours']) }}</p>
</section>

<section class="section-paper pb-24">
    <div class="container-site grid gap-10 lg:grid-cols-12">
        {{-- Enquiry form + visit scheduler --}}
        <form action="{{ route('leads.store') }}" method="POST" class="space-y-5 rounded-[1.5rem] border border-navy/10 bg-paper-50 p-6 sm:p-8 lg:col-span-7">
            @csrf
            <input type="hidden" name="source" value="contact_page">
            <h2 class="font-serif text-3xl text-navy">{{ __('Send an enquiry') }}</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                <div><label for="ct_name" class="field-label">{{ __('Full name') }} *</label><input id="ct_name" name="name" required autocomplete="name" value="{{ old('name') }}" class="field"></div>
                <div><label for="ct_phone" class="field-label">{{ __('Mobile / WhatsApp') }} *</label><input id="ct_phone" name="phone" type="tel" required autocomplete="tel" value="{{ old('phone') }}" class="field"></div>
                <div><label for="ct_email" class="field-label">{{ __('Email') }}</label><input id="ct_email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" class="field"></div>
                <div>
                    <label for="ct_project" class="field-label">{{ __('Project of interest') }}</label>
                    <select id="ct_project" name="project_name" class="field">
                        <option value="General Inquiry">{{ __('General enquiry') }}</option>
                        @foreach($projects as $p)<option value="{{ $p->name }}">{{ $p->name }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div><label for="ct_msg" class="field-label">{{ __('Message') }}</label><textarea id="ct_msg" name="message" rows="4" maxlength="500" class="field" placeholder="{{ __('Budget, preferred plot size, questions about JDA patta…') }}">{{ old('message') }}</textarea></div>

            <fieldset class="rounded-2xl border border-dashed border-navy/20 p-5">
                <legend class="label px-2 text-ink/60">{{ __('Schedule a site visit (optional)') }}</legend>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label for="ct_date" class="field-label">{{ __('Date') }}</label><input id="ct_date" name="preferred_date" type="date" min="{{ now()->toDateString() }}" class="field"></div>
                    <div>
                        <label for="ct_slot" class="field-label">{{ __('Slot') }}</label>
                        <select id="ct_slot" name="time_slot" class="field">
                            <option value="">{{ __('Any time') }}</option>
                            @foreach(['10:00 AM - 12:00 PM', '12:00 PM - 02:00 PM', '03:00 PM - 05:00 PM'] as $slot)<option value="{{ $slot }}">{{ $slot }}</option>@endforeach
                        </select>
                    </div>
                </div>
                <label class="mt-4 flex items-center gap-2 text-sm text-ink/70"><input type="checkbox" name="pickup_requested" value="1" class="h-4 w-4 accent-navy"> {{ __('Arrange a chauffeured pickup within Jaipur') }}</label>
            </fieldset>
            <button type="submit" class="btn-navy">{{ __('Send enquiry') }}</button>
        </form>

        <div class="space-y-4 lg:col-span-5">
            <div class="overflow-hidden rounded-[1.5rem] border border-navy/10 bg-paper-200" data-reveal>
                <iframe src="{{ $mapSrc }}" title="{{ __('Map of Kedia Builders head office, Jaipur') }}" loading="lazy" referrerpolicy="no-referrer" class="h-80 w-full border-0 grayscale-[35%]"></iframe>
            </div>
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $lat }},{{ $lng }}" target="_blank" rel="noopener" class="btn-ghost w-full">{{ __('Get directions') }}</a>
            <div class="card">
                <div class="label text-ink/50">{{ __('Follow us') }}</div>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach(config('kedia.socials') as $s)<a href="{{ $s['url'] }}" target="_blank" rel="noopener" class="chip hover:border-terracotta hover:text-terracotta">{{ $s['label'] }} · {{ $s['handle'] }}</a>@endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
