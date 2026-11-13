@extends('layouts.app')

@section('title', 'Cost Sheet · ' . $unit->unit_code . ' · ' . ($unit->project->title ?? 'Kedia Builders'))
@section('robots', 'noindex, nofollow')

@php $company = config('kedia.company'); @endphp

@section('content')
<section class="section-paper py-10">
    <div class="container-site max-w-3xl">
        <div class="no-print mb-6 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ $unit->url }}" class="label text-ink/50 hover:text-terracotta">← {{ __('Back to unit') }}</a>
            <div class="flex gap-2">
                <button type="button" onclick="window.print()" class="btn-navy btn-sm">{{ __('Download / print PDF') }}</button>
                @if($unit->status === 'available')<a href="{{ route('booking.checkout', $unit->id) }}" class="btn-brass btn-sm">{{ __('Book now') }}</a>@endif
            </div>
        </div>

        <article class="print-sheet rounded-[1.5rem] border border-navy/10 bg-white p-8 shadow-xl sm:p-12">
            <header class="flex flex-wrap items-start justify-between gap-6 border-b border-navy/10 pb-6">
                <div>
                    <img src="{{ asset('assets/logo-navy.png') }}" alt="Kedia Builders & Colonizers" class="h-12 w-auto">
                    <p class="mt-3 text-xs text-ink/60">{{ $company['address'] }}<br>{{ $company['phone'] }} · {{ $company['email'] }}</p>
                </div>
                <div class="text-right">
                    <h1 class="font-serif text-3xl text-navy">{{ __('Cost Sheet') }}</h1>
                    <p class="label mt-1 text-ink/50">{{ __('Generated') }} {{ now()->format('d M Y') }}</p>
                </div>
            </header>

            <dl class="grid gap-x-10 gap-y-3 border-b border-navy/10 py-6 text-sm sm:grid-cols-2">
                @foreach([
                    __('Project') => $unit->project->title ?? $unit->project_name,
                    __('Location') => $unit->project->location ?? 'Jaipur',
                    __('Unit') => $unit->title . ' · ' . $unit->unit_code,
                    __('Type') => $unit->unit_type,
                    __('Area') => \App\Support\Format::inr($unit->area_sqft) . ' sq ft' . ((float) $unit->area_sqyd > 0 ? ' · ' . rtrim(rtrim(number_format((float) $unit->area_sqyd, 2), '0'), '.') . ' sq yd' : ''),
                    __('Facing') => $unit->facing,
                    'RERA' => $unit->project->rera_number ?? '—',
                    __('Status') => $unit->status_label,
                ] as $label => $value)
                    <div class="flex justify-between gap-4"><dt class="label text-ink/50">{{ $label }}</dt><dd class="text-right text-navy">{{ $value }}</dd></div>
                @endforeach
            </dl>

            <x-price-breakup :pricing="$pricing" class="py-6" />

            <div class="flex items-center justify-between rounded-xl border border-brass/50 bg-paper-50 px-5 py-4">
                <div class="label text-ink/60">{{ __('Online booking token (5%)') }}</div>
                <div class="font-serif text-2xl text-navy">₹ {{ \App\Support\Format::inr($pricing['token_amount']) }}</div>
            </div>

            <footer class="mt-8 border-t border-navy/10 pt-5 text-[11px] leading-relaxed text-ink/50">
                {{ __('This cost sheet is indicative and valid on the date generated. Stamp duty and registration are estimates and are payable as per government rates at the time of registry. Final amounts are confirmed in the agreement to sale.') }}
            </footer>
        </article>
    </div>
</section>
@endsection
