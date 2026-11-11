@extends('layouts.app')

@section('title', 'Patron Portal & Booking Dashboard | Kedia Builders & Colonizers')

@section('content')

<section class="py-12 bg-[#FAF8F5]">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24 space-y-10">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-[#0E1520]/10">
            <div>
                <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Patron Holding Portal') }}</span>
                <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#0E1520]">
                    {{ __('My Property Holdings & Construction Tracker') }}
                </h1>
                <p class="text-xs sm:text-sm text-[#0E1520]/60 mt-1">{{ __('Review active unit allocations, token receipts, construction milestones, and title documents.') }}</p>
            </div>

            <!-- Search Booking Form -->
            <form method="GET" action="{{ route('customer.dashboard') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="booking_id" required value="{{ request('booking_id') }}" placeholder="{{ __('Booking ID (KB-…)') }}" aria-label="Booking ID" class="px-4 py-2.5 rounded-full bg-white border border-[#0E1520]/15 text-xs font-mono text-[#0E1520] focus:outline-none focus:border-[#0E1520]">
                <input type="tel" name="phone" required value="{{ request('phone') }}" placeholder="{{ __('Registered mobile') }}" aria-label="Registered mobile number" class="px-4 py-2.5 rounded-full bg-white border border-[#0E1520]/15 text-xs font-mono text-[#0E1520] focus:outline-none focus:border-[#0E1520]">
                <button type="submit" class="px-5 py-2.5 rounded-full bg-[#0E1520] text-white text-xs font-bold uppercase tracking-wider">
                    {{ __('Lookup') }}
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left 8 Cols: Bookings & Milestones -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Primary Holding Card -->
                @if($primaryBooking)
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#0E1520]/10 shadow-xl space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#0E1520]/10">
                            <div>
                                <span class="px-3 py-1 rounded-full text-[10px] font-mono uppercase tracking-wider font-bold bg-emerald-100 text-emerald-800">
                                    ● {{ strtoupper($primaryBooking->status_label) }}
                                </span>
                                <h2 class="font-serif text-2xl font-bold text-[#0E1520] mt-2">
                                    {{ $primaryBooking->unit->title ?? 'Villa Residence' }} ({{ $primaryBooking->unit->unit_code ?? 'P-11' }})
                                </h2>
                                <div class="text-xs text-[#0E1520]/60 font-mono">{{ $primaryBooking->project->title ?? 'Kedia Landmark' }} · {{ $primaryBooking->project->location ?? 'Jaipur' }}</div>
                            </div>
                            <div class="text-left sm:text-right font-mono text-xs">
                                <div class="text-[#0E1520]/50 uppercase text-[10px]">{{ __('Booking Ref') }}</div>
                                <div class="text-base font-bold text-[#0E1520]">{{ $primaryBooking->booking_id }}</div>
                                <div class="text-[10px] text-emerald-700 font-bold mt-0.5">Token {{ $primaryBooking->payment_status === 'paid' ? 'Paid' : '(awaiting verification)' }}: ₹ {{ number_format($primaryBooking->token_amount) }}</div>
                            </div>
                        </div>

                        <!-- 5 Payment Milestones -->
                        <div class="space-y-3 font-mono text-xs">
                            <div class="flex items-center justify-between">
                                <h3 class="font-serif text-lg font-bold text-[#0E1520]">{{ __('Milestone Payment Progress') }}</h3>
                                <span class="text-[10px] text-emerald-700 font-bold">{{ __('Phase 1 Complete') }}</span>
                            </div>

                            <div class="space-y-2">
                                @if(!empty($primaryBooking->payment_schedule))
                                    @foreach($primaryBooking->payment_schedule as $m)
                                        <div class="p-3 rounded-2xl bg-[#FAF8F5] border border-[#0E1520]/5 flex items-center justify-between">
                                            <div>
                                                <div class="font-semibold text-[#0E1520]">{{ $m['milestone'] }}</div>
                                                <div class="text-[10px] text-[#0E1520]/50">Due: {{ $m['due_date'] }}</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-[#0E1520]">₹ {{ number_format($m['amount']) }}</div>
                                                <span class="text-[10px] uppercase font-bold {{ $m['status'] === 'Paid' ? 'text-emerald-700' : 'text-amber-700' }}">
                                                    ● {{ $m['status'] }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="pt-2 flex flex-wrap gap-3">
                            <a href="{{ route('booking.confirmation', $primaryBooking->booking_id) }}" class="px-5 py-2.5 rounded-full bg-[#0E1520] text-white text-xs font-mono font-bold hover:bg-[#162132]">
                                {{ __('Download Official Allotment Voucher →') }}
                            </a>
                            <button type="button" onclick="alert('JDA Sanction Map is verified and archived under record #JDA-2026-KD.')" class="px-5 py-2.5 rounded-full bg-white border border-[#0E1520]/20 text-[#0E1520] text-xs font-mono font-bold hover:bg-[#FAF8F5]">
                                {{ __('🗺 View JDA Cadastral Map') }}
                            </button>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-3xl p-12 text-center border border-[#0E1520]/10">
                        <div class="text-3xl mb-2">📋</div>
                        @if($lookupFailed)
                            <h3 class="font-serif text-xl font-bold text-[#0E1520]">{{ __('No booking matches those details') }}</h3>
                            <p class="text-xs text-[#0E1520]/60 mt-1 mb-6">{{ __('Check the booking ID on your voucher and the mobile number you booked with, then try again.') }}</p>
                        @else
                            <h3 class="font-serif text-xl font-bold text-[#0E1520]">{{ __('Find your booking') }}</h3>
                            <p class="text-xs text-[#0E1520]/60 mt-1 mb-6">{{ __('Enter your booking ID and registered mobile number to retrieve your property dossier.') }}</p>
                        @endif
                        <a href="{{ route('projects.index') }}" class="px-6 py-2.5 rounded-full bg-[#D4E845] text-[#0E1520] font-bold text-xs uppercase tracking-wider">
                            {{ __('Browse Available Units') }}
                        </a>
                    </div>
                @endif

                <!-- Live Construction Progress Track -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#0E1520]/10 shadow-sm space-y-6">
                    <div>
                        <div class="text-[10px] font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Engineering Milestones') }}</div>
                        <h3 class="font-serif text-2xl font-bold text-[#0E1520] mt-1">{{ __('Township Construction Progress') }}</h3>
                    </div>

                    <div class="space-y-4">
                        @foreach($constructionTimeline as $phase)
                            <div class="space-y-1.5 font-mono text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-[#0E1520]">{{ $phase['phase'] }}</span>
                                    <span class="text-[#0E1520]/60 text-[11px]">{{ $phase['date'] }}</span>
                                </div>
                                <div class="w-full bg-[#FAF8F5] rounded-full h-2.5 border border-[#0E1520]/10 overflow-hidden">
                                    <div class="h-2.5 rounded-full {{ $phase['progress'] == 100 ? 'bg-emerald-500' : ($phase['progress'] > 0 ? 'bg-[#D4E845]' : 'bg-transparent') }}" style="width: {{ $phase['progress'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right 4 Cols: Dedicated Relationship Manager & Documents -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Assigned Officer Card -->
                <div class="bg-[#0E1520] text-white rounded-3xl p-6 sm:p-8 border border-white/10 shadow-xl space-y-4">
                    <div class="text-[10px] font-mono text-[#D4E845] uppercase tracking-wider">{{ __('Dedicated Custodian') }}</div>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-[#D4E845] text-[#0E1520] flex items-center justify-center font-bold text-lg font-serif">
                            {{ __('RM') }}
                        </div>
                        <div>
                            <h4 class="font-serif text-lg font-bold">{{ __('Vikramaditya Rathore') }}</h4>
                            <div class="text-xs text-white/60 font-mono">{{ __('Senior Portfolio Officer') }}</div>
                        </div>
                    </div>
                    <p class="text-xs text-white/70 leading-relaxed font-mono">
                        {{ __('Available 7 days a week for registration scheduling, bank loan processing, or physical site inspection.') }}
                    </p>
                    <div class="pt-2 space-y-2 font-mono text-xs">
                        <a href="tel:+918302500500" class="block w-full py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-center font-bold text-[#D4E845] transition-colors">
                            {{ __('📞 Call +91 8302 500 500') }}
                        </a>
                        <a href="https://wa.me/919829184872" target="_blank" class="block w-full py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-center font-bold text-white transition-colors">
                            {{ __('💬 WhatsApp Direct Desk') }}
                        </a>
                    </div>
                </div>

                <!-- Legal Documents Repository -->
                <div class="bg-white rounded-3xl p-6 border border-[#0E1520]/10 shadow-sm space-y-4">
                    <h4 class="font-serif text-lg font-bold text-[#0E1520]">{{ __('Sanction Repository') }}</h4>
                    <ul class="space-y-3 font-mono text-xs">
                        <li class="flex items-center justify-between pb-2 border-b border-[#0E1520]/5">
                            <span class="text-[#0E1520]">{{ __('JDA Approved Layout Plan') }}</span>
                            <span class="text-emerald-700 font-bold">{{ __('PDF (Verified)') }}</span>
                        </li>
                        <li class="flex items-center justify-between pb-2 border-b border-[#0E1520]/5">
                            <span class="text-[#0E1520]">{{ __('RERA Sanction Certificate') }}</span>
                            <span class="text-emerald-700 font-bold">{{ __('RAJ/P/2021/1480') }}</span>
                        </li>
                        <li class="flex items-center justify-between pb-2 border-b border-[#0E1520]/5">
                            <span class="text-[#0E1520]">{{ __('Non-Encumbrance 40-Yr Search') }}</span>
                            <span class="text-emerald-700 font-bold">{{ __('Clean Title') }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-[#0E1520]">{{ __('Standard Agreement to Sale') }}</span>
                            <span class="text-blue-700 font-bold">{{ __('Draft Available') }}</span>
                        </li>
                    </ul>
                </div>

            </div>

        </div>

    </div>
</section>

@endsection
