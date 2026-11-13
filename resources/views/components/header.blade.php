@php
    $locale = app()->getLocale();
    $aboutLinks = [
        ['route' => 'about', 'label' => __('Our Story')],
        ['route' => 'about.chairman', 'label' => __('Chairman & Leadership')],
        ['route' => 'about.vision', 'label' => __('Vision & Mission')],
        ['route' => 'about.why', 'label' => __('Why Invest')],
        ['route' => 'careers', 'label' => __('Careers')],
    ];
    $mainLinks = [
        ['route' => 'gallery', 'label' => __('Gallery')],
        ['route' => 'tools', 'label' => __('Tools')],
        ['route' => 'nri', 'label' => __('NRI Corner')],
        ['route' => 'contact', 'label' => __('Contact')],
    ];
    $navLink = 'relative py-2 text-[13px] font-medium text-paper/80 transition hover:text-paper after:absolute after:-bottom-0.5 after:left-0 after:h-px after:w-0 after:bg-brass after:transition-all hover:after:w-full';
    $iconBtn = 'relative grid h-10 w-10 place-items-center rounded-full text-paper/80 transition hover:bg-white/5 hover:text-paper';
@endphp
<header id="siteHeader" class="group/header sticky top-0 z-50 border-b border-white/5 bg-navy/95 text-paper backdrop-blur-md transition-shadow [&.is-scrolled]:shadow-[0_10px_40px_-20px_rgba(0,0,0,0.6)]">
    <div class="container-site flex h-[76px] items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex flex-none items-center" aria-label="{{ __('Kedia Builders & Colonizers — home') }}">
            <img src="{{ asset('assets/logo-white.png') }}" alt="Kedia Builders & Colonizers" class="h-10 w-auto sm:h-11" width="160" height="44">
        </a>

        <nav class="hidden items-center gap-6 xl:flex" aria-label="{{ __('Main') }}">
            <div class="group/menu relative">
                <a href="{{ route('projects.index') }}" class="{{ $navLink }} inline-flex items-center gap-1" aria-haspopup="true">
                    {{ __('Projects') }}
                    <svg class="h-3 w-3 opacity-60 transition group-hover/menu:rotate-180" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M3 4.5l3 3 3-3"/></svg>
                </a>
                <div class="invisible absolute left-1/2 top-full w-[34rem] -translate-x-1/2 pt-4 opacity-0 transition duration-200 group-hover/menu:visible group-hover/menu:opacity-100 group-focus-within/menu:visible group-focus-within/menu:opacity-100">
                    <div class="grid grid-cols-2 gap-1 rounded-3xl border border-brass/20 bg-navy p-3 shadow-2xl">
                        @foreach($navProjects as $p)
                            <a href="{{ route('projects.show', $p->slug) }}" class="rounded-2xl px-4 py-3 transition hover:bg-white/5">
                                <div class="font-serif text-base text-paper">{{ $p->name }}</div>
                                <div class="label mt-0.5 text-paper/40">{{ $p->location }} · {{ $p->status === 'completed' ? __('Completed') : __('Ongoing') }}</div>
                            </a>
                        @endforeach
                        <a href="{{ route('projects.index') }}" class="col-span-2 mt-1 rounded-2xl border border-brass/30 px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.14em] text-brass hover:bg-brass hover:text-navy">{{ __('Browse all projects & units') }} →</a>
                    </div>
                </div>
            </div>
            <div class="group/menu relative">
                <a href="{{ route('about') }}" class="{{ $navLink }} inline-flex items-center gap-1" aria-haspopup="true">
                    {{ __('About') }}
                    <svg class="h-3 w-3 opacity-60 transition group-hover/menu:rotate-180" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M3 4.5l3 3 3-3"/></svg>
                </a>
                <div class="invisible absolute left-0 top-full w-60 pt-4 opacity-0 transition duration-200 group-hover/menu:visible group-hover/menu:opacity-100 group-focus-within/menu:visible group-focus-within/menu:opacity-100">
                    <div class="rounded-3xl border border-brass/20 bg-navy p-2 shadow-2xl">
                        @foreach($aboutLinks as $link)
                            <a href="{{ route($link['route']) }}" class="block rounded-2xl px-4 py-2.5 text-sm text-paper/80 transition hover:bg-white/5 hover:text-paper">{{ $link['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            @foreach($mainLinks as $link)
                <a href="{{ route($link['route']) }}" class="{{ $navLink }} {{ request()->routeIs($link['route']) ? 'text-paper after:w-full' : '' }}">{{ $link['label'] }}</a>
            @endforeach
        </nav>

        <div class="flex flex-none items-center gap-0.5 sm:gap-1">
            <button type="button" class="{{ $iconBtn }}" data-open="searchPanel" aria-label="{{ __('Search projects and units') }}">
                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="M20 20l-4.2-4.2"/></svg>
            </button>
            <button type="button" class="{{ $iconBtn }}" data-open="wishlistDrawer" aria-label="{{ __('Shortlist') }}">
                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M12 20s-7-4.4-7-10a4 4 0 017-2.6A4 4 0 0119 10c0 5.6-7 10-7 10z"/></svg>
                <span data-wishlist-count hidden class="absolute right-0.5 top-0.5 grid h-4 min-w-4 place-items-center rounded-full bg-brass px-1 font-mono text-[9px] font-bold text-navy">0</span>
            </button>
            <a href="{{ route('compare') }}" class="{{ $iconBtn }} hidden sm:grid" aria-label="{{ __('Compare units') }}">
                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M7 4v16M17 4v16M3 8h8M13 16h8"/></svg>
                <span data-compare-count hidden class="absolute right-0.5 top-0.5 grid h-4 min-w-4 place-items-center rounded-full bg-brass px-1 font-mono text-[9px] font-bold text-navy">0</span>
            </a>
            <a href="{{ route('account') }}" class="{{ $iconBtn }}" aria-label="{{ __('My account') }}">
                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20a7.5 7.5 0 0115 0"/></svg>
                @if(session('customer_phone'))<span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-status-available ring-2 ring-navy"></span>@endif
            </a>
            <div class="ml-1 hidden items-center rounded-full border border-white/10 p-0.5 text-[11px] font-semibold md:flex" role="group" aria-label="{{ __('Language') }}">
                <a href="{{ route('language.switch', 'en') }}" class="rounded-full px-2.5 py-1 {{ $locale === 'en' ? 'bg-paper text-navy' : 'text-paper/60 hover:text-paper' }}" @if($locale === 'en') aria-current="true" @endif lang="en">EN</a>
                <a href="{{ route('language.switch', 'hi') }}" class="rounded-full px-2.5 py-1 {{ $locale === 'hi' ? 'bg-paper text-navy' : 'text-paper/60 hover:text-paper' }}" @if($locale === 'hi') aria-current="true" @endif lang="hi">हिन्दी</a>
            </div>
            <button type="button" data-open="visitModal" class="btn-brass ml-2 hidden !py-2.5 lg:inline-flex">{{ __('Book a Visit') }}</button>
            <button type="button" data-open="mobileNav" class="{{ $iconBtn }} xl:hidden" aria-label="{{ __('Open menu') }}">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
            </button>
        </div>
    </div>
</header>
