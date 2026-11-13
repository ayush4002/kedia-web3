@extends('layouts.app')

@section('title', 'Gallery · Projects, Events, Press & CSR | Kedia Builders & Colonizers')
@section('meta_description', 'Photos and drone films of Kedia townships in Jaipur, Bhoomi Pujan ceremonies, press coverage and CSR initiatives.')

@php
    $labels = ['Project' => __('Projects'), 'Event' => __('Events'), 'Press Release' => __('Press releases'), 'CSR' => __('CSR')];
@endphp

@section('content')
<x-page-hero :eyebrow="__('Gallery')" :title="__('Built, celebrated, shared')" :crumb="__('Gallery')">
    {{ __('Townships from the air and the ground, the ceremonies that began them, and the press that followed.') }}
</x-page-hero>

<section class="section-paper py-14" data-gallery>
    <div class="container-site">
        <div class="flex flex-wrap gap-2" role="group" aria-label="{{ __('Filter gallery') }}">
            <button type="button" data-filter="all" aria-pressed="true" class="chip aria-pressed:border-navy aria-pressed:bg-navy aria-pressed:text-paper">{{ __('All') }} ({{ $items->count() }})</button>
            @foreach($categories as $cat)
                <button type="button" data-filter="{{ $cat }}" aria-pressed="false" class="chip aria-pressed:border-navy aria-pressed:bg-navy aria-pressed:text-paper">{{ $labels[$cat] }} ({{ $items->where('category', $cat)->count() }})</button>
            @endforeach
        </div>

        <div class="masonry mt-10">
            @foreach($items as $item)
                @php $isVideo = ($item['type'] ?? 'image') === 'video'; @endphp
                <a href="{{ $item['src'] }}" data-lightbox data-type="{{ $isVideo ? 'video' : 'image' }}" data-src="{{ $item['src'] }}" data-caption="{{ $item['caption'] }}" data-category="{{ $item['category'] }}"
                   class="group relative block overflow-hidden rounded-2xl bg-navy">
                    <img src="{{ $item['thumb'] }}" alt="{{ $item['caption'] }}" loading="lazy" decoding="async" class="w-full transition duration-700 group-hover:scale-105 group-hover:opacity-75">
                    @if($isVideo)
                        <span class="absolute left-1/2 top-1/2 grid h-14 w-14 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-paper/90 text-navy shadow-lg" aria-hidden="true">▶</span>
                    @endif
                    <span class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-2 bg-gradient-to-t from-navy/90 p-4 pt-10 text-paper opacity-0 transition group-hover:opacity-100 group-focus-visible:opacity-100">
                        <span class="text-sm">{{ $item['caption'] }}</span>
                        <span class="label flex-none text-brass">{{ $labels[$item['category']] ?? $item['category'] }}</span>
                    </span>
                    @if(!empty($item['placeholder']))<x-sample-tag class="absolute right-3 top-3 bg-paper" />@endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    const root = document.querySelector('[data-gallery]');
    root.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-filter]');
        if (!btn) return;
        const cat = btn.dataset.filter;
        root.querySelectorAll('[data-filter]').forEach((b) => b.setAttribute('aria-pressed', b === btn ? 'true' : 'false'));
        root.querySelectorAll('[data-category]').forEach((item) => { item.hidden = cat !== 'all' && item.dataset.category !== cat; });
    });
})();
</script>
@endpush
