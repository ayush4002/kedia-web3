@extends('layouts.app')

@section('title', 'Compare Units | Kedia Builders & Colonizers')
@section('robots', 'noindex, follow')

@php
    $sqyd = fn ($u) => (float) $u->area_sqyd > 0 ? rtrim(rtrim(number_format((float) $u->area_sqyd, 2), '0'), '.') . ' ' . __('sq yd') : '—';
    $rows = [
        __('Project') => fn ($u) => $u->project_name,
        __('Location') => fn ($u) => $u->project->location ?? 'Jaipur',
        __('Type') => fn ($u) => $u->unit_type,
        __('Configuration') => fn ($u) => $u->title,
        __('Area') => fn ($u) => \App\Support\Format::inr($u->area_sqft) . ' ' . __('sq ft'),
        __('Plot size') => $sqyd,
        __('Bedrooms / baths') => fn ($u) => $u->unit_type === 'Plot' ? '—' : $u->bedrooms . ' / ' . $u->bathrooms,
        __('Facing') => fn ($u) => $u->facing,
        __('Floor') => fn ($u) => $u->floor_level,
        __('Possession') => fn ($u) => $u->possession ?: '—',
        'RERA' => fn ($u) => $u->project->rera_number ?? '—',
        __('Base price') => fn ($u) => '₹ ' . \App\Support\Format::inr($pricing[$u->id]['base_price']),
        __('GST') => fn ($u) => $pricing[$u->id]['gst'] ? '₹ ' . \App\Support\Format::inr($pricing[$u->id]['gst']) : __('Not applicable'),
        __('Price per sq ft') => fn ($u) => $u->area_sqft ? '₹ ' . \App\Support\Format::inr($u->total_price / $u->area_sqft) : '—',
        __('Online token') => fn ($u) => '₹ ' . \App\Support\Format::inr($pricing[$u->id]['token_amount']),
    ];
    $cheapest = $units->min('total_price');
@endphp

@section('content')
<section class="section-navy overflow-hidden">
    <div class="blueprint-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="container-site relative py-14">
        <div class="eyebrow">{{ __('Compare') }}</div>
        <h1 class="display-lg mt-3 text-paper">{{ __('Side by side') }}</h1>
        <p class="mt-3 max-w-xl text-paper/70">{{ __('Compare up to three units on size, price and specifications before you book.') }}</p>
    </div>
</section>

<section class="section-paper py-14">
    <div class="container-site">
        @if($units->isEmpty())
            <div class="rounded-[2rem] border border-dashed border-navy/20 p-12 text-center" id="compareEmpty">
                <x-blueprint variant="plots" class="mx-auto h-32 w-48 text-brass/70" />
                <h2 class="mt-6 font-serif text-3xl text-navy">{{ __('No units to compare yet') }}</h2>
                <p class="mt-2 text-ink/60">{{ __('Tap the compare icon on any unit card to add it here (up to 3).') }}</p>
                <a href="{{ route('projects.index', ['view' => 'units']) }}" class="btn-navy mt-6">{{ __('Browse units') }}</a>
            </div>
        @else
            <div class="overflow-x-auto rounded-[1.5rem] border border-navy/10 bg-paper-50">
                <table class="w-full min-w-[40rem] text-left text-sm">
                    <caption class="sr-only">{{ __('Unit comparison') }}</caption>
                    <thead>
                        <tr>
                            <th scope="col" class="w-44 p-5 align-bottom"><span class="label text-ink/40">{{ $units->count() }} {{ __('units') }}</span></th>
                            @foreach($units as $unit)
                                <th scope="col" class="border-l border-navy/10 p-5 align-top font-normal">
                                    <x-status-pill :status="$unit->status" />
                                    <div class="mt-3 font-serif text-2xl leading-tight text-navy"><a href="{{ $unit->url }}" class="hover:text-terracotta">{{ $unit->unit_number }}</a></div>
                                    <div class="label mt-1 text-ink/50">{{ $unit->unit_code }}</div>
                                    <div class="mt-3 font-serif text-3xl text-navy">{{ $unit->price_formatted }}</div>
                                    @if($units->count() > 1 && $unit->total_price === $cheapest)<div class="label mt-1 text-status-available">{{ __('Lowest price') }}</div>@endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $label => $value)
                            <tr class="border-t border-navy/10">
                                <th scope="row" class="label p-5 font-medium text-ink/50">{{ $label }}</th>
                                @foreach($units as $unit)
                                    <td class="border-l border-navy/10 p-5 text-navy">{{ $value($unit) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="border-t border-navy/10">
                            <td class="p-5"></td>
                            @foreach($units as $unit)
                                <td class="border-l border-navy/10 p-5">
                                    <div class="grid gap-2">
                                        @if($unit->status === 'available')<a href="{{ route('booking.checkout', $unit->id) }}" class="btn-brass btn-sm">{{ __('Book now') }}</a>@endif
                                        <a href="{{ $unit->url }}" class="btn-ghost btn-sm">{{ __('View unit details') }}</a>
                                        <button type="button" data-compare="{{ json_encode(['id' => 'unit-' . $unit->id, 'title' => $unit->unit_code . ' · ' . $unit->project_name]) }}" class="label text-ink/50 underline hover:text-terracotta" data-compare-remove>{{ __('Remove') }}</button>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="mt-4 text-xs text-ink/50">{{ __('Prices are all-inclusive estimates; see each cost sheet for the full breakup.') }}</p>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
// Keep the URL in step with the compare tray: opening /compare without ids loads the saved list,
// and removing a unit reloads the page without it.
(function () {
    const read = () => { try { return JSON.parse(localStorage.getItem('kedia_compare') || '[]'); } catch (e) { return []; } };
    const params = new URLSearchParams(location.search);
    if (!params.get('units') && read().length) {
        location.replace('/compare?units=' + read().map((x) => encodeURIComponent(x.id)).join(','));
        return;
    }
    document.addEventListener('click', (e) => {
        if (!e.target.closest('[data-compare-remove]')) return;
        setTimeout(() => {
            const ids = read().map((x) => encodeURIComponent(x.id)).join(',');
            location.href = ids ? '/compare?units=' + ids : '/compare';
        }, 50);
    });
})();
</script>
@endpush
