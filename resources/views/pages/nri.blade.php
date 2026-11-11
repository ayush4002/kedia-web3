@extends('layouts.app')

@section('title', 'NRI Real Estate Investment Desk | Kedia Builders & Colonizers')

@section('content')

<section class="py-16 bg-[#0E1520] text-white">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        <div class="max-w-3xl space-y-4">
            <span class="text-xs font-mono uppercase tracking-widest text-[#D4E845]">{{ __('Global Investor Services') }}</span>
            <h1 class="font-serif text-4xl sm:text-6xl font-bold tracking-tight">
                {{ __('Dedicated NRI Real Estate Desk') }}
            </h1>
            <p class="text-base sm:text-lg text-white/70 leading-relaxed font-normal">
                {{ __('Invest in the high-growth corridor of the Pink City from anywhere in the world. Enjoy 100% transparent JDA title deeds, end-to-end FEMA compliance, virtual 3D inspections, and repatriation assistance.') }}
            </p>
            <div class="pt-2 flex flex-wrap gap-4 font-mono text-xs">
                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/20">{{ __('FEMA & RBI Compliant') }}</span>
                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/20">{{ __('NRE / NRO Repatriation') }}</span>
                <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/20">{{ __('Digital PoA Sanctions') }}</span>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-[#FAF8F5]">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24 space-y-16">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl bg-white border border-[#0E1520]/10 shadow-sm space-y-4">
                <div class="text-3xl">🏛</div>
                <h3 class="font-serif text-2xl font-bold text-[#0E1520]">{{ __('Clear Title & JDA Patta') }}</h3>
                <p class="text-xs text-[#0E1520]/70 leading-relaxed font-mono">
                    {{ __('Zero title risk. Every single plot and villa in Kedia townships is freehold with individual JDA patta and clear chain of non-encumbrance records spanning 40+ years.') }}
                </p>
            </div>

            <div class="p-8 rounded-3xl bg-white border border-[#0E1520]/10 shadow-sm space-y-4">
                <div class="text-3xl">💱</div>
                <h3 class="font-serif text-2xl font-bold text-[#0E1520]">{{ __('NRE / NRO Seamless Payments') }}</h3>
                <p class="text-xs text-[#0E1520]/70 leading-relaxed font-mono">
                    {{ __('Direct wire transfers via SWIFT/IBAN into dedicated escrow accounts. Simplified documentation for outward repatriation of rental yields and capital gains.') }}
                </p>
            </div>

            <div class="p-8 rounded-3xl bg-white border border-[#0E1520]/10 shadow-sm space-y-4">
                <div class="text-3xl">📹</div>
                <h3 class="font-serif text-2xl font-bold text-[#0E1520]">{{ __('Live Drone & Video Walkthroughs') }}</h3>
                <p class="text-xs text-[#0E1520]/70 leading-relaxed font-mono">
                    {{ __('Weekly aerial high-definition drone progress reports and private live video tours conducted by your dedicated relationship custodian.') }}
                </p>
            </div>
        </div>

        <!-- NRI Inquiry Desk Box -->
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-[#0E1520]/10 shadow-xl max-w-3xl mx-auto space-y-6">
            <div class="text-center space-y-2">
                <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Direct Global Line') }}</span>
                <h3 class="font-serif text-3xl font-bold text-[#0E1520]">{{ __('Connect with Our Global NRI Desk') }}</h3>
                <p class="text-xs text-[#0E1520]/60 font-mono">{{ __('Our international advisors will coordinate via WhatsApp or scheduled Zoom conference.') }}</p>
            </div>

            <form action="{{ route('leads.store') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                <input type="hidden" name="source" value="nri_desk">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Full Name *') }}</label>
                        <input type="text" name="name" required placeholder="{{ __('e.g. Anand Singhania') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300">
                    </div>
                    <div>
                        <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Current Country / City *') }}</label>
                        <input type="text" name="message" required placeholder="{{ __('e.g. Dubai, UAE / London, UK') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('International WhatsApp *') }}</label>
                        <input type="tel" name="phone" required placeholder="+971 50 000 0000" class="w-full px-4 py-2.5 rounded-xl border border-gray-300">
                    </div>
                    <div>
                        <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Email Address *') }}</label>
                        <input type="email" name="email" required placeholder="anand@example.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-300">
                    </div>
                </div>

                <div>
                    <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Target Township / Investment Size') }}</label>
                    <select name="project_name" class="w-full px-4 py-2.5 rounded-xl border border-gray-300">
                        <option value="Kedia's Landmark">{{ __('Kedia\'s Landmark (Ajmer Road) - Plots & Villas') }}</option>
                        <option value="Kedia's Atulyam">{{ __('Kedia\'s Atulyam (Sirsi Road) - Turnkey 3 BHK Villas') }}</option>
                        <option value="Kedia's Tulsi">{{ __('Kedia\'s Tulsi (Bani Park) - Luxury 3 & 4 BHK') }}</option>
                        <option value="Ganpati Greens">{{ __('Ganpati Greens (Sikar Road) - Enclave') }}</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-full bg-[#0E1520] hover:bg-[#162132] text-white font-bold text-xs uppercase tracking-wider transition-all shadow mt-2">
                    {{ __('Request Priority NRI Consultation →') }}
                </button>
            </form>
        </div>

    </div>
</section>

@endsection
