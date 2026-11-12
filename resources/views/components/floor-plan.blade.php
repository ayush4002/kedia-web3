{{--
    Interactive, INDICATIVE floor plan drawn from the unit's type and size.
    Hover / focus / tap a room to read its details. Real architect drawings should
    replace this before launch (the UI tags it as a sample).
--}}
@props(['unit'])
@php
    $sqft = (int) $unit->area_sqft;
    $isPlot = $unit->unit_type === 'Plot';
    // [key, label, x, y, w, h, share of area]
    if ($isPlot) {
        $rooms = [
            ['plot', __('Buildable plot'), 70, 60, 300, 220, 0.72],
            ['front', __('Front setback'), 70, 280, 300, 50, 0.12],
            ['side', __('Side setback'), 370, 60, 50, 270, 0.16],
        ];
    } elseif ($unit->unit_type === 'Apartment') {
        $rooms = [
            ['living', __('Living & dining'), 40, 40, 220, 150, 0.30],
            ['kitchen', __('Kitchen'), 260, 40, 120, 90, 0.11],
            ['utility', __('Utility'), 380, 40, 70, 90, 0.05],
            ['master', __('Master bedroom'), 40, 190, 170, 140, 0.20],
            ['bed2', __('Bedroom 2'), 210, 190, 130, 140, 0.15],
            ['bath', __('Bathrooms'), 260, 130, 190, 60, 0.09],
            ['balcony', __('Balcony'), 340, 190, 110, 140, 0.10],
        ];
    } else {
        $rooms = [
            ['lawn', __('Private lawn & parking'), 40, 250, 410, 80, 0.16],
            ['living', __('Living & dining'), 40, 100, 200, 150, 0.26],
            ['kitchen', __('Kitchen'), 240, 100, 110, 80, 0.10],
            ['bed', __('Bedroom'), 350, 100, 100, 150, 0.15],
            ['courtyard', __('Courtyard'), 240, 180, 110, 70, 0.08],
            ['master', __('Master bedroom'), 40, 40, 170, 60, 0.14],
            ['bath', __('Bathrooms'), 210, 40, 110, 60, 0.06],
            ['stair', __('Staircase'), 320, 40, 130, 60, 0.05],
        ];
    }
    $uid = 'fp' . $unit->id;
@endphp
<figure {{ $attributes->merge(['class' => 'rounded-[1.5rem] border border-navy/10 bg-paper-50 p-5 sm:p-6']) }} data-floor-plan>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <figcaption class="font-serif text-xl text-navy">{{ $isPlot ? __('Plot layout') : __('Floor plan') }}</figcaption>
        <div class="flex items-center gap-2">
            <x-sample-tag />
            <span class="label text-ink/40">{{ __('Indicative') }} · {{ \App\Support\Format::inr($sqft) }} {{ __('sq ft') }}</span>
        </div>
    </div>
    <svg viewBox="0 0 490 370" class="mt-4 w-full" role="group" aria-label="{{ __('Interactive floor plan. Select a room for details.') }}">
        <defs>
            <pattern id="{{ $uid }}-grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M20 0H0V20" fill="none" stroke="#0E1520" stroke-opacity=".06"/></pattern>
        </defs>
        <rect width="490" height="370" fill="url(#{{ $uid }}-grid)"/>
        @foreach($rooms as [$key, $label, $x, $y, $w, $h, $share])
            <g tabindex="0" role="button" class="cursor-pointer outline-none [&:focus-visible>rect]:stroke-brass [&:hover>rect]:fill-brass/25 [&.is-on>rect]:fill-brass/30 [&.is-on>rect]:stroke-brass"
               data-room="{{ $label }}" data-size="{{ \App\Support\Format::inr(round($sqft * $share)) }} {{ __('sq ft') }}" aria-label="{{ $label }}, {{ __('about') }} {{ round($sqft * $share) }} {{ __('sq ft') }}">
                <rect x="{{ $x }}" y="{{ $y }}" width="{{ $w }}" height="{{ $h }}" fill="#FBF8F2" stroke="#0E1520" stroke-width="1.5" class="transition-colors"/>
                <text x="{{ $x + $w / 2 }}" y="{{ $y + $h / 2 }}" text-anchor="middle" dominant-baseline="middle" font-family="JetBrains Mono, monospace" font-size="{{ $w < 110 ? 8 : 10 }}" fill="#0E1520" class="pointer-events-none uppercase">{{ \Illuminate\Support\Str::limit($label, $w < 110 ? 12 : 26, '…') }}</text>
            </g>
        @endforeach
        {{-- north arrow --}}
        <g transform="translate(455 22)" stroke="#C8A24B" fill="none" stroke-width="1.5"><path d="M0 14V-8M-5 -2l5 -8 5 8"/><text x="0" y="26" text-anchor="middle" font-family="JetBrains Mono, monospace" font-size="9" fill="#C8A24B" stroke="none">N</text></g>
    </svg>
    <p class="mt-3 flex flex-wrap items-center justify-between gap-2 text-sm" aria-live="polite">
        <span class="font-medium text-navy" data-room-name>{{ __('Select a room') }}</span>
        <span class="font-mono text-xs text-ink/60" data-room-size>{{ $unit->facing }} · {{ $isPlot ? rtrim(rtrim(number_format((float) $unit->area_sqyd, 2), '0'), '.') . ' ' . __('sq yd') : $unit->bedrooms . ' ' . __('BHK') }}</span>
    </p>
</figure>

@once
@push('scripts')
<script>
(function () {
    const pick = (g) => {
        const root = g.closest('[data-floor-plan]');
        root.querySelectorAll('[data-room]').forEach((n) => n.classList.toggle('is-on', n === g));
        root.querySelector('[data-room-name]').textContent = g.dataset.room;
        root.querySelector('[data-room-size]').textContent = '≈ ' + g.dataset.size;
    };
    document.addEventListener('click', (e) => { const g = e.target.closest('[data-floor-plan] [data-room]'); if (g) pick(g); });
    document.addEventListener('mouseover', (e) => { const g = e.target.closest('[data-floor-plan] [data-room]'); if (g) pick(g); });
    document.addEventListener('focusin', (e) => { const g = e.target.closest('[data-floor-plan] [data-room]'); if (g) pick(g); });
})();
</script>
@endpush
@endonce
