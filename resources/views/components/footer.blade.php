@php
    $company = config('kedia.company');
    $latest = \App\Models\Project::where('status', 'ongoing')->orderBy('sort_order')->take(5)->get(['slug', 'name', 'location', 'rera_number']);
@endphp
<footer class="section-navy overflow-hidden pt-20">
    <div class="blueprint-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="container-site relative">
        <div class="grid gap-12 pb-14 md:grid-cols-2 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <a href="{{ route('home') }}" class="inline-block" aria-label="{{ __('Home') }}">
                    <img src="{{ asset('assets/logo-white.png') }}" alt="Kedia Builders & Colonizers" class="h-12 w-auto" loading="lazy">
                </a>
                <p class="mt-6 max-w-sm font-serif text-xl italic leading-snug text-paper/80">“{{ __($company['tagline']) }}”</p>
                <div class="mt-6 flex flex-wrap gap-2">
                    <span class="chip border-paper/15 text-paper/70">{{ __('Est.') }} {{ $company['founded'] }}</span>
                    <span class="chip border-paper/15 text-paper/70">{{ __('200+ projects') }}</span>
                    <span class="chip border-paper/15 text-paper/70">{{ __('JDA approved') }}</span>
                </div>
                <div class="mt-6 flex gap-2">
                    @foreach(config('kedia.socials') as $social)
                        <a href="{{ $social['url'] }}" target="_blank" rel="noopener" class="rounded-full border border-paper/15 px-3.5 py-2 text-xs text-paper/70 transition hover:border-brass hover:text-brass" aria-label="{{ $social['label'] }} {{ $social['handle'] }}">{{ $social['label'] }}</a>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-2">
                <h2 class="eyebrow">{{ __('Explore') }}</h2>
                <ul class="mt-5 space-y-2.5 text-sm text-paper/70">
                    @foreach([['projects.index', __('All projects')], ['about', __('Our story')], ['about.why', __('Why invest')], ['gallery', __('Gallery')], ['tools', __('EMI & price tools')], ['nri', __('NRI corner')], ['careers', __('Careers')], ['account', __('My account')]] as [$r, $label])
                        <li><a href="{{ route($r) }}" class="transition hover:text-brass">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="lg:col-span-3">
                <h2 class="eyebrow">{{ __('Latest projects') }}</h2>
                <ul class="mt-5 space-y-3 text-sm">
                    @foreach($latest as $p)
                        <li>
                            <a href="{{ route('projects.show', $p->slug) }}" class="group block">
                                <span class="text-paper/85 transition group-hover:text-brass">{{ $p->name }}</span>
                                <span class="label block text-paper/35">RERA {{ $p->rera_number }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="lg:col-span-3">
                <h2 class="eyebrow">{{ __('Contact') }}</h2>
                <address class="mt-5 space-y-3 text-sm not-italic text-paper/70">
                    <p>{{ __($company['address']) }}</p>
                    <p><a href="tel:{{ $company['phone_href'] }}" class="font-serif text-2xl text-paper transition hover:text-brass">{{ $company['phone'] }}</a></p>
                    <p><a href="https://wa.me/{{ $company['whatsapp_href'] }}" target="_blank" rel="noopener" class="hover:text-brass">WhatsApp {{ $company['whatsapp'] }}</a></p>
                    <p><a href="mailto:{{ $company['email'] }}" class="break-all hover:text-brass">{{ $company['email'] }}</a></p>
                    <p class="label text-paper/40">{{ __($company['hours']) }}</p>
                </address>
            </div>
        </div>

        <div class="jaali opacity-40" aria-hidden="true"></div>

        <div class="grid gap-6 py-8 text-xs text-paper/50 lg:grid-cols-2">
            <p class="max-w-xl leading-relaxed">
                {{ __('All Kedia townships are JDA approved. RAJ-RERA registration numbers are shown on every project page; verify them at rera.rajasthan.gov.in. Prices, offers and availability are indicative and subject to change.') }}
            </p>
            <div class="flex flex-wrap items-start gap-x-6 gap-y-2 lg:justify-end">
                <a href="{{ route('legal.privacy') }}" class="hover:text-paper">{{ __('Privacy Policy') }}</a>
                <a href="{{ route('legal.terms') }}" class="hover:text-paper">{{ __('Terms of Use') }}</a>
                <a href="{{ route('legal.refund') }}" class="hover:text-paper">{{ __('Refund & Booking Policy') }}</a>
                <a href="{{ route('legal') }}" class="hover:text-paper">{{ __('RERA disclosures') }}</a>
                <a href="{{ route('sitemap') }}" class="hover:text-paper">{{ __('Sitemap') }}</a>
            </div>
        </div>
        <div class="border-t border-white/5 py-6 text-xs text-paper/40">
            &copy; {{ date('Y') }} {{ $company['name'] }}. {{ __('All rights reserved.') }}
        </div>
    </div>
</footer>
