@php
    $company = config('kedia.company');
    $panel = 'no-print fixed inset-0 z-[70] bg-navy/60 opacity-0 backdrop-blur-sm transition-opacity duration-300 [&.is-open]:opacity-100';
    $closeBtn = 'grid h-10 w-10 place-items-center rounded-full text-current/60 transition hover:bg-black/5 hover:text-terracotta';
    $locations = \App\Models\Project::query()->distinct()->orderBy('location')->pluck('location');
@endphp

{{-- Mobile navigation --}}
<div id="mobileNav" data-panel hidden aria-hidden="true" role="dialog" aria-modal="true" aria-label="{{ __('Menu') }}" class="{{ $panel }}">
    <div class="ml-auto flex h-full w-full max-w-sm flex-col overflow-y-auto bg-navy p-6 text-paper">
        <div class="flex items-center justify-between">
            <img src="/assets/logo-white.png" alt="Kedia Builders & Colonizers" class="h-9 w-auto">
            <button type="button" data-close class="{{ $closeBtn }} text-paper" aria-label="{{ __('Close') }}">&times;</button>
        </div>
        <nav class="mt-8 space-y-1 font-serif text-2xl" aria-label="{{ __('Mobile') }}">
            @foreach([['home', __('Home')], ['projects.index', __('Projects')], ['about', __('About')], ['about.why', __('Why Invest')], ['gallery', __('Gallery')], ['tools', __('Tools')], ['nri', __('NRI Corner')], ['careers', __('Careers')], ['contact', __('Contact')], ['account', __('My account')]] as [$r, $label])
                <a href="{{ route($r) }}" class="block border-b border-white/5 py-3 transition hover:text-brass">{{ $label }}</a>
            @endforeach
        </nav>
        <div class="mt-6 flex items-center gap-2 text-xs font-semibold">
            <a href="{{ route('language.switch', 'en') }}" class="rounded-full border border-white/15 px-3 py-1.5" lang="en">EN</a>
            <a href="{{ route('language.switch', 'hi') }}" class="rounded-full border border-white/15 px-3 py-1.5" lang="hi">हिन्दी</a>
        </div>
        <button type="button" data-open="visitModal" class="btn-brass mt-auto w-full">{{ __('Book a Visit') }}</button>
    </div>
</div>

{{-- Global search --}}
<div id="searchPanel" data-panel hidden aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="searchPanelTitle" class="{{ $panel }} !bg-navy/90">
    <div class="container-site pt-24">
        <div class="mx-auto max-w-2xl text-paper">
            <div class="flex items-center justify-between">
                <h2 id="searchPanelTitle" class="eyebrow">{{ __('Search projects, locations & unit codes') }}</h2>
                <button type="button" data-close class="{{ $closeBtn }} text-paper" aria-label="{{ __('Close') }}">&times;</button>
            </div>
            <form action="{{ route('projects.index') }}" method="GET" class="mt-4">
                <label for="globalSearchInput" class="sr-only">{{ __('Search') }}</label>
                <input id="globalSearchInput" name="q" type="search" autocomplete="off" autofocus placeholder="{{ __('e.g. Ajmer Road, villa, KED-Plot12') }}" class="w-full border-b border-brass/40 bg-transparent pb-4 font-serif text-3xl text-paper placeholder:text-paper/30 focus:border-brass focus:outline-none sm:text-4xl">
            </form>
            <div id="globalSearchResults" class="mt-4 max-h-[55vh] overflow-y-auto" aria-live="polite"></div>
            <p class="label mt-6 text-paper/30">{{ __('Tip: press / anywhere to search') }}</p>
        </div>
    </div>
</div>

{{-- Shortlist (wishlist) drawer --}}
<div id="wishlistDrawer" data-panel hidden aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="wishlistTitle" class="{{ $panel }}">
    <div class="ml-auto flex h-full w-full max-w-md flex-col bg-paper text-ink shadow-2xl">
        <div class="flex items-center justify-between border-b border-navy/10 px-6 py-5">
            <div>
                <div class="eyebrow">{{ __('Saved') }}</div>
                <h2 id="wishlistTitle" class="font-serif text-2xl text-navy">{{ __('Your shortlist') }}</h2>
            </div>
            <button type="button" data-close class="{{ $closeBtn }}" aria-label="{{ __('Close') }}">&times;</button>
        </div>
        <div id="wishlistItems" class="flex-1 space-y-3 overflow-y-auto p-6"></div>
        <div class="space-y-2 border-t border-navy/10 p-6">
            <button type="button" data-open="visitModal" class="btn-navy w-full">{{ __('Schedule a visit for my shortlist') }}</button>
            <a href="{{ route('compare') }}" class="btn-ghost w-full">{{ __('Compare units') }}</a>
        </div>
    </div>
</div>

{{-- Book a visit --}}
<div id="visitModal" data-panel hidden aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="visitTitle" class="{{ $panel }} grid place-items-center p-4">
    <div class="relative w-full max-w-lg overflow-hidden rounded-[2rem] bg-navy text-paper shadow-2xl">
        <div class="jaali-panel absolute inset-0 opacity-60" aria-hidden="true"></div>
        <div class="relative p-6 sm:p-8">
            <div class="flex items-start justify-between">
                <div>
                    <div class="eyebrow">{{ __('Private site experience') }}</div>
                    <h2 id="visitTitle" class="mt-1 font-serif text-3xl">{{ __('Book a visit') }}</h2>
                    <p class="mt-1 text-sm text-paper/60">{{ __('Pick a project, date and slot. A relationship manager confirms within a few hours.') }}</p>
                </div>
                <button type="button" data-close class="{{ $closeBtn }} text-paper" aria-label="{{ __('Close') }}">&times;</button>
            </div>
            <form action="{{ route('leads.store') }}" method="POST" class="mt-6 grid grid-cols-2 gap-3">
                @csrf
                <input type="hidden" name="source" value="book_a_visit">
                <div class="col-span-2">
                    <label for="visit_name" class="label mb-1.5 block text-paper/60">{{ __('Full name') }} *</label>
                    <input id="visit_name" name="name" required autocomplete="name" class="field-dark">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label for="visit_phone" class="label mb-1.5 block text-paper/60">{{ __('Mobile / WhatsApp') }} *</label>
                    <input id="visit_phone" name="phone" type="tel" required autocomplete="tel" class="field-dark">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label for="visit_project" class="label mb-1.5 block text-paper/60">{{ __('Project') }}</label>
                    <select id="visit_project" name="project_name" class="field-dark [&>option]:text-ink">
                        @foreach($navProjects as $p)
                            <option value="{{ $p->name }}">{{ $p->name }}</option>
                        @endforeach
                        <option value="General Inquiry">{{ __('Not sure yet') }}</option>
                    </select>
                </div>
                <div>
                    <label for="visit_date" class="label mb-1.5 block text-paper/60">{{ __('Date') }}</label>
                    <input id="visit_date" name="preferred_date" type="date" min="{{ now()->toDateString() }}" value="{{ now()->addDay()->toDateString() }}" class="field-dark [color-scheme:dark]">
                </div>
                <div>
                    <label for="visit_slot" class="label mb-1.5 block text-paper/60">{{ __('Slot') }}</label>
                    <select id="visit_slot" name="time_slot" class="field-dark [&>option]:text-ink">
                        @foreach(['10:00 AM - 12:00 PM', '12:00 PM - 02:00 PM', '03:00 PM - 05:00 PM'] as $slot)
                            <option value="{{ $slot }}">{{ $slot }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="col-span-2 flex items-center gap-2 text-sm text-paper/80">
                    <input type="checkbox" name="pickup_requested" value="1" class="h-4 w-4 rounded accent-brass">
                    {{ __('Arrange a chauffeured pickup within Jaipur') }}
                </label>
                <button type="submit" class="btn-brass col-span-2 mt-2">{{ __('Confirm visit request') }}</button>
            </form>
        </div>
    </div>
</div>

{{-- AI assistant --}}
<div id="assistantPanel" data-panel hidden aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="assistantTitle" class="{{ $panel }} flex items-end justify-end p-4">
    <div class="w-full max-w-md overflow-hidden rounded-[2rem] bg-navy text-paper shadow-2xl ring-1 ring-brass/30">
        <div class="flex items-start justify-between border-b border-white/10 p-6">
            <div>
                <div class="eyebrow">{{ __('Kedia assistant') }}</div>
                <h2 id="assistantTitle" class="mt-1 font-serif text-2xl">{{ __('Find your home in Jaipur') }}</h2>
                <p class="mt-1 text-xs text-paper/50">{{ __('Recommendations come from live inventory.') }}</p>
            </div>
            <button type="button" data-close class="{{ $closeBtn }} text-paper" aria-label="{{ __('Close') }}">&times;</button>
        </div>
        <form id="assistantForm" class="space-y-4 p-6">
            <fieldset>
                <legend class="label mb-2 text-paper/60">{{ __('Budget') }}</legend>
                <div class="grid grid-cols-3 gap-2 text-xs">
                    @foreach(['0-4000000' => __('Under ₹40 L'), '4000000-8000000' => __('₹40–80 L'), '8000000-999999999' => __('₹80 L +')] as $value => $label)
                        <label class="cursor-pointer rounded-xl border border-white/10 px-2 py-2.5 text-center has-[:checked]:border-brass has-[:checked]:text-brass">
                            <input type="radio" name="budget" value="{{ $value }}" class="sr-only" @checked($loop->first)>{{ $label }}
                        </label>
                    @endforeach
                </div>
            </fieldset>
            <fieldset>
                <legend class="label mb-2 text-paper/60">{{ __('Looking for') }}</legend>
                <div class="grid grid-cols-3 gap-2 text-xs">
                    @foreach(['Plot' => __('Plot'), 'Villa' => __('Villa'), 'Apartment' => __('Apartment')] as $value => $label)
                        <label class="cursor-pointer rounded-xl border border-white/10 px-2 py-2.5 text-center has-[:checked]:border-brass has-[:checked]:text-brass">
                            <input type="radio" name="type" value="{{ $value }}" class="sr-only" @checked($loop->first)>{{ $label }}
                        </label>
                    @endforeach
                </div>
            </fieldset>
            <div>
                <label for="assistant_location" class="label mb-2 block text-paper/60">{{ __('Area of Jaipur') }}</label>
                <select id="assistant_location" name="location" class="field-dark [&>option]:text-ink">
                    <option value="">{{ __('Anywhere in Jaipur') }}</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc }}">{{ $loc }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-brass w-full">{{ __('Show my matches') }}</button>
            <div id="assistantResults" aria-live="polite"></div>
        </form>
    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox" data-panel hidden aria-hidden="true" role="dialog" aria-modal="true" aria-label="{{ __('Gallery viewer') }}" class="{{ $panel }} !bg-navy/95">
    <div class="flex h-full flex-col items-center justify-center p-4">
        <button type="button" data-close class="absolute right-4 top-4 grid h-12 w-12 place-items-center rounded-full text-2xl text-paper/70 hover:text-paper" aria-label="{{ __('Close') }}">&times;</button>
        <div id="lightboxStage" class="flex max-h-[80vh] items-center justify-center"></div>
        <p id="lightboxCaption" class="mt-4 text-center text-sm text-paper/70"></p>
        <div class="mt-4 flex gap-3">
            <button type="button" id="lightboxPrev" class="btn-ghost-light btn-sm" aria-label="{{ __('Previous') }}">←</button>
            <button type="button" id="lightboxNext" class="btn-ghost-light btn-sm" aria-label="{{ __('Next') }}">→</button>
        </div>
    </div>
</div>

{{-- Compare tray --}}
<div id="compareTray" hidden class="no-print fixed inset-x-0 bottom-0 z-40 border-t border-brass/30 bg-navy/95 text-paper backdrop-blur">
    <div class="container-site flex flex-wrap items-center gap-3 py-3">
        <span class="label text-brass">{{ __('Compare') }}</span>
        <div id="compareSlots" class="flex flex-1 flex-wrap items-center gap-2"></div>
        <a id="compareGo" href="{{ route('compare') }}" class="btn-brass btn-sm">{{ __('Compare now') }}</a>
    </div>
</div>

{{-- Floating dock: AI assistant + WhatsApp --}}
<div id="floatingDock" class="no-print fixed bottom-5 right-4 z-40 flex flex-col items-end gap-3 sm:right-6">
    <button type="button" data-open="assistantPanel" class="group flex items-center gap-2 rounded-full bg-navy py-2.5 pl-3 pr-4 text-xs font-semibold text-paper shadow-xl ring-1 ring-brass/40 transition hover:-translate-y-0.5">
        <span class="grid h-7 w-7 place-items-center rounded-full bg-brass text-navy" aria-hidden="true">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l1.8 4.6L18 9l-4.2 1.4L12 15l-1.8-4.6L6 9l4.2-1.4z"/><path d="M18 15l.8 2 2 .8-2 .8-.8 2-.8-2-2-.8 2-.8z"/></svg>
        </span>
        <span class="hidden sm:inline">{{ __('Ask our assistant') }}</span>
    </button>
    <a href="https://wa.me/{{ $company['whatsapp_href'] }}?text={{ rawurlencode('Hello Kedia Builders, I would like to know more about your Jaipur projects.') }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-full bg-[#25D366] py-2.5 pl-3 pr-4 text-xs font-semibold text-white shadow-xl transition hover:-translate-y-0.5" aria-label="{{ __('Chat on WhatsApp') }} {{ $company['whatsapp'] }}">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 00-8.6 15.1L2 22l5-1.3A10 10 0 1012 2zm0 18.2a8.2 8.2 0 01-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1112 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8s-.4-.1-.6.1-.7.8-.8 1-.3.2-.5.1a6.7 6.7 0 01-3.3-2.9c-.3-.4.3-.4.7-1.3a.5.5 0 000-.5l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 00-.7.3 3 3 0 00-.9 2.2 5.2 5.2 0 001.1 2.7 11.8 11.8 0 004.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 001.7-1.2 2.2 2.2 0 00.2-1.2c-.1-.1-.2-.2-.5-.3z"/></svg>
        <span class="hidden sm:inline">WhatsApp</span>
    </a>
</div>
