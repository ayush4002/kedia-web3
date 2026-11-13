@extends('layouts.app')

@section('title', 'Checkout · ' . $unit->unit_code . ' | Kedia Builders & Colonizers')
@section('robots', 'noindex, nofollow')

@php
    $project = $unit->project;
    $steps = [__('Unit & cost sheet'), __('Your details'), __('Test payment'), __('Confirmation')];
@endphp

@section('content')
<section class="section-navy">
    <div class="container-site py-10">
        <a href="{{ $unit->url }}" class="label text-paper/50 hover:text-brass">← {{ __('Back to unit') }}</a>
        <h1 class="display-md mt-3 text-paper">{{ __('Reserve your unit') }}</h1>
        <ol class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4" aria-label="{{ __('Checkout steps') }}">
            @foreach($steps as $i => $step)
                <li class="flex items-center gap-3 border-t-2 pt-3 text-sm {{ $i < 2 ? 'border-brass text-paper' : 'border-paper/15 text-paper/40' }}" @if($i === 1) aria-current="step" @endif>
                    <span class="font-mono text-xs {{ $i < 2 ? 'text-brass' : '' }}">0{{ $i + 1 }}</span>{{ $step }}
                </li>
            @endforeach
        </ol>
    </div>
</section>

<section class="section-paper py-12">
    <form action="{{ route('booking.store') }}" method="POST" class="container-site grid gap-10 lg:grid-cols-12" novalidate>
        @csrf
        <input type="hidden" name="unit_id" value="{{ $unit->id }}">

        <div class="space-y-8 lg:col-span-7">
            {{-- Buyer details --}}
            <fieldset class="rounded-[1.5rem] border border-navy/10 bg-paper-50 p-6 sm:p-8">
                <legend class="sr-only">{{ __('Your details') }}</legend>
                <h2 class="font-serif text-2xl text-navy">{{ __('Your details') }}</h2>
                <p class="mt-1 text-sm text-ink/60">{{ __('Use the name on your PAN card; the provisional allotment is issued in this name.') }}</p>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="c_name" class="field-label">{{ __('Full name') }} *</label>
                        <input id="c_name" name="customer_name" required autocomplete="name" value="{{ old('customer_name') }}" class="field" aria-invalid="{{ $errors->has('customer_name') ? 'true' : 'false' }}">
                        @error('customer_name')<p class="mt-1 text-xs text-terracotta">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="c_phone" class="field-label">{{ __('Mobile number') }} *</label>
                        <input id="c_phone" name="customer_phone" type="tel" required autocomplete="tel" inputmode="tel" value="{{ old('customer_phone') }}" placeholder="+91 98290 00000" class="field">
                        <p class="mt-1 text-xs text-ink/50">{{ __('You will use this number to sign in to your account.') }}</p>
                        @error('customer_phone')<p class="mt-1 text-xs text-terracotta">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="c_email" class="field-label">{{ __('Email') }} *</label>
                        <input id="c_email" name="customer_email" type="email" required autocomplete="email" value="{{ old('customer_email') }}" class="field">
                        @error('customer_email')<p class="mt-1 text-xs text-terracotta">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="c_pan" class="field-label">{{ __('PAN') }} <span class="normal-case tracking-normal text-ink/40">({{ __('optional now') }})</span></label>
                        <input id="c_pan" name="customer_pan" autocomplete="off" maxlength="10" value="{{ old('customer_pan') }}" placeholder="ABCDE1234F" class="field uppercase">
                        @error('customer_pan')<p class="mt-1 text-xs text-terracotta">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="c_aadhaar" class="field-label">{{ __('Aadhaar, last 4 digits') }} <span class="normal-case tracking-normal text-ink/40">({{ __('optional') }})</span></label>
                        <input id="c_aadhaar" name="customer_aadhaar" inputmode="numeric" maxlength="4" autocomplete="off" value="{{ old('customer_aadhaar') }}" class="field">
                        @error('customer_aadhaar')<p class="mt-1 text-xs text-terracotta">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="c_city" class="field-label">{{ __('City & state') }}</label>
                        <input id="c_city" name="customer_city" autocomplete="address-level2" value="{{ old('customer_city', 'Jaipur, Rajasthan') }}" class="field">
                    </div>
                </div>
            </fieldset>

            {{-- Payment method --}}
            <fieldset class="rounded-[1.5rem] border border-navy/10 bg-paper-50 p-6 sm:p-8">
                <legend class="sr-only">{{ __('Payment method') }}</legend>
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="font-serif text-2xl text-navy">{{ __('Payment method') }}</h2>
                    <span class="rounded-full bg-terracotta/10 px-3 py-1 font-mono text-[10px] uppercase tracking-wider text-terracotta">{{ __('Test mode — no money is charged') }}</span>
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    @foreach(['upi' => [__('UPI'), 'GPay · PhonePe · Paytm'], 'card' => [__('Card'), 'Visa · Mastercard · RuPay'], 'netbanking' => [__('Net banking'), 'SBI · HDFC · ICICI · Axis']] as $value => [$label, $hint])
                        <label class="cursor-pointer rounded-2xl border border-navy/15 p-4 transition hover:border-brass has-[:checked]:border-navy has-[:checked]:bg-navy has-[:checked]:text-paper">
                            <input type="radio" name="payment_method" value="{{ $value }}" class="sr-only" @checked(old('payment_method', 'upi') === $value)>
                            <span class="block font-serif text-xl">{{ $label }}</span>
                            <span class="mt-1 block text-xs opacity-60">{{ $hint }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <label class="flex items-start gap-3 text-sm text-ink/70">
                <input type="checkbox" name="accept_terms" value="1" required class="mt-0.5 h-4 w-4 accent-navy" @checked(old('accept_terms'))>
                <span>{{ __('I have read the') }} <a href="{{ route('legal.refund') }}" target="_blank" class="underline decoration-brass underline-offset-4">{{ __('refund & booking policy') }}</a> {{ __('and the') }} <a href="{{ route('legal.terms') }}" target="_blank" class="underline decoration-brass underline-offset-4">{{ __('terms of use') }}</a>.</span>
            </label>
            @error('accept_terms')<p class="-mt-6 text-xs text-terracotta">{{ $message }}</p>@enderror
        </div>

        {{-- Order summary --}}
        <aside class="lg:col-span-5">
            <div class="space-y-5 lg:sticky lg:top-28">
                <div class="overflow-hidden rounded-[1.5rem] bg-navy text-paper">
                    <div class="flex gap-4 border-b border-paper/10 p-6">
                        @if($project)
                            <img src="{{ \App\Models\Project::media($project->cover_image, 600) }}" alt="" class="arch-soft h-24 w-20 flex-none object-cover">
                        @endif
                        <div class="min-w-0">
                            <div class="label text-brass">{{ $unit->project_name }}</div>
                            <div class="mt-1 font-serif text-2xl leading-tight">{{ $unit->title }}</div>
                            <div class="mt-1 font-mono text-xs text-paper/60">{{ $unit->unit_code }} · {{ \App\Support\Format::inr($unit->area_sqft) }} {{ __('sq ft') }} · {{ $unit->facing }}</div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="eyebrow">{{ __('Cost sheet') }}</h2>
                            <a href="{{ route('units.cost-sheet', $unit->id) }}" target="_blank" rel="noopener" class="label text-paper/60 underline hover:text-brass">{{ __('Download PDF') }}</a>
                        </div>
                        <x-price-breakup :pricing="$pricing" dark class="mt-3" />
                    </div>
                    <div class="border-t border-brass/30 bg-white/[0.03] p-6">
                        <div class="flex items-end justify-between gap-4">
                            <div>
                                <div class="label text-brass">{{ __('Pay now (5% token)') }}</div>
                                <div class="font-serif text-4xl">₹ {{ \App\Support\Format::inr($pricing['token_amount']) }}</div>
                            </div>
                            <div class="label text-right text-paper/50">{{ __('Balance in') }}<br>{{ __('RERA milestones') }}</div>
                        </div>
                        <button type="submit" class="btn-brass mt-6 w-full">{{ __('Continue to test payment') }} →</button>
                        <p class="mt-3 text-center text-xs text-paper/50">{{ __('The unit is held for you for') }} {{ config('kedia.hold_hours') }} {{ __('hours after payment.') }}</p>
                    </div>
                </div>
                <ul class="space-y-2 text-sm text-ink/70">
                    @foreach([__('RERA registered project') . ($project?->rera_number ? ' · ' . $project->rera_number : ''), __('Clear JDA-approved title'), __('Token refundable as per policy')] as $line)
                        <li class="flex gap-3"><span class="text-brass" aria-hidden="true">✓</span>{{ $line }}</li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </form>
</section>
@endsection
