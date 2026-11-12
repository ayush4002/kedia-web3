@extends('layouts.app')

@section('title', 'Architectural Gallery & Archives | Kedia Builders & Colonizers')

@section('content')

<section class="py-16 bg-[#FAF8F5] border-b border-[#0E1520]/10">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        <div class="max-w-3xl space-y-4">
            <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Visual Monograph') }}</span>
            <h1 class="font-serif text-4xl sm:text-6xl font-bold text-[#0E1520] tracking-tight">
                {{ __('Architectural Archives & Milestones') }}
            </h1>
            <p class="text-base text-[#0E1520]/70 leading-relaxed">
                {{ __('A visual journey through our master-planned townships, foundation stone ceremonies, construction craftsmanship, and finished residential sanctuaries.') }}
            </p>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24 space-y-12">
        
        <!-- Category Filter Pills -->
        <div class="flex flex-wrap items-center gap-3 font-mono text-xs">
            <button class="px-4 py-2 rounded-full bg-[#0E1520] text-white font-bold">{{ __('All Archives') }}</button>
            <button class="px-4 py-2 rounded-full bg-[#FAF8F5] text-[#0E1520] font-semibold hover:bg-black/5">{{ __('Villas & Architecture') }}</button>
            <button class="px-4 py-2 rounded-full bg-[#FAF8F5] text-[#0E1520] font-semibold hover:bg-black/5">{{ __('Bhoomi Pujan Ceremonies') }}</button>
            <button class="px-4 py-2 rounded-full bg-[#FAF8F5] text-[#0E1520] font-semibold hover:bg-black/5">{{ __('Township Infrastructure') }}</button>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div class="group rounded-3xl overflow-hidden aspect-[4/3] bg-[#0E1520] relative shadow-lg">
                <img fetchpriority="high" alt="{{ __('Grand Entrance Arch & 60ft Boulevard') }}" src="/media/img/2021/04/Kedia-Nikunj-1-1920.webp" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-6">
                    <div>
                        <div class="text-[10px] font-mono text-[#D4E845] uppercase">{{ __('Nikunj Villas') }}</div>
                        <h4 class="font-serif text-lg font-bold text-white">{{ __('Grand Entrance Arch & 60ft Boulevard') }}</h4>
                    </div>
                </div>
            </div>

            <div class="group rounded-3xl overflow-hidden aspect-[4/3] bg-[#0E1520] relative shadow-lg">
                <img loading="lazy" decoding="async" alt="{{ __('Turnkey Luxury Villa Facade') }}" src="/media/img/2021/04/Kedia-Nikunj-2-600.webp" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-6">
                    <div>
                        <div class="text-[10px] font-mono text-[#D4E845] uppercase">{{ __('Ganpati Greens') }}</div>
                        <h4 class="font-serif text-lg font-bold text-white">{{ __('Turnkey Luxury Villa Facade') }}</h4>
                    </div>
                </div>
            </div>

            <div class="group rounded-3xl overflow-hidden aspect-[4/3] bg-[#0E1520] relative shadow-lg">
                <img loading="lazy" decoding="async" alt="{{ __('Amara Vedic Bhoomi Pujan') }}" src="/media/img/2021/04/Amara-Bhoomi-Pujan-1-900.webp" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-6">
                    <div>
                        <div class="text-[10px] font-mono text-[#D4E845] uppercase">{{ __('Ceremony Milestone') }}</div>
                        <h4 class="font-serif text-lg font-bold text-white">{{ __('Amara Vedic Bhoomi Pujan') }}</h4>
                    </div>
                </div>
            </div>

            <div class="group rounded-3xl overflow-hidden aspect-[4/3] bg-[#0E1520] relative shadow-lg">
                <img loading="lazy" decoding="async" alt="{{ __('Township Temple Prana Pratishtha') }}" src="/media/img/2021/04/Prana-Pratishtha-ceremony-1-900.webp" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-6">
                    <div>
                        <div class="text-[10px] font-mono text-[#D4E845] uppercase">{{ __('Vedic Heritage') }}</div>
                        <h4 class="font-serif text-lg font-bold text-white">{{ __('Township Temple Prana Pratishtha') }}</h4>
                    </div>
                </div>
            </div>

            <div class="group rounded-3xl overflow-hidden aspect-[4/3] bg-[#0E1520] relative shadow-lg">
                <img loading="lazy" decoding="async" alt="{{ __('Landscaped Haveli Gardens') }}" src="/media/img/2021/04/Kedia-Nikunj-4-900.webp" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-6">
                    <div>
                        <div class="text-[10px] font-mono text-[#D4E845] uppercase">{{ __('Clubhouse & Greens') }}</div>
                        <h4 class="font-serif text-lg font-bold text-white">{{ __('Landscaped Haveli Gardens') }}</h4>
                    </div>
                </div>
            </div>

            <div class="group rounded-3xl overflow-hidden aspect-[4/3] bg-[#0E1520] relative shadow-lg">
                <img loading="lazy" decoding="async" alt="{{ __('Press Coverage & Industry Honors') }}" src="/media/img/2021/04/Press-Release-900.webp" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-6">
                    <div>
                        <div class="text-[10px] font-mono text-[#D4E845] uppercase">{{ __('Media Dispatches') }}</div>
                        <h4 class="font-serif text-lg font-bold text-white">{{ __('Press Coverage & Industry Honors') }}</h4>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection
