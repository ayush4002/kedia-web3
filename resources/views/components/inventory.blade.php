{{--
    Interactive inventory: every REAL unit of a project drawn as a colour-coded cell
    (available / on hold / sold). Click a cell to select the unit.
--}}
@props(['project', 'units'])
@php
    $groups = $units->groupBy(fn ($u) => $u->tower_or_block ?: __('Phase 1'));
    $cell = [
        'available' => 'border-status-available/60 bg-status-available/15 text-paper hover:bg-status-available/35',
        'on_hold' => 'border-brass/60 bg-brass/15 text-paper hover:bg-brass/30',
        'sold' => 'border-paper/15 bg-paper/5 text-paper/40',
    ];
    $first = $units->firstWhere('status', 'available') ?? $units->first();
@endphp
<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-[2rem] bg-navy text-paper']) }} data-inventory>
    <div class="grid lg:grid-cols-[minmax(0,1fr)_22rem]">
        <div class="relative p-6 sm:p-8">
            <div class="blueprint-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
            <div class="relative">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <div class="eyebrow">{{ __('Interactive inventory') }}</div>
                        <h3 class="mt-1 font-serif text-2xl">{{ __('Pick your unit') }}</h3>
                    </div>
                    <ul class="flex flex-wrap gap-4 font-mono text-[11px] uppercase tracking-wider text-paper/70" aria-label="{{ __('Legend') }}">
                        <li class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-sm bg-status-available" aria-hidden="true"></span>{{ __('Available') }} ({{ $units->where('status', 'available')->count() }})</li>
                        <li class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-sm bg-brass" aria-hidden="true"></span>{{ __('On Hold') }} ({{ $units->where('status', 'on_hold')->count() }})</li>
                        <li class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-sm bg-paper/30" aria-hidden="true"></span>{{ __('Sold') }} ({{ $units->where('status', 'sold')->count() }})</li>
                    </ul>
                </div>

                @foreach($groups as $block => $blockUnits)
                    <div class="mt-8">
                        <div class="label flex items-center gap-3 text-paper/50"><span class="h-px flex-1 bg-paper/10" aria-hidden="true"></span>{{ $block }}<span class="h-px flex-1 bg-paper/10" aria-hidden="true"></span></div>
                        <div class="mt-4 grid grid-cols-3 gap-2 sm:grid-cols-5 xl:grid-cols-7">
                            @foreach($blockUnits as $unit)
                                <button type="button"
                                    class="rounded-lg border px-2 py-3 text-center transition focus-visible:outline-brass aria-pressed:ring-2 aria-pressed:ring-brass aria-pressed:ring-offset-2 aria-pressed:ring-offset-navy {{ $cell[$unit->status] ?? $cell['sold'] }}"
                                    aria-pressed="{{ $first && $unit->id === $first->id ? 'true' : 'false' }}"
                                    aria-label="{{ $unit->unit_number }} — {{ $unit->status_label }}, {{ $unit->price_formatted }}"
                                    data-unit="{{ json_encode([
                                        'code' => $unit->unit_code,
                                        'number' => $unit->unit_number,
                                        'title' => $unit->title,
                                        'status' => $unit->status,
                                        'statusLabel' => $unit->status_label,
                                        'area' => \App\Support\Format::inr($unit->area_sqft) . ' ' . __('sq ft'),
                                        'plot' => rtrim(rtrim(number_format((float) $unit->area_sqyd, 2), '0'), '.') . ' ' . __('sq yd'),
                                        'facing' => $unit->facing,
                                        'price' => $unit->price_formatted,
                                        'url' => $unit->url,
                                        'book' => route('booking.checkout', $unit->id),
                                    ]) }}">
                                    <span class="block font-mono text-[11px] font-medium">{{ preg_replace('/^[A-Z]+-/', '', $unit->unit_code) }}</span>
                                    <span class="mt-0.5 block font-mono text-[9px] opacity-70">{{ rtrim(rtrim(number_format((float) $unit->area_sqyd, 1), '0'), '.') }} {{ __('sq yd') }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Selected unit --}}
        <div class="border-t border-paper/10 bg-navy-700 p-6 sm:p-8 lg:border-l lg:border-t-0" aria-live="polite">
            @if($first)
                <div class="label text-brass">{{ __('Selected unit') }}</div>
                <div class="mt-2 flex items-start justify-between gap-3">
                    <h4 class="font-serif text-3xl" data-inv="number">{{ $first->unit_number }}</h4>
                    <span class="rounded-full border border-paper/20 px-2.5 py-1 font-mono text-[10px] uppercase tracking-wider" data-inv="statusLabel">{{ $first->status_label }}</span>
                </div>
                <p class="mt-1 text-sm text-paper/60" data-inv="title">{{ $first->title }}</p>
                <dl class="mt-6 space-y-3 border-y border-paper/10 py-5 text-sm">
                    <div class="flex justify-between gap-4"><dt class="label text-paper/50">{{ __('Unit code') }}</dt><dd class="font-mono" data-inv="code">{{ $first->unit_code }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="label text-paper/50">{{ __('Area') }}</dt><dd data-inv="area">{{ \App\Support\Format::inr($first->area_sqft) }} {{ __('sq ft') }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="label text-paper/50">{{ __('Plot size') }}</dt><dd data-inv="plot">{{ rtrim(rtrim(number_format((float) $first->area_sqyd, 2), '0'), '.') }} {{ __('sq yd') }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="label text-paper/50">{{ __('Facing') }}</dt><dd data-inv="facing">{{ $first->facing }}</dd></div>
                </dl>
                <div class="mt-5">
                    <div class="label text-paper/50">{{ __('All-inclusive') }}</div>
                    <div class="font-serif text-4xl text-brass" data-inv="price">{{ $first->price_formatted }}</div>
                </div>
                <div class="mt-6 grid gap-2">
                    <a href="{{ route('booking.checkout', $first->id) }}" class="btn-brass {{ $first->status !== 'available' ? 'hidden' : '' }}" data-inv-book>{{ __('Book this unit') }}</a>
                    <a href="{{ $first->url }}" class="btn-ghost-light" data-inv-view>{{ __('View unit details') }}</a>
                    <button type="button" data-open="visitModal" class="btn-ghost-light">{{ __('Schedule a visit') }}</button>
                </div>
            @else
                <p class="text-sm text-paper/60">{{ __('Units for this project are not listed online yet. Book a visit and our team will share availability.') }}</p>
                <button type="button" data-open="visitModal" class="btn-brass mt-6">{{ __('Book a Visit') }}</button>
            @endif
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
document.addEventListener('click', function (e) {
    const cell = e.target.closest('[data-inventory] [data-unit]');
    if (!cell) return;
    const root = cell.closest('[data-inventory]');
    const unit = JSON.parse(cell.dataset.unit);
    root.querySelectorAll('[data-unit]').forEach((b) => b.setAttribute('aria-pressed', b === cell ? 'true' : 'false'));
    root.querySelectorAll('[data-inv]').forEach((n) => { n.textContent = unit[n.dataset.inv] ?? ''; });
    const book = root.querySelector('[data-inv-book]');
    const view = root.querySelector('[data-inv-view]');
    if (book) { book.href = unit.book; book.classList.toggle('hidden', unit.status !== 'available'); }
    if (view) view.href = unit.url;
});
</script>
@endpush
@endonce
