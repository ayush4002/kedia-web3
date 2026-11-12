{{-- "Architectural plate" project card: hovering swaps the photo for a floor-plan sketch. --}}
@props(['project', 'index' => null])
@php $available = $project->available_units_count ?? null; @endphp
<article {{ $attributes->merge(['class' => 'group relative']) }} data-reveal>
    <a href="{{ route('projects.show', $project->slug) }}" class="plate">
        <div class="relative aspect-[4/5] overflow-hidden bg-navy">
            <img src="{{ $project->card_image }}" alt="{{ $project->name }}" loading="lazy" decoding="async" class="plate-photo h-full w-full object-cover transition duration-700 group-hover:scale-105">
            <div class="plate-sketch grid place-items-center bg-navy/80 text-brass">
                <x-blueprint variant="plots" class="h-3/4 w-3/4" />
                <span class="label absolute bottom-4 left-0 right-0 text-center text-brass/80">{{ __('Layout sketch') }} · {{ $project->type }}</span>
            </div>
            <div class="absolute inset-x-0 top-0 flex flex-wrap items-start gap-2 p-4 pr-16">
                <span class="label rounded-full bg-navy/80 px-3 py-1 text-paper backdrop-blur">{{ $index !== null ? __('Plate') . ' ' . str_pad($index + 1, 2, '0', STR_PAD_LEFT) : $project->classification }}</span>
                <span class="label rounded-full px-3 py-1 backdrop-blur {{ $project->status === 'completed' ? 'bg-paper/90 text-navy' : 'bg-brass/90 text-navy' }}">{{ $project->status === 'completed' ? __('Completed') : __('Ongoing') }}</span>
            </div>
        </div>
        <div class="border-t border-navy/10 p-5">
            <h3 class="truncate font-serif text-2xl text-navy">{{ $project->name }}</h3>
            <p class="mt-1 truncate text-sm text-ink/60">{{ $project->location }} · {{ $project->type }}</p>
            <div class="mt-4 flex items-end justify-between gap-3 border-t border-dashed border-navy/15 pt-4">
                <div>
                    <div class="label text-ink/40">{{ __('Starting') }}</div>
                    <div class="font-serif text-lg text-navy">{{ str_replace(['Starting at ', '*'], '', $project->price_range) }}</div>
                </div>
                <div class="text-right">
                    <div class="label text-ink/40">RERA</div>
                    <div class="font-mono text-xs text-ink/70">{{ $project->rera_number }}</div>
                </div>
            </div>
            @if($available !== null && $project->status !== 'completed')
                <div class="label mt-3 text-status-available">{{ $available }} {{ __('units available') }}</div>
            @endif
        </div>
    </a>
    <button type="button" data-wishlist="{{ json_encode($project->shopItem()) }}" aria-pressed="false" aria-label="{{ __('Save to shortlist') }}: {{ $project->name }}"
        class="absolute right-4 top-4 z-10 grid h-10 w-10 place-items-center rounded-full bg-paper/95 text-navy shadow-lg transition hover:text-terracotta [&.is-on]:text-terracotta">
        <svg class="h-[18px] w-[18px] [.is-on_&]:fill-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M12 20s-7-4.4-7-10a4 4 0 017-2.6A4 4 0 0119 10c0 5.6-7 10-7 10z"/></svg>
    </button>
</article>
