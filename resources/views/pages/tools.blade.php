@extends('layouts.app')

@section('title', 'EMI, Price & Home Loan Tools | Kedia Builders & Colonizers')
@section('meta_description', 'Home loan EMI calculator, property price calculator for Kedia projects in Jaipur, stamp duty estimate and home loan enquiry.')

@php $range = 'w-full accent-[#C8A24B]'; @endphp

@section('content')
<x-page-hero :eyebrow="__('Tools')" :title="__('Plan the numbers')" :crumb="__('Tools')">
    {{ __('Estimate your EMI, price a unit by size, and ask us to arrange a pre-approved home loan.') }}
</x-page-hero>

<section class="section-paper py-16" data-tabs>
    <div class="container-site">
        <div class="inline-flex flex-wrap rounded-full border border-navy/15 p-1" role="tablist" aria-label="{{ __('Tools') }}">
            @foreach(['emi' => __('EMI calculator'), 'price' => __('Price calculator'), 'loan' => __('Home loan enquiry')] as $key => $label)
                <button type="button" role="tab" id="tt-{{ $key }}" aria-controls="tp-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" class="{{ $loop->first ? 'is-active' : '' }} rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-ink/60 [&.is-active]:bg-navy [&.is-active]:text-paper">{{ $label }}</button>
            @endforeach
        </div>

        {{-- EMI --}}
        <div id="tp-emi" role="tabpanel" aria-labelledby="tt-emi" class="mt-10 grid gap-8 lg:grid-cols-12">
            <div class="space-y-7 rounded-[1.5rem] border border-navy/10 bg-paper-50 p-6 sm:p-8 lg:col-span-7">
                @foreach([
                    ['inputProperty', __('Property value'), 1000000, 30000000, 100000, 5000000, 'inr'],
                    ['inputLoan', __('Loan amount'), 500000, 25000000, 100000, 4000000, 'inr'],
                    ['inputRate', __('Interest rate (% p.a.)'), 6.5, 14, 0.05, 8.5, 'pct'],
                    ['inputTenure', __('Tenure (years)'), 5, 30, 1, 20, 'yrs'],
                ] as [$id, $label, $min, $max, $step, $value, $fmt])
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="{{ $id }}" class="field-label !mb-0">{{ $label }}</label>
                            <output for="{{ $id }}" data-display="{{ $id }}" data-fmt="{{ $fmt }}" class="font-mono text-sm text-navy"></output>
                        </div>
                        <input type="range" id="{{ $id }}" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" value="{{ $value }}" class="{{ $range }} mt-3">
                    </div>
                @endforeach
            </div>
            <div class="rounded-[1.5rem] bg-navy p-6 text-paper sm:p-8 lg:col-span-5" aria-live="polite">
                <div class="label text-brass">{{ __('Monthly EMI') }}</div>
                <div class="mt-2 font-serif text-6xl" id="resultEmi">—</div>
                <dl class="mt-8 space-y-3 border-t border-paper/10 pt-6 text-sm">
                    <div class="flex justify-between"><dt class="text-paper/60">{{ __('Principal') }}</dt><dd class="font-mono" id="resultPrincipal">—</dd></div>
                    <div class="flex justify-between"><dt class="text-paper/60">{{ __('Total interest') }}</dt><dd class="font-mono text-brass" id="resultInterest">—</dd></div>
                    <div class="flex justify-between"><dt class="text-paper/60">{{ __('Total payable') }}</dt><dd class="font-mono" id="resultTotal">—</dd></div>
                    <div class="flex justify-between border-t border-paper/10 pt-3"><dt class="text-paper/60">{{ __('Stamp duty (est. 5% of property value)') }}</dt><dd class="font-mono" id="resultStamp">—</dd></div>
                    <div class="flex justify-between"><dt class="text-paper/60">{{ __('Registration (est. 1%)') }}</dt><dd class="font-mono" id="resultReg">—</dd></div>
                </dl>
                <p class="mt-4 text-xs text-paper/40">{{ __('Indicative only — rates vary by buyer category and DLC rate; confirm with the sub-registrar.') }}</p>
                <button type="button" class="btn-brass mt-6 w-full" onclick="document.getElementById('tt-loan').click()">{{ __('Get a pre-approved loan') }}</button>
            </div>
        </div>

        {{-- PRICE --}}
        <div id="tp-price" role="tabpanel" aria-labelledby="tt-price" class="mt-10 grid gap-8 lg:grid-cols-12" hidden data-price data-rates="{{ json_encode($rates) }}">
            <div class="space-y-6 rounded-[1.5rem] border border-navy/10 bg-paper-50 p-6 sm:p-8 lg:col-span-7">
                <div>
                    <label for="pc_project" class="field-label">{{ __('Project & unit type') }}</label>
                    <select id="pc_project" class="field">
                        @foreach($rates as $r)<option value="{{ $r['key'] }}">{{ $r['label'] }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <div class="flex items-center justify-between"><label for="pc_area" class="field-label !mb-0">{{ __('Area (sq ft)') }}</label><output for="pc_area" id="pc_area_out" class="font-mono text-sm text-navy"></output></div>
                    <input type="range" id="pc_area" step="10" class="{{ $range }} mt-3">
                    <div class="mt-1 flex justify-between font-mono text-[11px] text-ink/40"><span id="pc_min"></span><span id="pc_max"></span></div>
                </div>
            </div>
            <div class="rounded-[1.5rem] bg-navy p-6 text-paper sm:p-8 lg:col-span-5" aria-live="polite">
                <div class="label text-brass">{{ __('Estimated all-inclusive price') }}</div>
                <div class="mt-2 font-serif text-6xl" id="pc_total">—</div>
                <dl class="mt-8 space-y-3 border-t border-paper/10 pt-6 text-sm">
                    <div class="flex justify-between"><dt class="text-paper/60">{{ __('Average rate') }}</dt><dd class="font-mono" id="pc_rate">—</dd></div>
                    <div class="flex justify-between"><dt class="text-paper/60">{{ __('Online token (5%)') }}</dt><dd class="font-mono" id="pc_token">—</dd></div>
                </dl>
                <p class="mt-4 text-xs text-paper/40">{{ __('Based on the average rate of listed units. See exact prices on each unit page.') }}</p>
                <a href="{{ route('projects.index', ['view' => 'units']) }}" class="btn-brass mt-6 w-full" id="pc_link">{{ __('See matching units') }}</a>
            </div>
        </div>

        {{-- LOAN ENQUIRY --}}
        <div id="tp-loan" role="tabpanel" aria-labelledby="tt-loan" class="mt-10 grid gap-8 lg:grid-cols-12" hidden>
            <form action="{{ route('leads.store') }}" method="POST" class="space-y-5 rounded-[1.5rem] border border-navy/10 bg-paper-50 p-6 sm:p-8 lg:col-span-7">
                @csrf
                <input type="hidden" name="source" value="home_loan">
                <h2 class="font-serif text-3xl text-navy">{{ __('Home loan enquiry') }}</h2>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div><label for="ln_name" class="field-label">{{ __('Full name') }} *</label><input id="ln_name" name="name" required autocomplete="name" class="field"></div>
                    <div><label for="ln_phone" class="field-label">{{ __('Mobile') }} *</label><input id="ln_phone" name="phone" type="tel" required autocomplete="tel" class="field"></div>
                    <div><label for="ln_email" class="field-label">{{ __('Email') }}</label><input id="ln_email" name="email" type="email" autocomplete="email" class="field"></div>
                    <div>
                        <label for="ln_emp" class="field-label">{{ __('Employment') }}</label>
                        <select id="ln_emp" name="project_name" class="field">
                            <option value="Home loan · Salaried">{{ __('Salaried') }}</option>
                            <option value="Home loan · Self-employed">{{ __('Self-employed') }}</option>
                            <option value="Home loan · NRI">{{ __('NRI') }}</option>
                        </select>
                    </div>
                </div>
                <div><label for="ln_msg" class="field-label">{{ __('Loan amount and the unit you like') }}</label><textarea id="ln_msg" name="message" rows="3" maxlength="500" class="field"></textarea></div>
                <button type="submit" class="btn-navy">{{ __('Request loan assistance') }}</button>
            </form>
            <div class="lg:col-span-5">
                <h3 class="label text-ink/50">{{ __('Partner banks') }}</h3>
                <ul class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    @foreach(['SBI', 'HDFC Bank', 'ICICI Bank', 'Axis Bank', 'PNB Housing', 'LIC Housing'] as $bank)
                        <li class="rounded-xl border border-navy/10 bg-paper-50 px-4 py-3 text-navy">{{ $bank }}</li>
                    @endforeach
                </ul>
                <p class="mt-3 flex items-center gap-2 text-xs text-ink/50"><x-sample-tag /> {{ __('Bank tie-ups to be confirmed.') }}</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    const inr = (n) => '₹ ' + Math.round(n).toLocaleString('en-IN');
    const $ = (id) => document.getElementById(id);

    // EMI
    const show = (id) => {
        const el = document.querySelector('[data-display="' + id + '"]');
        const v = parseFloat($(id).value);
        el.textContent = el.dataset.fmt === 'inr' ? inr(v) : el.dataset.fmt === 'pct' ? v.toFixed(2) + ' %' : v + ' yrs';
    };
    const emi = () => {
        const property = parseFloat($('inputProperty').value);
        const loan = $('inputLoan');
        if (parseFloat(loan.value) > property) loan.value = property; // can't borrow more than the price
        const P = parseFloat(loan.value), r = parseFloat($('inputRate').value) / 1200, n = parseInt($('inputTenure').value, 10) * 12;
        const m = r ? P * r * Math.pow(1 + r, n) / (Math.pow(1 + r, n) - 1) : P / n;
        ['inputProperty', 'inputLoan', 'inputRate', 'inputTenure'].forEach(show);
        $('resultEmi').textContent = inr(m);
        $('resultPrincipal').textContent = inr(P);
        $('resultInterest').textContent = inr(m * n - P);
        $('resultTotal').textContent = inr(m * n);
        $('resultStamp').textContent = inr(property * 0.05);
        $('resultReg').textContent = inr(property * 0.01);
    };
    ['inputProperty', 'inputLoan', 'inputRate', 'inputTenure'].forEach((id) => $(id).addEventListener('input', emi));
    emi();

    // Price calculator
    const box = document.querySelector('[data-price]');
    const rates = JSON.parse(box.dataset.rates);
    const sel = $('pc_project'), area = $('pc_area');
    const setRange = () => {
        const r = rates.find((x) => x.key === sel.value);
        if (!r) return;
        area.min = r.min; area.max = Math.max(r.max, r.min + 10);
        if (+area.value < r.min || +area.value > r.max) area.value = Math.round((r.min + r.max) / 2);
        $('pc_min').textContent = r.min.toLocaleString('en-IN'); $('pc_max').textContent = r.max.toLocaleString('en-IN');
        price();
    };
    const price = () => {
        const r = rates.find((x) => x.key === sel.value);
        if (!r) return;
        const total = r.rate * +area.value;
        $('pc_area_out').textContent = (+area.value).toLocaleString('en-IN') + ' sq ft';
        $('pc_total').textContent = inr(total);
        $('pc_rate').textContent = inr(r.rate) + ' / sq ft';
        $('pc_token').textContent = inr(total * 0.05);
        const [slug, type] = r.key.split('|');
        $('pc_link').href = '/projects?view=units&type=' + type.toLowerCase() + '&q=' + encodeURIComponent(r.label.split(' · ')[0]);
    };
    sel.addEventListener('change', setRange);
    area.addEventListener('input', price);
    setRange();
})();
</script>
@endpush
