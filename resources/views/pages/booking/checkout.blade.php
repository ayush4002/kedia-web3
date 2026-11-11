@extends('layouts.app')

@section('title', 'Reserve Unit ' . $unit->unit_code . ' | Kedia Builders Booking Checkout')

@section('content')

<section class="py-12 bg-[#FAF8F5]">
    <div class="max-w-4xl mx-auto px-6 sm:px-10 lg:px-12">
        
        <!-- Header & Breadcrumb -->
        <div class="text-center mb-10 space-y-2">
            <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">E-Commerce Reservation Portal</span>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#0E1520]">
                Reserve Unit {{ $unit->unit_code }}
            </h1>
            <p class="text-xs sm:text-sm text-[#0E1520]/60">
                Lock your property allotment with a 5% test token advance. Instant holding guarantee for 72 hours.
            </p>
        </div>

        <!-- 4-Step Indicator -->
        <div class="mb-10 bg-white p-4 rounded-2xl border border-[#0E1520]/10 shadow-sm">
            <div class="grid grid-cols-4 gap-2 text-center font-mono text-[11px]">
                <div class="p-2 rounded-xl bg-[#0E1520] text-white font-bold">
                    <span class="text-[#D4E845]">1.</span> Review Unit
                </div>
                <div class="p-2 rounded-xl bg-[#FAF8F5] text-[#0E1520] font-semibold">
                    <span class="text-[#C8A24B]">2.</span> Buyer Details
                </div>
                <div class="p-2 rounded-xl bg-[#FAF8F5] text-[#0E1520] font-semibold">
                    <span class="text-[#C8A24B]">3.</span> Payment Mode
                </div>
                <div class="p-2 rounded-xl bg-[#FAF8F5] text-[#0E1520]/60">
                    <span class="text-[#0E1520]/40">4.</span> Voucher
                </div>
            </div>
        </div>

        <form action="{{ route('booking.store') }}" method="POST" id="checkoutForm" class="space-y-8">
            @csrf
            <input type="hidden" name="unit_id" value="{{ $unit->id }}">

            <!-- Step 1: Unit Summary Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#0E1520]/10 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-4 border-b border-[#0E1520]/10">
                    <div>
                        <div class="text-xs font-mono uppercase text-[#0E1520]/50">Township Property</div>
                        <h3 class="font-serif text-2xl font-bold text-[#0E1520]">{{ $unit->title }} ({{ $unit->unit_code }})</h3>
                        <div class="text-xs text-[#0E1520]/60 font-mono">{{ $unit->project->title ?? 'Kedia Township' }} · {{ $unit->project->location ?? 'Jaipur' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-mono uppercase text-[#0E1520]/50">Base Price</div>
                        <div class="font-bold text-xl text-[#0E1520] font-mono">₹ {{ number_format($pricing['base_price']) }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 font-mono text-xs pt-2">
                    <div class="bg-[#FAF8F5] p-3 rounded-xl">
                        <span class="text-[#0E1520]/50 text-[10px] uppercase block">Super Area</span>
                        <span class="font-bold text-[#0E1520]">{{ $unit->area_sqft }} SQ.FT</span>
                    </div>
                    <div class="bg-[#FAF8F5] p-3 rounded-xl">
                        <span class="text-[#0E1520]/50 text-[10px] uppercase block">Plot Size</span>
                        <span class="font-bold text-[#0E1520]">{{ $unit->plot_size_sqyd ?? 125 }} SQ.YD</span>
                    </div>
                    <div class="bg-[#FAF8F5] p-3 rounded-xl">
                        <span class="text-[#0E1520]/50 text-[10px] uppercase block">Vastu Facing</span>
                        <span class="font-bold text-[#0E1520]">{{ $unit->facing ?? 'East' }}</span>
                    </div>
                    <div class="bg-[#FAF8F5] p-3 rounded-xl">
                        <span class="text-[#0E1520]/50 text-[10px] uppercase block">RERA Status</span>
                        <span class="font-bold text-emerald-700">Sanctioned</span>
                    </div>
                </div>
            </div>

            <!-- Step 2: Buyer Information -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#0E1520]/10 shadow-sm space-y-4">
                <div class="pb-3 border-b border-[#0E1520]/10">
                    <h3 class="font-serif text-xl font-bold text-[#0E1520]">2. Allottee / Buyer Details</h3>
                    <p class="text-xs text-[#0E1520]/60 font-mono">Official details as per PAN card for provisional agreement issuance.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-mono text-[#0E1520]/70 uppercase mb-1">Full Legal Name *</label>
                        <input type="text" name="customer_name" required value="{{ old('customer_name') }}" placeholder="As per PAN card" class="w-full px-4 py-2.5 rounded-xl border border-[#0E1520]/20 bg-[#FAF8F5] text-sm text-[#0E1520] focus:border-[#0E1520] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-[#0E1520]/70 uppercase mb-1">Phone Number (WhatsApp) *</label>
                        <input type="tel" name="customer_phone" required value="{{ old('customer_phone') }}" placeholder="+91 98290 00000" class="w-full px-4 py-2.5 rounded-xl border border-[#0E1520]/20 bg-[#FAF8F5] text-sm text-[#0E1520] focus:border-[#0E1520] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-[#0E1520]/70 uppercase mb-1">Email Address *</label>
                        <input type="email" name="customer_email" required value="{{ old('customer_email') }}" placeholder="you@example.com" class="w-full px-4 py-2.5 rounded-xl border border-[#0E1520]/20 bg-[#FAF8F5] text-sm text-[#0E1520] focus:border-[#0E1520] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-[#0E1520]/70 uppercase mb-1">City &amp; State</label>
                        <input type="text" name="customer_city" value="{{ old('customer_city', 'Jaipur, Rajasthan') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#0E1520]/20 bg-[#FAF8F5] text-sm text-[#0E1520] focus:border-[#0E1520] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-[#0E1520]/70 uppercase mb-1">PAN Card Number</label>
                        <input type="text" name="customer_pan" placeholder="ABCDE1234F" value="{{ old('customer_pan') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#0E1520]/20 bg-[#FAF8F5] text-sm text-[#0E1520] focus:border-[#0E1520] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-[#0E1520]/70 uppercase mb-1">Aadhaar Last 4 Digits</label>
                        <input type="text" name="customer_aadhaar" placeholder="Last 4 digits" value="{{ old('customer_aadhaar') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#0E1520]/20 bg-[#FAF8F5] text-sm text-[#0E1520] focus:border-[#0E1520] focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Step 3: Payment Method Selection -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#0E1520]/10 shadow-sm space-y-4">
                <div class="pb-3 border-b border-[#0E1520]/10">
                    <h3 class="font-serif text-xl font-bold text-[#0E1520]">3. Payment Mode (Razorpay Sandbox Mode)</h3>
                    <p class="text-xs text-[#0E1520]/60 font-mono">Select simulated payment provider. No real funds will be deducted.</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs font-mono">
                    <label class="border-2 border-[#0E1520] bg-[#FAF8F5] p-4 rounded-2xl cursor-pointer flex flex-col items-center justify-center text-center space-y-2">
                        <input type="radio" name="payment_method" value="upi" checked class="accent-[#0E1520]">
                        <span class="font-bold text-sm">UPI</span>
                        <span class="text-[10px] text-[#0E1520]/60">GPay, PhonePe, Paytm</span>
                    </label>
                    <label class="border border-[#0E1520]/20 hover:border-[#0E1520] p-4 rounded-2xl cursor-pointer flex flex-col items-center justify-center text-center space-y-2">
                        <input type="radio" name="payment_method" value="card" class="accent-[#0E1520]">
                        <span class="font-bold text-sm">Cards</span>
                        <span class="text-[10px] text-[#0E1520]/60">Visa, Mastercard, RuPay</span>
                    </label>
                    <label class="border border-[#0E1520]/20 hover:border-[#0E1520] p-4 rounded-2xl cursor-pointer flex flex-col items-center justify-center text-center space-y-2">
                        <input type="radio" name="payment_method" value="netbanking" class="accent-[#0E1520]">
                        <span class="font-bold text-sm">NetBanking</span>
                        <span class="text-[10px] text-[#0E1520]/60">HDFC, SBI, ICICI, Axis</span>
                    </label>
                    <label class="border border-[#0E1520]/20 hover:border-[#0E1520] p-4 rounded-2xl cursor-pointer flex flex-col items-center justify-center text-center space-y-2">
                        <input type="radio" name="payment_method" value="neft" class="accent-[#0E1520]">
                        <span class="font-bold text-sm">NEFT / RTGS</span>
                        <span class="text-[10px] text-[#0E1520]/60">Bank Virtual Account</span>
                    </label>
                </div>
            </div>

            <!-- Final Cost Summary & Submit -->
            <div class="bg-[#0E1520] text-white rounded-3xl p-6 sm:p-8 border border-white/10 shadow-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <div class="text-[10px] font-mono text-[#D4E845] uppercase tracking-wider">Token Advance Payable Now</div>
                    <div class="font-serif text-3xl sm:text-4xl font-bold text-white mt-1">₹ {{ number_format($pricing['token_amount']) }}</div>
                    <div class="text-xs text-white/50 font-mono mt-1">5% of total project value. Balance payable in RERA milestones.</div>
                </div>

                <button type="submit" class="px-8 py-4 rounded-full bg-[#D4E845] hover:bg-[#c2d733] text-[#0E1520] font-bold text-xs uppercase tracking-wider transition-all transform hover:-translate-y-0.5 shadow-xl flex items-center justify-center gap-2">
                    <span>Confirm Test Payment &amp; Reserve Unit</span>
                    <span>&rarr;</span>
                </button>
            </div>

        </form>

    </div>
</section>

@endsection
