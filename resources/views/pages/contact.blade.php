@extends('layouts.app')

@section('title', 'Contact Us | Kedia Builders & Colonizers Jaipur')

@section('content')

<section class="py-16 bg-[#FAF8F5] border-b border-[#0E1520]/10">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        <div class="max-w-3xl space-y-4">
            <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Headquarters & Site Desks') }}</span>
            <h1 class="font-serif text-4xl sm:text-6xl font-bold text-[#0E1520] tracking-tight">
                {{ __('Connect With Our Jaipur Headquarters') }}
            </h1>
            <p class="text-base text-[#0E1520]/70 leading-relaxed font-normal">
                {{ __('Visit our central corporate office on Sikar Road or Bani Park, schedule an on-site property tour, or speak directly with our senior leadership team.') }}
            </p>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Left 5 Cols: Contact Information -->
            <div class="lg:col-span-5 space-y-8 font-mono text-xs">
                
                <div class="p-8 rounded-3xl bg-[#0E1520] text-white shadow-xl space-y-6">
                    <div class="text-[10px] uppercase tracking-widest text-[#D4E845]">{{ __('Jaipur Corporate Office') }}</div>
                    
                    <div>
                        <div class="text-white/50 text-[10px] uppercase">{{ __('Corporate Address:') }}</div>
                        <div class="text-white font-bold text-sm leading-relaxed mt-1">
                            {{ __('Kedia Builders & Colonizers') }}<br>
                            {{ __('Sikar Road & Bani Park, Jaipur, Rajasthan 302016') }}
                        </div>
                    </div>

                    <div class="pt-2 border-t border-white/10 space-y-4">
                        <div>
                            <div class="text-white/50 text-[10px] uppercase">{{ __('Central Booking Desk:') }}</div>
                            <a href="tel:+918302500500" class="text-lg font-bold text-[#D4E845] hover:underline block mt-0.5">
                                +91 8302 500 500
                            </a>
                        </div>

                        <div>
                            <div class="text-white/50 text-[10px] uppercase">{{ __('WhatsApp Executive Desk:') }}</div>
                            <a href="https://wa.me/919829184872" target="_blank" class="text-sm font-semibold text-white hover:text-[#D4E845] block mt-0.5">
                                +91 98291 84872
                            </a>
                        </div>

                        <div>
                            <div class="text-white/50 text-[10px] uppercase">{{ __('Official Email:') }}</div>
                            <a href="mailto:kediabuildersandcolonizers@gmail.com" class="text-xs text-white/80 hover:text-white block mt-0.5 break-all">
                                kediabuildersandcolonizers@gmail.com
                            </a>
                        </div>

                        <div>
                            <div class="text-white/50 text-[10px] uppercase">{{ __('Hours of Operation:') }}</div>
                            <div class="text-xs text-white/80 mt-0.5">{{ __('Monday - Sunday: 09:30 AM – 07:00 PM IST') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Google Map Preview -->
                <div class="rounded-3xl overflow-hidden border border-[#0E1520]/10 shadow-sm aspect-[16/10] bg-[#0E1520]/5 relative">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d113885.67232230357!2d75.71765476722886!3d26.905786481912803!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db47000000001%3A0x868b4d8d17b4430e!2sJaipur%2C%20Rajasthan!5e0!3m2!1sen!2sin!4v1700000000000" 
                        class="w-full h-full border-0" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>

            </div>

            <!-- Right 7 Cols: Inquiry Form -->
            <div class="lg:col-span-7 bg-[#FAF8F5] p-8 sm:p-12 rounded-3xl border border-[#0E1520]/10 shadow-sm space-y-6">
                <div>
                    <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Dispatch A Message') }}</span>
                    <h2 class="font-serif text-3xl font-bold text-[#0E1520] mt-1">{{ __('Send Direct Inquiry') }}</h2>
                    <p class="text-xs text-[#0E1520]/60 font-mono mt-1">{{ __('Our sales directorate will respond within 2 business hours.') }}</p>
                </div>

                <form action="{{ route('leads.store') }}" method="POST" class="space-y-4 font-mono text-xs">
                    @csrf
                    <input type="hidden" name="source" value="contact_page">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Your Full Name *') }}</label>
                            <input type="text" name="name" required placeholder="{{ __('e.g. Vikram Sharma') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#0E1520]/20 bg-white">
                        </div>
                        <div>
                            <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Mobile (WhatsApp) *') }}</label>
                            <input type="tel" name="phone" required placeholder="+91 98290 00000" class="w-full px-4 py-2.5 rounded-xl border border-[#0E1520]/20 bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Email Address') }}</label>
                            <input type="email" name="email" placeholder="vikram@example.com" class="w-full px-4 py-2.5 rounded-xl border border-[#0E1520]/20 bg-white">
                        </div>
                        <div>
                            <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Project of Interest') }}</label>
                            <select name="project_name" class="w-full px-4 py-2.5 rounded-xl border border-[#0E1520]/20 bg-white">
                                <option value="Kedia's Landmark">{{ __('Kedia\'s Landmark (Ajmer Road)') }}</option>
                                <option value="Kedia's Atulyam">{{ __('Kedia\'s Atulyam (Sirsi Road)') }}</option>
                                <option value="Kedia's Tulsi">{{ __('Kedia\'s Tulsi (Bani Park)') }}</option>
                                <option value="Ganpati Greens">{{ __('Ganpati Greens (Sikar Road)') }}</option>
                                <option value="Nikunj Villas">{{ __('Nikunj Villas (Sirsi Road)') }}</option>
                                <option value="Kedia Aravali">{{ __('Kedia Aravali Plots') }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[#0E1520]/70 uppercase mb-1">{{ __('Message or Specific Query') }}</label>
                        <textarea name="message" rows="4" placeholder="{{ __('Let us know your requirements, preferred plot sizes, or questions regarding JDA patta.') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#0E1520]/20 bg-white"></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-full bg-[#0E1520] hover:bg-[#162132] text-white font-bold text-xs uppercase tracking-wider transition-all shadow-md">
                        {{ __('Transmit Inquiry to Headquarters →') }}
                    </button>
                </form>
            </div>

        </div>

    </div>
</section>

@endsection
