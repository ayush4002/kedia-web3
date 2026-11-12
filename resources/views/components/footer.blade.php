<footer class="bg-[#0E1520] text-white pt-20 pb-12 border-t border-[#D4E845]/15 relative overflow-hidden">
    <!-- Faint jaali / blueprint grid overlay -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#D4E845 1px, transparent 1px); background-size: 28px 28px;"></div>

    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 pb-16 border-b border-white/10">
            
            <!-- Column 1 & 2: Brand & Corporate Statement -->
            <div class="lg:col-span-2 space-y-6">
                <a href="{{ route('home') }}" class="inline-block">
                    <img loading="lazy" decoding="async" src="/assets/logo-white.png" alt="Kedia Builders & Colonizers" class="h-12 w-auto object-contain" onerror="this.src='/assets/logo-navy.png'; this.classList.add('brightness-0', 'invert');">
                </a>
                <p class="text-sm text-white/70 max-w-md leading-relaxed">
                    {{ __('messages.footer_about') }}
                </p>
                <div class="flex flex-wrap gap-3 font-mono text-[11px] text-[#D4E845]">
                    <span class="px-2.5 py-1 rounded-md bg-white/5 border border-white/10">{{ __('EST. 1980') }}</span>
                    <span class="px-2.5 py-1 rounded-md bg-white/5 border border-white/10">{{ __('200+ LAND DEVELOPMENTS') }}</span>
                    <span class="px-2.5 py-1 rounded-md bg-white/5 border border-white/10">{{ __('100% JDA APPROVED') }}</span>
                    <span class="px-2.5 py-1 rounded-md bg-white/5 border border-white/10">{{ __('RAJ-RERA SANCTIONED') }}</span>
                </div>
            </div>

            <!-- Column 3: Featured Projects -->
            <div class="space-y-4">
                <div class="text-xs font-mono uppercase tracking-widest text-[#D4E845] font-semibold">{{ __('Townships & Enclaves') }}</div>
                <ul class="space-y-2.5 text-sm text-white/70">
                    <li><a href="{{ route('projects.show', 'kedias-landmark') }}" class="hover:text-white transition-colors">{{ __('Kedia\'s Landmark') }}</a></li>
                    <li><a href="{{ route('projects.show', 'kedias-atulyam') }}" class="hover:text-white transition-colors">{{ __('Kedia\'s Atulyam') }}</a></li>
                    <li><a href="{{ route('projects.show', 'kedias-tulsi') }}" class="hover:text-white transition-colors">{{ __('Kedia\'s Tulsi (Bani Park)') }}</a></li>
                    <li><a href="{{ route('projects.show', 'ganpati-greens') }}" class="hover:text-white transition-colors">{{ __('Ganpati Greens') }}</a></li>
                    <li><a href="{{ route('projects.show', 'nikunj-villas') }}" class="hover:text-white transition-colors">{{ __('Nikunj Villas') }}</a></li>
                    <li><a href="{{ route('projects.show', 'kedia-aravali') }}" class="hover:text-white transition-colors">{{ __('Kedia Aravali Plots') }}</a></li>
                    <li><a href="{{ route('projects.index') }}" class="text-[#D4E845] font-semibold hover:underline block pt-1">{{ __('All 16 Projects →') }}</a></li>
                </ul>
            </div>

            <!-- Column 4: Architectural Navigation -->
            <div class="space-y-4">
                <div class="text-xs font-mono uppercase tracking-widest text-[#D4E845] font-semibold">{{ __('Exploration') }}</div>
                <ul class="space-y-2.5 text-sm text-white/70">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">{{ __('messages.nav_about') }}</a></li>
                    <li><a href="{{ route('gallery') }}" class="hover:text-white transition-colors">{{ __('messages.nav_gallery') }}</a></li>
                    <li><a href="{{ route('tools') }}" class="hover:text-white transition-colors">{{ __('messages.nav_calculator') }}</a></li>
                    <li><a href="{{ route('nri') }}" class="hover:text-white transition-colors">{{ __('messages.nav_nri') }}</a></li>
                    <li><a href="{{ route('careers') }}" class="hover:text-white transition-colors">{{ __('Careers & Internships') }}</a></li>
                    <li><a href="{{ route('customer.dashboard') }}" class="hover:text-white transition-colors">{{ __('Customer Portal') }}</a></li>                </ul>
            </div>

            <!-- Column 5: Headquarters & Direct Line -->
            <div class="space-y-4">
                <div class="text-xs font-mono uppercase tracking-widest text-[#D4E845] font-semibold">{{ __('Jaipur Headquarters') }}</div>
                <div class="text-sm text-white/70 space-y-3">
                    <p class="leading-relaxed">
                        {{ __('Kedia Builders & Colonizers') }}<br>
                        {{ __('Corporate Office: Sikar Road, Bani Park & Vaishali Nagar, Jaipur, Rajasthan 302016') }}
                    </p>
                    <div class="pt-2">
                        <div class="text-[11px] font-mono text-white/40 uppercase">{{ __('Direct Booking Desk:') }}</div>
                        <a href="tel:+918302500500" class="text-base font-bold text-white hover:text-[#D4E845] block">+91 8302 500 500</a>
                    </div>
                    <div>
                        <div class="text-[11px] font-mono text-white/40 uppercase">{{ __('WhatsApp Inquiry:') }}</div>
                        <a href="https://wa.me/919829184872" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-[#D4E845] hover:underline flex items-center gap-1.5">
                            <span>+91 98291 84872</span>
                        </a>
                    </div>
                    <div>
                        <div class="text-[11px] font-mono text-white/40 uppercase">{{ __('Corporate Email:') }}</div>
                        <a href="mailto:kediabuildersandcolonizers@gmail.com" class="text-xs text-white/70 hover:text-white break-all">kediabuildersandcolonizers@gmail.com</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Certification & Copyright -->
        <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-white/50">
            <div class="text-center md:text-left">
                &copy; {{ date('Y') }} Kedia Builders & Colonizers. {{ __('messages.all_rights_reserved') }}
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('legal') }}" class="hover:text-white transition-colors">{{ __('RERA Compliance') }}</a>
                <a href="{{ route('legal') }}" class="hover:text-white transition-colors">{{ __('Privacy Policy') }}</a>
                <a href="{{ route('legal') }}" class="hover:text-white transition-colors">{{ __('Terms of Booking') }}</a>
                <a href="{{ route('contact') }}" class="hover:text-white transition-colors">{{ __('Site Map') }}</a>
            </div>
        </div>

    </div>
</footer>
