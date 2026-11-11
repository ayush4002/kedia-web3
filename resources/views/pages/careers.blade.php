@extends('layouts.app')

@section('title', 'Careers | Kedia Builders & Colonizers')

@section('content')

<section class="py-16 bg-[#FAF8F5] border-b border-[#0E1520]/10">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        <div class="max-w-3xl space-y-4">
            <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Join Our Team') }}</span>
            <h1 class="font-serif text-4xl sm:text-6xl font-bold text-[#0E1520] tracking-tight">
                {{ __('Build Jaipur\'s Finest Townships') }}
            </h1>
            <p class="text-base text-[#0E1520]/70 leading-relaxed font-normal">
                {{ __('At Kedia Builders & Colonizers, we build more than structures—we craft enduring communities. Join our multidisciplinary team of civil engineers, architects, project managers, and property advisors.') }}
            </p>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6 sm:px-10 lg:px-12 space-y-10">
        
        <div class="space-y-4">
            <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[#0E1520]">{{ __('Current Openings in Jaipur') }}</h2>
            <p class="text-xs text-[#0E1520]/60 font-mono">{{ __('Headquarters & On-Site Construction Enclaves') }}</p>
        </div>

        <div class="space-y-4">
            
            <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-[#0E1520]/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-mono uppercase bg-[#0E1520] text-white">{{ __('Engineering') }}</span>
                    <h3 class="font-serif text-xl font-bold text-[#0E1520] mt-1">{{ __('Senior Civil Site Engineer') }}</h3>
                    <div class="text-xs text-[#0E1520]/60 font-mono mt-0.5">{{ __('Full Time · Ajmer Road & Sirsi Road · 5-8 Yrs Exp') }}</div>
                </div>
                <button type="button" onclick="openCareerModal('Senior Civil Site Engineer')" class="px-5 py-2.5 rounded-full bg-[#0E1520] text-white text-xs font-bold uppercase tracking-wider hover:bg-[#162132]">
                    {{ __('Apply Now →') }}
                </button>
            </div>

            <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-[#0E1520]/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-mono uppercase bg-[#0E1520] text-white">{{ __('Design & CAD') }}</span>
                    <h3 class="font-serif text-xl font-bold text-[#0E1520] mt-1">{{ __('Lead Residential Architect') }}</h3>
                    <div class="text-xs text-[#0E1520]/60 font-mono mt-0.5">{{ __('Full Time · Sikar Road HQ · 4-7 Yrs Exp') }}</div>
                </div>
                <button type="button" onclick="openCareerModal('Lead Residential Architect')" class="px-5 py-2.5 rounded-full bg-[#0E1520] text-white text-xs font-bold uppercase tracking-wider hover:bg-[#162132]">
                    {{ __('Apply Now →') }}
                </button>
            </div>

            <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-[#0E1520]/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-mono uppercase bg-[#0E1520] text-white">{{ __('Sales & Advisory') }}</span>
                    <h3 class="font-serif text-xl font-bold text-[#0E1520] mt-1">{{ __('Senior Property Portfolio Manager') }}</h3>
                    <div class="text-xs text-[#0E1520]/60 font-mono mt-0.5">{{ __('Full Time · Bani Park Desk · 3-6 Yrs Exp') }}</div>
                </div>
                <button type="button" onclick="openCareerModal('Senior Property Portfolio Manager')" class="px-5 py-2.5 rounded-full bg-[#0E1520] text-white text-xs font-bold uppercase tracking-wider hover:bg-[#162132]">
                    {{ __('Apply Now →') }}
                </button>
            </div>

        </div>

    </div>
</section>

<!-- Career Application Modal -->
<div id="careerModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl relative border border-[#0E1520]/10">
        <button type="button" onclick="document.getElementById('careerModal').classList.add('hidden')" class="absolute top-5 right-5 text-gray-500 hover:text-black p-2">
            {{ __('×') }}
        </button>

        <h3 class="font-serif text-2xl font-bold text-[#0E1520] mb-1">{{ __('Submit Application') }}</h3>
        <p class="text-xs text-[#0E1520]/60 font-mono mb-4" id="careerRoleTitle">{{ __('Senior Civil Site Engineer') }}</p>

        <form action="{{ route('leads.store') }}" method="POST" class="space-y-4 font-mono text-xs">
            @csrf
            <input type="hidden" name="source" value="career_application">
            <div>
                <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Full Name *') }}</label>
                <input type="text" name="name" required placeholder="{{ __('e.g. Ramesh Meena') }}" class="w-full px-3 py-2 rounded-xl border border-gray-300">
            </div>
            <div>
                <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Phone *') }}</label>
                <input type="tel" name="phone" required placeholder="+91 98290 00000" class="w-full px-3 py-2 rounded-xl border border-gray-300">
            </div>
            <div>
                <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Email *') }}</label>
                <input type="email" name="email" required placeholder="ramesh@example.com" class="w-full px-3 py-2 rounded-xl border border-gray-300">
            </div>
            <div>
                <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Portfolio / LinkedIn URL') }}</label>
                <input type="text" name="message" placeholder="{{ __('https://linkedin.com/in/...') }}" class="w-full px-3 py-2 rounded-xl border border-gray-300">
            </div>

            <button type="submit" class="w-full py-3 rounded-full bg-[#D4E845] hover:bg-[#c2d733] text-[#0E1520] font-bold uppercase tracking-wider text-xs shadow mt-2">
                {{ __('Submit Candidacy →') }}
            </button>
        </form>
    </div>
</div>

<script>
    function openCareerModal(role) {
        document.getElementById('careerRoleTitle').innerText = role;
        document.getElementById('careerModal').classList.remove('hidden');
    }
</script>

@endsection
