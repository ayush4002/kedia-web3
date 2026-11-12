@extends('layouts.app')

@section('title', $unit->title . ' (' . $unit->unit_code . ') at ' . ($unit->project->title ?? 'Kedia Builders') . ' | Kedia Builders & Colonizers')
@section('meta_description', $unit->title . ' in ' . ($unit->project->title ?? 'a Kedia township') . ', ' . ($unit->project->location ?? 'Jaipur') . '. ' . \App\Support\Format::inr($unit->area_sqft) . ' sq ft, ' . (stripos((string) $unit->facing, 'facing') !== false ? $unit->facing : $unit->facing . ' facing') . ', all-inclusive ' . $unit->price_formatted . '. Check live availability and book online.')
@if($unit->project)
    @section('og_image', $unit->project->hero_image)
@endif

@section('content')

<section class="py-10 bg-[#FAF8F5]">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        
        <!-- Breadcrumb & Shortlist Trigger -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-2 text-xs font-mono text-[#0E1520]/60">
                <a href="{{ route('home') }}" class="hover:text-[#0E1520]">{{ __('Home') }}</a>
                <span>/</span>
                <a href="{{ route('projects.index') }}" class="hover:text-[#0E1520]">{{ __('Projects') }}</a>
                <span>/</span>
                @if($unit->project)
                    <a href="{{ route('projects.show', $unit->project->slug) }}" class="hover:text-[#0E1520]">{{ $unit->project->title }}</a>
                    <span>/</span>
                @endif
                <span class="font-bold text-[#0E1520]">Unit {{ $unit->unit_code }}</span>
            </div>

            <button type="button" 
                onclick='addToShortlist({ id: "unit-{{ $unit->id }}", title: "{{ $unit->title }} ({{ $unit->unit_code }})", location: "{{ $unit->project->title ?? "Jaipur" }}", price: "{{ $unit->price_formatted }}", url: "{{ route("units.show", [$unit->project_slug, $unit->unit_code]) }}" })'
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-[#0E1520]/15 text-xs font-mono font-semibold text-[#0E1520] hover:border-rose-400 hover:text-rose-600 transition-colors shadow-sm">
                <span>♥</span>
                <span>{{ __('Save to Shortlist') }}</span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left Column: Gallery & Blueprint Floor Plan -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- Main Unit Visual Card -->
                <div class="rounded-3xl bg-[#0E1520] p-4 border border-[#0E1520]/10 shadow-2xl relative overflow-hidden">
                    <div class="relative aspect-[16/10] rounded-2xl overflow-hidden bg-black/50">
                        <img fetchpriority="high" src="{{ $unit->project->hero_image ?? '/media/img/2021/04/Kedia-Nikunj-1-1920.webp' }}" alt="Unit {{ $unit->unit_code }}" class="w-full h-full object-cover">
                        
                        <!-- Floating Unit Badge -->
                        <div class="absolute top-4 left-4 bg-black/70 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/20 text-white font-mono text-xs flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $unit->status === 'available' ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                            <span class="uppercase tracking-wider font-bold">{{ $unit->unit_code }}</span>
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold uppercase tracking-wider
                                {{ $unit->status === 'available' ? 'bg-emerald-500 text-white' : ($unit->status === 'on_hold' ? 'bg-amber-400 text-[#0E1520]' : 'bg-slate-500 text-white') }}">
                                {{ str_replace('_', ' ', $unit->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Architectural Floor Plan Preview (Blueprint Style) -->
                <div class="rounded-3xl bg-[#09111C] p-8 border border-[#D4E845]/20 text-white shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-[10px] font-mono uppercase tracking-widest text-[#D4E845]">{{ __('Engineering Blueprint') }}</div>
                            <h3 class="font-serif text-2xl font-bold">{{ __('2D Architectural Floor Layout') }}</h3>
                        </div>
                        <span class="font-mono text-xs text-white/50">{{ __('Scale 1:50') }}</span>
                    </div>

                    <!-- Blueprint SVG Mock Drawing -->
                    <div class="rounded-2xl border border-dashed border-[#D4E845]/30 p-8 bg-[#060B12] text-center my-4 relative" style="background-image: radial-gradient(rgba(212, 232, 69, 0.1) 1px, transparent 1px); background-size: 20px 20px;">
                        <svg class="w-full max-w-md mx-auto h-48 stroke-[#D4E845]/70 fill-none" viewBox="0 0 400 200">
                            <!-- Outer Boundary -->
                            <rect x="20" y="20" width="360" height="160" stroke-width="2"></rect>
                            <!-- Master Bedroom -->
                            <rect x="20" y="20" width="160" height="100" stroke-width="1.5"></rect>
                            <text x="50" y="70" fill="#D4E845" font-size="10" font-family="monospace">{{ __('MASTER BEDROOM') }}</text>
                            <text x="65" y="85" fill="white" font-size="8" opacity="0.6">14' x 16'</text>
                            <!-- Living Lounge -->
                            <rect x="180" y="20" width="200" height="100" stroke-width="1.5"></rect>
                            <text x="230" y="70" fill="#D4E845" font-size="10" font-family="monospace">{{ __('LIVING & DINING') }}</text>
                            <text x="255" y="85" fill="white" font-size="8" opacity="0.6">18' x 22'</text>
                            <!-- Modular Kitchen -->
                            <rect x="20" y="120" width="120" height="60" stroke-width="1.5"></rect>
                            <text x="35" y="155" fill="#D4E845" font-size="9" font-family="monospace">{{ __('KITCHEN') }}</text>
                            <!-- Vastu Deck -->
                            <rect x="140" y="120" width="240" height="60" stroke-width="1.5"></rect>
                            <text x="210" y="155" fill="#D4E845" font-size="9" font-family="monospace">{{ __('PRIVATE COURTYARD & LAWN') }}</text>
                        </svg>
                        <div class="mt-2 font-mono text-[10px] text-white/50">{{ __('Vedic Vastu Aligned with North-East Entrance') }}</div>
                    </div>
                </div>

                <!-- Specifications Matrix -->
                <div class="bg-white rounded-3xl p-8 border border-[#0E1520]/10 shadow-sm space-y-6">
                    <h3 class="font-serif text-2xl font-bold text-[#0E1520]">{{ __('Unit Dossier & Specifications') }}</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 font-mono text-xs">
                        <div>
                            <div class="text-[#0E1520]/50 text-[10px] uppercase">{{ __('Property Type') }}</div>
                            <div class="font-bold text-sm text-[#0E1520] mt-0.5">{{ ucfirst($unit->type) }}</div>
                        </div>
                        <div>
                            <div class="text-[#0E1520]/50 text-[10px] uppercase">{{ __('Super Built-Up Area') }}</div>
                            <div class="font-bold text-sm text-[#0E1520] mt-0.5">{{ $unit->area_sqft }} SQ.FT</div>
                        </div>
                        <div>
                            <div class="text-[#0E1520]/50 text-[10px] uppercase">{{ __('Plot Size (Cadastral)') }}</div>
                            <div class="font-bold text-sm text-[#0E1520] mt-0.5">{{ $unit->plot_size_sqyd ?? '125' }} SQ.YD</div>
                        </div>
                        <div>
                            <div class="text-[#0E1520]/50 text-[10px] uppercase">{{ __('Vastu Facing') }}</div>
                            <div class="font-bold text-sm text-[#0E1520] mt-0.5">{{ $unit->facing ?? 'East Facing' }}</div>
                        </div>
                        <div>
                            <div class="text-[#0E1520]/50 text-[10px] uppercase">{{ __('Bedrooms / Baths') }}</div>
                            <div class="font-bold text-sm text-[#0E1520] mt-0.5">{{ $unit->bedrooms ?? 3 }} BHK / {{ $unit->bathrooms ?? 3 }} Baths</div>
                        </div>
                        <div>
                            <div class="text-[#0E1520]/50 text-[10px] uppercase">{{ __('Floor Level') }}</div>
                            <div class="font-bold text-sm text-[#0E1520] mt-0.5">{{ $unit->floor ?? 'Ground + 1' }}</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Live E-Commerce Cost Sheet & Booking Flow -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="bg-white rounded-3xl p-8 border border-[#0E1520]/10 shadow-xl sticky top-28 space-y-6">
                    
                    <div>
                        <div class="text-[10px] font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Transparent Valuation') }}</div>
                        <h1 class="font-serif text-3xl font-bold text-[#0E1520] mt-1">{{ $unit->title }}</h1>
                        <div class="text-xs text-[#0E1520]/60 font-mono mt-0.5">Township: {{ $unit->project->title ?? 'Kedia Community' }}</div>
                    </div>

                    <!-- Price Breakup Card -->
                    <div class="bg-[#FAF8F5] rounded-2xl p-5 border border-[#0E1520]/10 space-y-3 font-mono text-xs">
                        @php
                            $priceRows = [
                                ['Base Agreement Value', $pricing['base_price']],
                                ['Infrastructure Development Charges', $pricing['development_charges']],
                                ['Club Membership', $pricing['club_charges']],
                                ['Parking', $pricing['parking_charges']],
                                ['GST', $pricing['gst']],
                                ['Govt Stamp Duty & Registration (est.)', $pricing['stamp_duty_estimate']],
                                ['Other Charges', $pricing['other_charges']],
                            ];
                        @endphp
                        @foreach($priceRows as [$label, $amount])
                            @if($amount != 0 || $loop->first)
                                <div class="flex items-center justify-between {{ $loop->first ? 'pb-2 border-b border-[#0E1520]/10' : '' }}">
                                    <span class="text-[#0E1520]/70">{{ $label }}:</span>
                                    <span class="{{ $loop->first ? 'font-bold' : 'font-semibold' }} text-[#0E1520]">₹ {{ \App\Support\Format::inr($amount) }}</span>
                                </div>
                            @endif
                        @endforeach
                        @if($pricing['gst'] == 0)
                            <div class="text-[10px] text-[#0E1520]/50">{{ __('No GST applies to this unit.') }}</div>
                        @endif
                        <div class="border-b border-[#0E1520]/10"></div>
                        <div class="flex items-center justify-between pt-1 text-sm font-bold">
                            <span class="text-[#0E1520]">{{ __('Estimated All-Inclusive Cost:') }}</span>
                            <span class="text-[#0E1520] font-serif text-lg">₹ {{ \App\Support\Format::inr($pricing['grand_total']) }}</span>
                        </div>
                    </div>

                    <!-- Token Advance Required Today -->
                    <div class="p-4 rounded-2xl bg-[#0E1520] text-white flex items-center justify-between">
                        <div>
                            <div class="text-[10px] font-mono text-[#D4E845] uppercase tracking-wider">{{ __('Advance Token Online (5%)') }}</div>
                            <div class="text-xl font-bold font-serif">₹ {{ \App\Support\Format::inr($pricing['token_amount']) }}</div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-mono bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                            {{ __('Refundable 15 Days') }}
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3 pt-2">
                        @if($unit->status === 'available')
                            <a href="{{ route('booking.checkout', $unit->id) }}" class="block w-full py-4 rounded-full bg-[#D4E845] hover:bg-[#c2d733] text-[#0E1520] font-bold text-center text-xs uppercase tracking-wider transition-all shadow-lg transform hover:-translate-y-0.5">
                                {{ __('Book Unit Now (Test Token) →') }}
                            </a>
                        @elseif($unit->status === 'on_hold')
                            <button disabled class="w-full py-4 rounded-full bg-amber-100 text-amber-800 font-bold text-center text-xs uppercase tracking-wider cursor-not-allowed">
                                {{ __('Unit Under Holding') }}
                            </button>
                        @else
                            <button disabled class="w-full py-4 rounded-full bg-slate-200 text-slate-600 font-bold text-center text-xs uppercase tracking-wider cursor-not-allowed">
                                {{ __('Unit Sold Out') }}
                            </button>
                        @endif

                        <button type="button" onclick="openSiteVisitModal()" class="w-full py-3.5 rounded-full bg-[#FAF8F5] hover:bg-[#0E1520] text-[#0E1520] hover:text-white border border-[#0E1520]/15 font-bold text-center text-xs uppercase tracking-wider transition-colors">
                            {{ __('Schedule Private Site Visit') }}
                        </button>
                    </div>

                    <!-- Security & Transparency Assurances -->
                    <div class="pt-4 border-t border-[#0E1520]/10 text-[11px] font-mono text-[#0E1520]/60 space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-600">✓</span> {{ __('Instant provisional reservation voucher generated') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-600">✓</span> {{ __('Unit held exclusively for 72 hours upon token payment') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-600">✓</span> {{ __('Direct relationship manager assigned within 15 minutes') }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

@endsection
