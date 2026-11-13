@extends('layouts.app')

@section('title', 'My Account | Kedia Builders & Colonizers')
@section('robots', 'noindex, nofollow')

@section('content')
<section class="section-navy overflow-hidden">
    <div class="blueprint-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <x-blueprint class="absolute -right-10 bottom-0 hidden h-80 w-[28rem] text-brass/20 lg:block" />
    <div class="container-site relative grid min-h-[70vh] items-center gap-12 py-16 lg:grid-cols-2">
        <div>
            <div class="eyebrow">{{ __('My account') }}</div>
            <h1 class="display-lg mt-4 text-paper">{{ __('Your home, tracked from booking to keys.') }}</h1>
            <ul class="mt-8 space-y-3 text-paper/70">
                @foreach([__('Bookings and receipts'), __('Payment schedule and reminders'), __('Documents for your unit'), __('Construction updates')] as $line)
                    <li class="flex items-center gap-3"><span class="h-px w-6 bg-brass" aria-hidden="true"></span>{{ $line }}</li>
                @endforeach
            </ul>
        </div>

        <div class="rounded-[2rem] bg-paper p-8 text-ink shadow-2xl sm:p-10">
            @if(!$pending)
                <h2 class="font-serif text-3xl text-navy">{{ __('Sign in with your mobile') }}</h2>
                <p class="mt-2 text-sm text-ink/60">{{ __('Use the number you booked with. We will send a one-time code.') }}</p>
                <form action="{{ route('account.otp') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="acc_phone" class="field-label">{{ __('Mobile number') }}</label>
                        <input id="acc_phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" required autofocus value="{{ old('phone', session('account_phone_hint')) }}" placeholder="98290 00000" class="field text-lg">
                        @error('phone')<p class="mt-1 text-xs text-terracotta">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn-navy w-full">{{ __('Send code') }}</button>
                </form>
            @else
                <h2 class="font-serif text-3xl text-navy">{{ __('Enter your code') }}</h2>
                <p class="mt-2 text-sm text-ink/60">{{ __('Sent to') }} ••••••{{ substr($pending['phone'], -4) }}. {{ __('It expires in 10 minutes.') }}</p>
                @if($demoOtp)
                    <div class="mt-5 rounded-2xl border border-dashed border-terracotta/60 bg-terracotta/5 p-4 text-sm">
                        <div class="label text-terracotta">{{ __('Demo mode — no SMS is sent') }}</div>
                        <div class="mt-1 text-ink/70">{{ __('Your code is') }} <span class="font-mono text-lg font-semibold tracking-[0.3em] text-navy" data-demo-otp>{{ $demoOtp }}</span></div>
                    </div>
                @endif
                <form action="{{ route('account.verify') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="acc_otp" class="field-label">{{ __('6-digit code') }}</label>
                        <input id="acc_otp" name="otp" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required autofocus class="field text-center font-mono text-2xl tracking-[0.5em]">
                        @error('otp')<p class="mt-1 text-xs text-terracotta">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn-navy w-full">{{ __('Verify and sign in') }}</button>
                </form>
                <form action="{{ route('account.otp') }}" method="POST" class="mt-3 text-center">
                    @csrf
                    <input type="hidden" name="phone" value="{{ $pending['phone'] }}">
                    <button type="submit" class="label text-ink/50 underline hover:text-terracotta">{{ __('Send a new code') }}</button>
                </form>
            @endif
            <p class="mt-8 border-t border-navy/10 pt-5 text-xs text-ink/50">{{ __('No booking yet?') }} <a href="{{ route('projects.index', ['view' => 'units']) }}" class="underline decoration-brass underline-offset-4">{{ __('Browse available units') }}</a></p>
        </div>
    </div>
</section>
@endsection
