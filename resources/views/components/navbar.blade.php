@php
    $currentLocale = app()->getLocale();
@endphp

<header class="sticky top-0 z-50 bg-[#FAF8F5] border-b border-[#0E1520]/10 shadow-sm transition-all duration-300" style="background-color: #FAF8F5;">
    <div class="w-full max-w-[1720px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 h-20 flex items-center justify-between gap-4">
        
        <!-- Brand Logo & Heritage Tag -->
        <div class="flex items-center gap-3 sm:gap-4 flex-shrink-0">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <img src="/assets/logo-navy.png" alt="Kedia Builders & Colonizers" class="h-9 sm:h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105" onerror="this.src='/assets/logo-white.png'; this.classList.add('invert');">
            </a>
            <div class="hidden 2xl:flex items-center gap-2 pl-3 border-l border-[#0E1520]/20 text-[10px] font-sans font-semibold tracking-widest text-[#0E1520]/60 uppercase">
                <span>{{ __('EST. 1980') }}</span>
                <span class="inline-block w-1 h-1 rounded-full bg-[#0E1520]/40"></span>
                <span>{{ __('JAIPUR') }}</span>
            </div>
        </div>

        <!-- Navigation Links (Always single line, no wrapping) -->
        <nav class="hidden lg:flex items-center gap-5 xl:gap-8 text-sm font-medium text-[#0E1520] flex-shrink-0">
            <!-- Projects Dropdown -->
            <div class="relative group">
                <a href="{{ route('projects.index') }}" class="flex items-center gap-1.5 py-2 hover:text-[#C8A24B] transition-colors whitespace-nowrap">
                    <span>{{ __('messages.nav_projects') }}</span>
                    <svg class="w-3.5 h-3.5 text-[#0E1520]/50 group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <!-- Dropdown Menu -->
                <div class="absolute left-0 top-full mt-2 w-72 rounded-2xl bg-[#0E1520] text-white p-3 shadow-2xl border border-[#D4E845]/20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="text-[10px] font-mono tracking-widest uppercase text-[#D4E845] px-3 py-1.5">{{ __('Signature Townships') }}</div>
                    <a href="{{ route('projects.show', 'kedias-landmark') }}" class="block px-3 py-2 rounded-xl text-xs hover:bg-white/10 transition-colors">
                        <div class="font-semibold text-white">{{ __('Kedia\'s Landmark') }}</div>
                        <div class="text-[11px] text-white/50">{{ __('Ajmer Road · Villas & Plots') }}</div>
                    </a>
                    <a href="{{ route('projects.show', 'kedias-atulyam') }}" class="block px-3 py-2 rounded-xl text-xs hover:bg-white/10 transition-colors">
                        <div class="font-semibold text-white">{{ __('Kedia\'s Atulyam') }}</div>
                        <div class="text-[11px] text-white/50">{{ __('Sirsi Road · Smart Living') }}</div>
                    </a>
                    <a href="{{ route('projects.show', 'kedias-tulsi') }}" class="block px-3 py-2 rounded-xl text-xs hover:bg-white/10 transition-colors">
                        <div class="font-semibold text-white">{{ __('Kedia\'s Tulsi') }}</div>
                        <div class="text-[11px] text-white/50">{{ __('Bani Park · 3 & 4 BHK Luxury') }}</div>
                    </a>
                    <a href="{{ route('projects.show', 'ganpati-greens') }}" class="block px-3 py-2 rounded-xl text-xs hover:bg-white/10 transition-colors">
                        <div class="font-semibold text-white">{{ __('Ganpati Greens') }}</div>
                        <div class="text-[11px] text-white/50">{{ __('Eco-Friendly Villas & Enclave') }}</div>
                    </a>
                    <div class="my-1 border-t border-white/10"></div>
                    <a href="{{ route('projects.index') }}" class="block px-3 py-2 text-center text-xs font-semibold text-[#D4E845] hover:underline">
                        {{ __('View All 16 Projects →') }}
                    </a>
                </div>
            </div>

            <a href="{{ route('about') }}" class="hover:text-[#C8A24B] transition-colors whitespace-nowrap py-2">{{ __('messages.nav_about') }}</a>
            <a href="{{ route('gallery') }}" class="hover:text-[#C8A24B] transition-colors whitespace-nowrap py-2">{{ __('messages.nav_gallery') }}</a>
            <a href="{{ route('tools') }}" class="hover:text-[#C8A24B] transition-colors whitespace-nowrap py-2">{{ __('messages.nav_calculator') }}</a>
            <a href="{{ route('nri') }}" class="hover:text-[#C8A24B] transition-colors whitespace-nowrap py-2">{{ __('messages.nav_nri') }}</a>
            <a href="{{ route('contact') }}" class="hover:text-[#C8A24B] transition-colors whitespace-nowrap py-2">{{ __('messages.nav_contact') }}</a>
        </nav>

        <!-- Right Utilities & Action CTAs -->
        <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
            
            <!-- Language Switcher Toggle -->
            <div class="flex items-center bg-[#0E1520]/5 p-0.5 rounded-full border border-[#0E1520]/10 text-xs">
                <a href="{{ route('language.switch', 'en') }}" class="px-2.5 py-1 font-semibold rounded-full transition-all {{ $currentLocale === 'en' ? 'bg-[#0E1520] text-white shadow-sm' : 'text-[#0E1520]/60 hover:text-[#0E1520]' }}">
                    {{ __('EN') }}
                </a>
                <a href="{{ route('language.switch', 'hi') }}" class="px-2.5 py-1 font-semibold rounded-full transition-all {{ $currentLocale === 'hi' ? 'bg-[#0E1520] text-[#D4E845] shadow-sm' : 'text-[#0E1520]/60 hover:text-[#0E1520]' }}">
                    हिन्दी
                </a>
            </div>

            <!-- AI Concierge Modal Trigger -->
            <button type="button" onclick="openAiConciergeModal()" class="hidden md:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white border border-[#0E1520]/15 hover:border-[#0E1520] text-[#0E1520] shadow-sm hover:shadow transition-all whitespace-nowrap group">
                <span class="text-amber-500 group-hover:rotate-12 transition-transform">✨</span>
                <span>{{ __('AI Concierge') }}</span>
            </button>

            <!-- Shortlist Wishlist Button -->
            <button type="button" onclick="toggleShortlistDrawer()" class="relative p-2 rounded-full text-[#0E1520]/75 hover:text-rose-600 hover:bg-[#0E1520]/5 transition-colors" title="My Shortlist">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <span id="navShortlistCount" class="hidden absolute top-0.5 right-0.5 w-4 h-4 rounded-full bg-[#D4E845] text-[#0E1520] font-bold text-[9px] items-center justify-center font-sans">
                    0
                </span>
            </button>

            <!-- Customer Portal / Patron Link -->
            <a href="{{ route('customer.dashboard') }}" class="p-2 rounded-full text-[#0E1520]/75 hover:text-[#0E1520] hover:bg-[#0E1520]/5 transition-colors" title="Customer Dashboard &amp; Holding Status">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </a>

            <!-- Schedule a Visit Primary Button Desktop -->
            <button type="button" onclick="openSiteVisitModal()" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#D4E845] hover:bg-[#c2d733] text-[#0E1520] shadow-sm hover:shadow transition-all whitespace-nowrap">
                <svg class="w-3.5 h-3.5 text-[#0E1520]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ __('messages.btn_schedule_visit') }}</span>
            </button>

            <!-- Schedule Visit Icon Button Mobile -->
            <button type="button" onclick="openSiteVisitModal()" class="sm:hidden p-2 rounded-full bg-[#D4E845] text-[#0E1520] shadow-sm" title="Schedule Visit">
                <svg class="w-4 h-4 text-[#0E1520]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </button>

            <!-- Mobile Menu Toggle Button -->
            <button type="button" onclick="toggleMobileNav()" class="lg:hidden p-2 text-[#0E1520] hover:bg-black/5 rounded-lg" aria-label="Toggle Navigation">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer Navigation -->
    <div id="mobileNavDrawer" class="hidden lg:hidden bg-[#FAF8F5] border-b border-[#0E1520]/10 px-6 py-4 space-y-3">
        <a href="{{ route('home') }}" class="block text-sm font-semibold text-[#0E1520] py-1.5">{{ __('messages.nav_home') }}</a>
        <a href="{{ route('projects.index') }}" class="block text-sm font-semibold text-[#0E1520] py-1.5">{{ __('messages.nav_projects') }}</a>
        <a href="{{ route('about') }}" class="block text-sm font-semibold text-[#0E1520] py-1.5">{{ __('messages.nav_about') }}</a>
        <a href="{{ route('gallery') }}" class="block text-sm font-semibold text-[#0E1520] py-1.5">{{ __('messages.nav_gallery') }}</a>
        <a href="{{ route('tools') }}" class="block text-sm font-semibold text-[#0E1520] py-1.5">{{ __('messages.nav_calculator') }}</a>
        <a href="{{ route('nri') }}" class="block text-sm font-semibold text-[#0E1520] py-1.5">{{ __('messages.nav_nri') }}</a>
        <a href="{{ route('contact') }}" class="block text-sm font-semibold text-[#0E1520] py-1.5">{{ __('messages.nav_contact') }}</a>        <a href="{{ route('customer.dashboard') }}" class="block text-sm font-semibold text-blue-800 py-1.5">{{ __('PATRON DASHBOARD') }}</a>
    </div>
</header>

<script>
    function toggleMobileNav() {
        const drawer = document.getElementById('mobileNavDrawer');
        if (drawer) {
            drawer.classList.toggle('hidden');
        }
    }
</script>
