@extends('layouts.app')

@section('title', 'About Us | Kedia Builders & Colonizers - 4+ Decades of Excellence')

@section('content')

<!-- Hero Section -->
<section class="py-16 bg-[#FAF8F5] border-b border-[#0E1520]/10">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        <div class="max-w-3xl space-y-4">
            <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Corporate Heritage · Est. 1980') }}</span>
            <h1 class="font-serif text-4xl sm:text-6xl font-bold text-[#0E1520] tracking-tight">
                {{ __('Crafting Jaipur\'s Architectural Future For Over 4 Decades') }}
            </h1>
            <p class="text-base sm:text-lg text-[#0E1520]/70 leading-relaxed font-normal">
                {{ __('Kedia Builders and Colonizers has been a pioneering name in the real estate market of Jaipur. Over 41+ years, we have completed over 200 land development projects, creating enduring benchmarks in turnkey villa construction and master-planned residential communities.') }}
            </p>
        </div>
    </div>
</section>

<!-- Vision & Mission Banner -->
<section class="py-16 bg-white border-b border-[#0E1520]/10">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="p-8 sm:p-10 rounded-3xl bg-[#0E1520] text-white shadow-xl space-y-4">
                <span class="text-xs font-mono uppercase tracking-widest text-[#D4E845]">{{ __('Our Corporate Vision') }}</span>
                <h3 class="font-serif text-3xl font-bold">{{ __('Traditional Wisdom Infused With Modern Concepts') }}</h3>
                <p class="text-sm text-white/70 leading-relaxed">
                    {{ __('Kedia Builders and Colonizers strive to live up to traditional wisdom infused with modern concepts. Change is constant, and the quality of our projects reflects our upgraded technologies. We aspire to play an important role in building a new India and to be number one in real estate.') }}
                </p>
            </div>

            <div class="p-8 sm:p-10 rounded-3xl bg-[#FAF8F5] border border-[#0E1520]/10 shadow-sm space-y-4">
                <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Our Enduring Mission') }}</span>
                <h3 class="font-serif text-3xl font-bold text-[#0E1520]">{{ __('World-Class Construction & Security') }}</h3>
                <p class="text-sm text-[#0E1520]/70 leading-relaxed">
                    {{ __('To deliver world-class construction, integrity of commitment, prompt delivery and security of investment. Qualified professionals maintain the standard in designing and constructing the dreams of our customers.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Section -->
<section class="py-20 bg-[#FAF8F5]">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24 space-y-16">
        
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Executive Directorate') }}</span>
            <h2 class="font-serif text-3xl sm:text-5xl font-bold text-[#0E1520]">{{ __('Leadership That Inspires Trust') }}</h2>
            <p class="text-sm text-[#0E1520]/60">{{ __('A multi-generational legacy combining foundational wisdom with contemporary execution.') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Chairman -->
            <div class="bg-white rounded-3xl p-8 border border-[#0E1520]/10 shadow-sm space-y-6 flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="w-28 h-28 rounded-full overflow-hidden border-2 border-[#0E1520] mx-auto shadow-lg">
                        <img src="/media/img/2021/04/Mr-Rajendra-kedia-600.webp" alt="Mr Rajendra Kedia" class="w-full h-full object-cover">
                    </div>
                    <div class="text-center">
                        <h3 class="font-serif text-2xl font-bold text-[#0E1520]">{{ __('Mr Rajendra Kedia') }}</h3>
                        <div class="text-xs font-mono text-amber-700 font-bold uppercase tracking-wider mt-0.5">{{ __('Founder & Chairman') }}</div>
                        <div class="text-[11px] font-mono text-[#0E1520]/40 mt-1">{{ __('Pioneering Since 1980 (44+ Years)') }}</div>
                    </div>
                    <p class="text-xs text-[#0E1520]/70 leading-relaxed text-center">
                        {{ __('Laid the bedrock of modern real estate colonization in Jaipur in 1980. Over four decades, Mr. Rajendra Kedia has created notable footprints in construction with over 200 land development achievements.') }}
                    </p>
                </div>
            </div>

            <!-- Director -->
            <div class="bg-white rounded-3xl p-8 border border-[#0E1520]/10 shadow-sm space-y-6 flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="w-28 h-28 rounded-full overflow-hidden border-2 border-[#0E1520] mx-auto shadow-lg">
                        <img src="/media/img/2021/04/Mr-Saurabh-Kedia-600.webp" alt="Mr Saurabh Kedia" class="w-full h-full object-cover">
                    </div>
                    <div class="text-center">
                        <h3 class="font-serif text-2xl font-bold text-[#0E1520]">{{ __('Mr Saurabh Kedia') }}</h3>
                        <div class="text-xs font-mono text-amber-700 font-bold uppercase tracking-wider mt-0.5">{{ __('Director') }}</div>
                        <div class="text-[11px] font-mono text-[#0E1520]/40 mt-1">{{ __('Master\'s from Bhawani Niketan (10+ Yrs)') }}</div>
                    </div>
                    <p class="text-xs text-[#0E1520]/70 leading-relaxed text-center">
                        {{ __('Hands-on leader working from the inside out. Sets an uncompromising bar for structural quality, earthquake-resistant design, and turnkey residential execution across Jaipur.') }}
                    </p>
                </div>
            </div>

            <!-- CEO -->
            <div class="bg-white rounded-3xl p-8 border border-[#0E1520]/10 shadow-sm space-y-6 flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="w-28 h-28 rounded-full overflow-hidden border-2 border-[#0E1520] mx-auto shadow-lg">
                        <img src="/media/img/2021/04/Mr-Gaurav-Kedia-600.webp" alt="Mr Gaurav Kedia" class="w-full h-full object-cover">
                    </div>
                    <div class="text-center">
                        <h3 class="font-serif text-2xl font-bold text-[#0E1520]">{{ __('Mr Gaurav Kedia') }}</h3>
                        <div class="text-xs font-mono text-amber-700 font-bold uppercase tracking-wider mt-0.5">{{ __('Chief Executive Officer') }}</div>
                        <div class="text-[11px] font-mono text-[#0E1520]/40 mt-1">{{ __('B.Com from Commerce College (8+ Yrs)') }}</div>
                    </div>
                    <p class="text-xs text-[#0E1520]/70 leading-relaxed text-center">
                        {{ __('Believes the tiniest things make the biggest difference. The strategist behind client experience, digital e-commerce reservations, and modern living ergonomics.') }}
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Heritage Timeline (1980 to Today) -->
<section class="py-20 bg-[#0E1520] text-white">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24 space-y-12">
        
        <div class="text-center max-w-xl mx-auto space-y-2">
            <span class="text-xs font-mono uppercase tracking-widest text-[#D4E845]">{{ __('Historical Track Record') }}</span>
            <h2 class="font-serif text-3xl sm:text-4xl font-bold">{{ __('Four Decades of Turning Vision Into Reality') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 font-mono text-xs">
            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                <span class="font-serif text-3xl font-bold text-[#D4E845] block">1980</span>
                <div class="text-sm font-bold text-white">{{ __('Foundation by Rajendra Kedia') }}</div>
                <p class="text-white/60 leading-relaxed">{{ __('Pioneered private colony development in Jaipur with 100% legal title guarantees.') }}</p>
            </div>

            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                <span class="font-serif text-3xl font-bold text-[#D4E845] block">2005</span>
                <div class="text-sm font-bold text-white">{{ __('100+ Townships Completed') }}</div>
                <p class="text-white/60 leading-relaxed">{{ __('Expanded into large scale plotted schemes across Ajmer Road and Sikar Road.') }}</p>
            </div>

            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                <span class="font-serif text-3xl font-bold text-[#D4E845] block">2018</span>
                <div class="text-sm font-bold text-white">{{ __('Luxury Turnkey Villas') }}</div>
                <p class="text-white/60 leading-relaxed">{{ __('Launched celebrated villa communities Nikunj Villas and Ganpati Greens with full club facilities.') }}</p>
            </div>

            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                <span class="font-serif text-3xl font-bold text-[#D4E845] block">{{ __('Today') }}</span>
                <div class="text-sm font-bold text-white">{{ __('The Jaipur Blueprint') }}</div>
                <p class="text-white/60 leading-relaxed">{{ __('200+ projects completed, smart underground infrastructure, and online e-commerce booking.') }}</p>
            </div>
        </div>

    </div>
</section>

@endsection
