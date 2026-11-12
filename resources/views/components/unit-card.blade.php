{{-- Unit "product" card with shortlist and compare. --}}
@props(['unit', 'layout' => 'grid'])
@php $img = $unit->relationLoaded('project') && $unit->project ? \App\Models\Project::media($unit->project->cover_image, 600) : null; @endphp
<article {{ $attributes->merge(['class' => 'group relative flex flex-col overflow-hidden rounded-[1.25rem] border border-navy/10 bg-paper-50 transition duration-300 hover:-translate-y-0.5 hover:border-brass/60 hover:shadow-[0_24px_50px_-30px_rgba(14,21,32,0.5)] ' . ($layout === 'list' ? 'sm:flex-row' : '')]) }} data-reveal>
    <a href="{{ $unit->url }}" class="block {{ $layout === 'list' ? 'sm:w-60 sm:flex-none' : '' }}" tabindex="-1" aria-hidden="true">
        <div class="relative {{ $layout === 'list' ? 'aspect-[4/3] sm:aspect-auto sm:h-full' : 'aspect-[4/3]' }} overflow-hidden bg-navy">
            @if($img)
                <img src="{{ $img }}" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover opacity-90 transition duration-700 group-hover:scale-105">
            @endif
            <x-status-pill :status="$unit->status" class="absolute left-3 top-3 !bg-paper/95" />
        </div>
    </a>
    <div class="flex flex-1 flex-col p-5">
        <div class="label text-brass-700">{{ $unit->project_name }}</div>
        <h3 class="mt-1 font-serif text-xl leading-tight text-navy"><a href="{{ $unit->url }}" class="hover:text-terracotta">{{ $unit->title }}</a></h3>
        <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 font-mono text-[11px] uppercase tracking-wider text-ink/60">
            <span>{{ $unit->unit_code }}</span>
            <span>{{ \App\Support\Format::inr($unit->area_sqft) }} {{ __('sq ft') }}</span>
            @if($unit->unit_type !== 'Plot')<span>{{ $unit->bedrooms }} {{ __('BHK') }} · {{ $unit->bathrooms }} {{ __('baths') }}</span>@endif
            <span>{{ $unit->facing }}</span>
        </div>
        <div class="mt-auto flex items-end justify-between gap-3 pt-4">
            <div>
                <div class="label text-ink/40">{{ __('All-inclusive') }}</div>
                <div class="font-serif text-2xl text-navy">{{ $unit->price_formatted }}</div>
            </div>
            <div class="flex gap-1.5">
                <button type="button" data-compare="{{ json_encode(['id' => 'unit-' . $unit->id, 'title' => $unit->unit_code . ' · ' . $unit->project_name]) }}" aria-pressed="false" aria-label="{{ __('Compare') }} {{ $unit->unit_code }}" title="{{ __('Compare') }}"
                    class="grid h-10 w-10 place-items-center rounded-full border border-navy/15 text-ink/70 transition hover:border-brass hover:text-navy [&.is-on]:border-brass [&.is-on]:bg-brass [&.is-on]:text-navy">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M7 4v16M17 4v16M3 8h8M13 16h8"/></svg>
                </button>
                <button type="button" data-wishlist="{{ json_encode($unit->shopItem()) }}" aria-pressed="false" aria-label="{{ __('Save to shortlist') }}: {{ $unit->unit_code }}" title="{{ __('Shortlist') }}"
                    class="grid h-10 w-10 place-items-center rounded-full border border-navy/15 text-ink/70 transition hover:border-terracotta hover:text-terracotta [&.is-on]:border-terracotta [&.is-on]:text-terracotta">
                    <svg class="h-4 w-4 [.is-on_&]:fill-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M12 20s-7-4.4-7-10a4 4 0 017-2.6A4 4 0 0119 10c0 5.6-7 10-7 10z"/></svg>
                </button>
            </div>
        </div>
    </div>
</article>
