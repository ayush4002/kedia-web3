@extends('layouts.app')

@section('title', 'Booking ' . $booking->booking_id . ' | Kedia Builders & Colonizers')
@section('robots', 'noindex, nofollow')

@php
    $company = config('kedia.company');
    $paid = $booking->payment_status === 'paid';
    $cancelled = in_array($booking->status, ['cancelled', 'refunded'], true);
    $holdUntil = ($booking->booking_date ?? $booking->created_at)->copy()->addHours(config('kedia.hold_hours'));
    $breakdown = $booking->cost_breakdown;
@endphp

@section('content')
<section class="section-navy overflow-hidden py-14">
    <div class="blueprint-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="container-site relative grid items-center gap-10 lg:grid-cols-12">
        <div class="lg:col-span-7">
            <div class="eyebrow">{{ $booking->booking_id }}</div>
            <h1 class="display-lg mt-3 text-paper">
                @if($cancelled){{ __('Booking cancelled') }}
                @elseif($paid){{ __('Provisional Allotment Confirmed') }}
                @else{{ __('Unit On Hold · Payment Verification Pending') }}
                @endif
            </h1>
            <p class="mt-4 max-w-xl text-paper/70">
                @if($cancelled)
                    {{ __('This booking is no longer active. Contact us if you have questions about a refund.') }}
                @elseif($paid)
                    {{ __('Your token payment has been verified and your unit is reserved in our inventory.') }}
                @else
                    {{ __('Your unit is on hold while our sales team verifies your token payment. We\'ll contact you on your registered email or mobile to confirm.') }}
                @endif
            </p>
            <div class="no-print mt-8 flex flex-wrap gap-3">
                <a href="{{ route('account') }}" class="btn-brass">{{ __('Go to my account') }}</a>
                <button type="button" onclick="window.print()" class="btn-ghost-light">{{ __('Print / save receipt') }}</button>
            </div>
        </div>
        @if(!$cancelled && !$paid)
            <div class="lg:col-span-5">
                <div class="rounded-[1.5rem] border border-brass/40 bg-white/[0.03] p-6 text-paper" data-countdown="{{ $holdUntil->toIso8601String() }}">
                    <div class="label text-brass">{{ __('Unit held for you until') }}</div>
                    <div class="mt-1 text-sm text-paper/70">{{ $holdUntil->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</div>
                    <div class="mt-4 grid grid-cols-3 gap-3 text-center font-serif text-4xl" aria-live="off">
                        <div><span data-cd="h">--</span><div class="label mt-1 text-paper/40">{{ __('hours') }}</div></div>
                        <div><span data-cd="m">--</span><div class="label mt-1 text-paper/40">{{ __('min') }}</div></div>
                        <div><span data-cd="s">--</span><div class="label mt-1 text-paper/40">{{ __('sec') }}</div></div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<section class="section-paper py-12">
    <div class="container-site max-w-4xl">
        <article class="print-sheet rounded-[1.5rem] border border-navy/10 bg-white p-6 shadow-xl sm:p-10">
            <header class="flex flex-wrap items-start justify-between gap-6 border-b border-navy/10 pb-6">
                <div>
                    <img src="{{ asset('assets/logo-navy.png') }}" alt="Kedia Builders & Colonizers" class="h-11 w-auto">
                    <p class="mt-3 text-xs text-ink/60">{{ $company['address'] }} · {{ $company['phone'] }}</p>
                </div>
                <div class="text-right">
                    <h2 class="font-serif text-2xl text-navy">{{ __('Booking receipt') }}</h2>
                    <div class="font-mono text-sm text-navy">{{ $booking->booking_id }}</div>
                    @if($paid)
                        <div class="label mt-1 text-status-available">● {{ __('Payment Status: VERIFIED') }}</div>
                    @else
                        <div class="label mt-1 text-brass-700">● {{ __('Payment Status:') }} {{ strtoupper($booking->status_label) }} ({{ __('TEST PAYMENT') }})</div>
                    @endif
                </div>
            </header>

            <dl class="grid gap-x-10 gap-y-3 border-b border-navy/10 py-6 text-sm sm:grid-cols-2">
                @foreach([
                    __('Allottee') => $booking->customer_name,
                    __('Mobile') => $booking->customer_phone,
                    __('Project') => $booking->project->title ?? $booking->project_name,
                    __('Unit') => ($booking->unit->title ?? $booking->unit_number) . ' · ' . $booking->unit_code,
                    __('Booked on') => optional($booking->booking_date)->format('d M Y, h:i A'),
                    __('Payment method') => strtoupper((string) $booking->payment_method),
                    __('Transaction ref') => $booking->transaction_ref ?: '—',
                    'RERA' => $booking->project->rera_number ?? '—',
                ] as $label => $value)
                    <div class="flex justify-between gap-4"><dt class="label text-ink/50">{{ $label }}</dt><dd class="text-right text-navy">{{ $value }}</dd></div>
                @endforeach
            </dl>

            <div class="grid gap-8 py-6 md:grid-cols-2">
                <div>
                    <h3 class="label text-ink/50">{{ __('Payment summary') }}</h3>
                    <dl class="mt-3 space-y-2 text-sm">
                        <div class="flex justify-between"><dt>{{ __('Total property value') }}</dt><dd class="font-mono">₹ {{ \App\Support\Format::inr($booking->total_amount) }}</dd></div>
                        <div class="flex justify-between {{ $paid ? 'text-status-available' : '' }}"><dt>{{ __('Advance Booking Token (5%)') }}{{ $paid ? ' — ' . __('Paid') : ' — ' . __('Awaiting Verification') }}</dt><dd class="font-mono">₹ {{ \App\Support\Format::inr($booking->token_amount) }}</dd></div>
                        <div class="flex justify-between border-t border-navy/10 pt-2 text-ink/60"><dt>{{ __('Balance in construction milestones') }}</dt><dd class="font-mono">₹ {{ \App\Support\Format::inr($booking->total_amount - $booking->token_amount) }}</dd></div>
                    </dl>
                </div>
                <div>
                    <h3 class="label text-ink/50">{{ __('Payment schedule') }}</h3>
                    <ol class="mt-3 space-y-2 text-sm">
                        @foreach($booking->payment_schedule ?? [] as $stage)
                            <li class="flex items-start justify-between gap-4 border-b border-navy/5 pb-2">
                                <div>
                                    <div class="text-navy">{{ __($stage['milestone']) }}</div>
                                    <div class="label text-ink/40">{{ \Illuminate\Support\Carbon::parse($stage['due_date'])->format('d M Y') }} · {{ __($stage['status']) }}</div>
                                </div>
                                <div class="font-mono text-xs">₹ {{ \App\Support\Format::inr($stage['amount']) }}</div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>

            <footer class="border-t border-navy/10 pt-5 text-[11px] leading-relaxed text-ink/50">
                {{ __('This is a provisional booking receipt. The allotment is final after document verification and signing of the agreement to sale. Demo build: payments are simulated in test mode.') }}
            </footer>
        </article>

        <div class="no-print mt-10 grid gap-4 md:grid-cols-3">
            @foreach([[__('Verification'), __('Our team verifies your token and calls you within one working day.')], [__('Documents'), __('Share PAN, Aadhaar and photographs to prepare the agreement to sale.')], [__('Agreement'), __('Sign the agreement and pay the next milestone as per the schedule.')]] as $i => [$title, $text])
                <div class="card">
                    <div class="font-mono text-xs text-brass">0{{ $i + 1 }}</div>
                    <div class="mt-2 font-serif text-xl text-navy">{{ $title }}</div>
                    <p class="mt-1 text-sm text-ink/60">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    const box = document.querySelector('[data-countdown]');
    if (!box) return;
    const end = new Date(box.dataset.countdown).getTime();
    const pad = (n) => String(n).padStart(2, '0');
    const tick = () => {
        const left = Math.max(0, end - Date.now());
        box.querySelector('[data-cd="h"]').textContent = pad(Math.floor(left / 3600000));
        box.querySelector('[data-cd="m"]').textContent = pad(Math.floor(left / 60000) % 60);
        box.querySelector('[data-cd="s"]').textContent = pad(Math.floor(left / 1000) % 60);
    };
    tick();
    setInterval(tick, 1000);
})();
</script>
@endpush
