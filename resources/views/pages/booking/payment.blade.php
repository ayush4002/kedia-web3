@extends('layouts.app')

@section('title', 'Test Payment · ' . $booking->booking_id . ' | Kedia Builders & Colonizers')
@section('robots', 'noindex, nofollow')

@php $method = $booking->payment_method; @endphp

@section('content')
<section class="section-navy min-h-[70vh] py-14">
    <div class="blueprint-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="container-site relative max-w-xl">
        <div class="overflow-hidden rounded-[2rem] bg-paper text-ink shadow-2xl">
            <div class="flex items-center justify-between bg-terracotta px-6 py-2.5 text-paper">
                <span class="font-mono text-[11px] uppercase tracking-wider">{{ __('Test mode') }}</span>
                <span class="text-xs">{{ __('Demo gateway — no real payment is taken') }}</span>
            </div>
            <div class="border-b border-navy/10 p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="label text-ink/50">{{ __('Paying') }}</div>
                        <div class="font-serif text-2xl text-navy">Kedia Builders & Colonizers</div>
                        <div class="mt-1 font-mono text-xs text-ink/50">{{ __('Order') }} {{ $booking->booking_id }} · {{ $booking->unit_code }}</div>
                    </div>
                    <div class="text-right">
                        <div class="label text-ink/50">{{ __('Amount') }}</div>
                        <div class="font-serif text-3xl text-navy">₹ {{ \App\Support\Format::inr($booking->token_amount_paid) }}</div>
                    </div>
                </div>
            </div>

            <div class="p-6" data-tabs>
                <div class="grid grid-cols-3 gap-1 rounded-full bg-paper-200 p-1" role="tablist" aria-label="{{ __('Payment method') }}">
                    @foreach(['upi' => __('UPI'), 'card' => __('Card'), 'netbanking' => __('Net banking')] as $key => $label)
                        <button type="button" role="tab" id="pt-{{ $key }}" aria-controls="pp-{{ $key }}" aria-selected="{{ $method === $key ? 'true' : 'false' }}"
                            class="{{ $method === $key ? 'is-active' : '' }} rounded-full py-2 text-xs font-semibold uppercase tracking-wider text-ink/60 [&.is-active]:bg-navy [&.is-active]:text-paper">{{ $label }}</button>
                    @endforeach
                </div>

                <div id="pp-upi" role="tabpanel" aria-labelledby="pt-upi" class="mt-6 space-y-3" @if($method !== 'upi') hidden @endif>
                    <label for="pay_upi" class="field-label">{{ __('UPI ID') }}</label>
                    <input id="pay_upi" value="success@demo" class="field font-mono" autocomplete="off">
                    <p class="text-xs text-ink/50">{{ __('Any value works in test mode.') }}</p>
                </div>
                <div id="pp-card" role="tabpanel" aria-labelledby="pt-card" class="mt-6 grid grid-cols-2 gap-3" @if($method !== 'card') hidden @endif>
                    <div class="col-span-2">
                        <label for="pay_card" class="field-label">{{ __('Card number') }}</label>
                        <input id="pay_card" value="4111 1111 1111 1111" class="field font-mono" autocomplete="off" inputmode="numeric">
                    </div>
                    <div><label for="pay_exp" class="field-label">{{ __('Expiry') }}</label><input id="pay_exp" value="12/30" class="field font-mono" autocomplete="off"></div>
                    <div><label for="pay_cvv" class="field-label">CVV</label><input id="pay_cvv" value="123" class="field font-mono" autocomplete="off" inputmode="numeric"></div>
                    <p class="col-span-2 text-xs text-ink/50">{{ __('A standard test card number. Do not enter a real card here.') }}</p>
                </div>
                <div id="pp-netbanking" role="tabpanel" aria-labelledby="pt-netbanking" class="mt-6 space-y-3" @if($method !== 'netbanking') hidden @endif>
                    <label for="pay_bank" class="field-label">{{ __('Bank') }}</label>
                    <select id="pay_bank" class="field">
                        @foreach(['State Bank of India', 'HDFC Bank', 'ICICI Bank', 'Axis Bank', 'Punjab National Bank'] as $bank)<option>{{ $bank }}</option>@endforeach
                    </select>
                </div>

                <form action="{{ $processUrl }}" method="POST" class="mt-8 grid gap-2" id="payForm">
                    @csrf
                    <button type="submit" name="outcome" value="success" class="btn-navy w-full !py-4 text-sm">
                        {{ __('Pay') }} ₹ {{ \App\Support\Format::inr($booking->token_amount_paid) }} ({{ __('simulate success') }})
                    </button>
                    <button type="submit" name="outcome" value="failure" class="label py-2 text-ink/50 underline hover:text-terracotta">{{ __('Simulate a declined payment') }}</button>
                </form>
            </div>
            <div class="flex items-center gap-3 border-t border-navy/10 bg-paper-200/60 px-6 py-4 text-xs text-ink/60">
                <svg class="h-4 w-4 flex-none text-brass" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></svg>
                {{ __('Your unit is on hold while you complete this step. In production this screen is replaced by the live payment gateway.') }}
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.getElementById('payForm')?.addEventListener('submit', function (e) {
    const btn = e.submitter;
    if (btn && btn.value === 'success') { btn.disabled = false; btn.textContent = '{{ __('Processing…') }}'; }
});
</script>
@endpush
