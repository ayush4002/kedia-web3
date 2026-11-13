@extends('layouts.app')

@section('title', 'NRI Corner · Invest in Jaipur from Abroad | Kedia Builders & Colonizers')
@section('meta_description', 'NRI desk for buying plots and villas in Jaipur: FEMA-compliant NRE/NRO payments, video walkthroughs, power of attorney support and a currency converter.')

@php $fx = config('kedia.fx'); @endphp

@section('content')
<x-page-hero :eyebrow="__('NRI corner')" :title="__('Your Jaipur home, from anywhere')" :crumb="__('NRI corner')" image="/media/img/2025/04/DSC07902-1920.webp">
    {{ __('Video walkthroughs, FEMA-compliant payments and a dedicated advisor who works in your time zone.') }}
</x-page-hero>

<section class="section-paper py-20">
    <div class="container-site grid gap-6 md:grid-cols-3">
        @foreach([
            [__('Clear title & JDA patta'), __('Freehold plots and villas with individual JDA patta and a clean title history.')],
            [__('NRE / NRO payments'), __('Pay through your NRE or NRO account; we share the documents banks need for repatriation.')],
            [__('Live video tours'), __('Drone progress films and private video calls from the site with your advisor.')],
        ] as $i => [$title, $text])
            <div class="card" data-reveal data-reveal-delay="{{ $i * 80 }}">
                <div class="font-mono text-xs text-brass">0{{ $i + 1 }}</div>
                <h2 class="mt-3 font-serif text-2xl text-navy">{{ $title }}</h2>
                <p class="mt-2 text-sm text-ink/70">{{ $text }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="section-paper pb-24">
    <div class="container-site grid gap-10 lg:grid-cols-12">
        {{-- Currency converter --}}
        <div class="rounded-[1.5rem] bg-navy p-6 text-paper sm:p-8 lg:col-span-5" data-fx data-rates="{{ json_encode($fx['rates']) }}">
            <div class="flex items-center justify-between gap-3">
                <h2 class="font-serif text-3xl">{{ __('Currency converter') }}</h2>
                @if(!empty($fx['placeholder']))<x-sample-tag class="!border-paper/40 !text-paper/70" />@endif
            </div>
            <p class="mt-1 text-xs text-paper/50">{{ __($fx['as_of']) }}. {{ __('Use your bank\'s rate on the day you pay.') }}</p>
            <div class="mt-6 grid grid-cols-[minmax(0,1fr)_7rem] gap-3">
                <div>
                    <label for="fx_amount" class="label mb-1.5 block text-paper/60">{{ __('Property price (INR)') }}</label>
                    <input id="fx_amount" type="number" min="0" step="100000" value="5000000" inputmode="numeric" class="field-dark font-mono">
                </div>
                <div>
                    <label for="fx_currency" class="label mb-1.5 block text-paper/60">{{ __('Currency') }}</label>
                    <select id="fx_currency" class="field-dark [&>option]:text-ink">
                        @foreach(array_keys($fx['rates']) as $cur)<option value="{{ $cur }}">{{ $cur }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6 border-t border-paper/10 pt-5" aria-live="polite">
                <div class="label text-paper/50">{{ __('Approximately') }}</div>
                <div class="font-serif text-5xl text-brass" data-fx-out>—</div>
                <div class="mt-2 text-sm text-paper/60">{{ __('5% online token') }}: <span class="font-mono" data-fx-token>—</span></div>
            </div>
        </div>

        {{-- Enquiry flow --}}
        <form action="{{ route('leads.store') }}" method="POST" class="space-y-5 rounded-[1.5rem] border border-navy/10 bg-paper-50 p-6 sm:p-8 lg:col-span-7">
            @csrf
            <input type="hidden" name="source" value="nri_desk">
            <h2 class="font-serif text-3xl text-navy">{{ __('Talk to the NRI desk') }}</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                <div><label for="nri_name" class="field-label">{{ __('Full name') }} *</label><input id="nri_name" name="name" required autocomplete="name" class="field"></div>
                <div><label for="nri_phone" class="field-label">{{ __('WhatsApp number (with country code)') }} *</label><input id="nri_phone" name="phone" type="tel" required autocomplete="tel" placeholder="+971 50 000 0000" class="field"></div>
                <div><label for="nri_email" class="field-label">{{ __('Email') }}</label><input id="nri_email" name="email" type="email" autocomplete="email" class="field"></div>
                <div>
                    <label for="nri_project" class="field-label">{{ __('Project of interest') }}</label>
                    <select id="nri_project" name="project_name" class="field">
                        <option value="General Inquiry">{{ __('Not sure yet') }}</option>
                        @foreach($projects as $p)<option value="{{ $p->name }}">{{ $p->name }} · {{ $p->location }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div><label for="nri_msg" class="field-label">{{ __('Country, budget and best time to call') }}</label><textarea id="nri_msg" name="message" rows="3" maxlength="500" class="field" placeholder="{{ __('e.g. Dubai, ₹60–80 L, evenings IST') }}"></textarea></div>
            <button type="submit" class="btn-navy">{{ __('Request a call back') }}</button>
            <p class="text-xs text-ink/50">{{ __('Information only; not legal or tax advice. Your advisor will share the current FEMA and RBI requirements.') }}</p>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    const box = document.querySelector('[data-fx]');
    if (!box) return;
    const rates = JSON.parse(box.dataset.rates);
    const amount = document.getElementById('fx_amount');
    const cur = document.getElementById('fx_currency');
    const run = () => {
        const inr = Math.max(0, parseFloat(amount.value) || 0);
        const rate = rates[cur.value];
        const fmt = (n) => new Intl.NumberFormat('en', { style: 'currency', currency: cur.value, maximumFractionDigits: 0 }).format(n);
        box.querySelector('[data-fx-out]').textContent = fmt(inr / rate);
        box.querySelector('[data-fx-token]').textContent = fmt(inr * 0.05 / rate);
    };
    amount.addEventListener('input', run);
    cur.addEventListener('change', run);
    run();
})();
</script>
@endpush
