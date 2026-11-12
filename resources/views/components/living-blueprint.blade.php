@props([
    'project' => null,
    'units' => null,
])

@php
    $blueprintUnits = [
        ['code' => 'P-11', 'type' => 'Villa Plot', 'size' => '111 SQ.YD', 'price' => '₹ 32.83 Lakh', 'price_num' => 3283000, 'status' => 'available', 'facing' => 'East', 'dimensions' => '25 x 40 ft'],
        ['code' => 'P-12', 'type' => 'Villa Plot', 'size' => '138 SQ.YD', 'price' => '₹ 33.57 Lakh', 'price_num' => 3357000, 'status' => 'available', 'facing' => 'North-East', 'dimensions' => '28 x 44 ft'],
        ['code' => 'P-13', 'type' => 'Villa Plot', 'size' => '142 SQ.YD', 'price' => '₹ 34.51 Lakh', 'price_num' => 3451000, 'status' => 'available', 'facing' => 'North', 'dimensions' => '30 x 42 ft'],
        ['code' => 'P-14', 'type' => 'Villa Plot', 'size' => '148 SQ.YD', 'price' => '₹ 35.44 Lakh', 'price_num' => 3544000, 'status' => 'on_hold', 'facing' => 'North', 'dimensions' => '30 x 44 ft'],
        ['code' => 'P-15', 'type' => 'Luxury Villa', 'size' => '162 SQ.YD', 'price' => '₹ 48.20 Lakh', 'price_num' => 4820000, 'status' => 'sold', 'facing' => 'East', 'dimensions' => '32 x 45 ft'],
        ['code' => 'P-16', 'type' => 'Luxury Villa', 'size' => '180 SQ.YD', 'price' => '₹ 54.10 Lakh', 'price_num' => 5410000, 'status' => 'sold', 'facing' => 'Corner East-North', 'dimensions' => '35 x 46 ft'],
        ['code' => 'P-17', 'type' => 'Villa Plot', 'size' => '143 SQ.YD', 'price' => '₹ 34.17 Lakh', 'price_num' => 3417000, 'status' => 'available', 'facing' => 'West', 'dimensions' => '30 x 43 ft'],
        ['code' => 'P-18', 'type' => 'Villa Plot', 'size' => '146 SQ.YD', 'price' => '₹ 35.11 Lakh', 'price_num' => 3511000, 'status' => 'available', 'facing' => 'West', 'dimensions' => '30 x 44 ft'],
        ['code' => 'P-19', 'type' => 'Villa Plot', 'size' => '122 SQ.YD', 'price' => '₹ 30.05 Lakh', 'price_num' => 3005000, 'status' => 'available', 'facing' => 'West', 'dimensions' => '26 x 42 ft'],
        ['code' => 'P-20', 'type' => 'Villa Plot', 'size' => '156 SQ.YD', 'price' => '₹ 38.00 Lakh', 'price_num' => 3800000, 'status' => 'available', 'facing' => 'East', 'dimensions' => '32 x 44 ft'],
        ['code' => 'P-21', 'type' => 'Villa Plot', 'size' => '152 SQ.YD', 'price' => '₹ 37.01 Lakh', 'price_num' => 3701000, 'status' => 'available', 'facing' => 'East', 'dimensions' => '31 x 44 ft'],
        ['code' => 'P-22', 'type' => 'Villa Plot', 'size' => '148 SQ.YD', 'price' => '₹ 36.00 Lakh', 'price_num' => 3600000, 'status' => 'on_hold', 'facing' => 'East', 'dimensions' => '30 x 44 ft'],
        ['code' => 'P-23', 'type' => 'Villa Plot', 'size' => '151 SQ.YD', 'price' => '₹ 36.70 Lakh', 'price_num' => 3670000, 'status' => 'available', 'facing' => 'South', 'dimensions' => '30 x 45 ft'],
        ['code' => 'P-24', 'type' => 'Royal Villa', 'size' => '178 SQ.YD', 'price' => '₹ 42.72 Lakh', 'price_num' => 4272000, 'status' => 'available', 'facing' => 'Corner North-West', 'dimensions' => '35 x 46 ft'],
    ];
@endphp

<div id="livingBlueprintContainer" class="rounded-3xl bg-[#09111C] border border-[#D4E845]/20 p-6 sm:p-8 lg:p-10 shadow-2xl relative overflow-hidden text-white font-sans">
    
    <!-- Header & Interactive Controls -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-white/10 relative z-10">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono tracking-widest uppercase text-[#D4E845] mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>{{ __('The Living Blueprint · Live Sector Layout') }}</span>
            </div>
            <h3 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight text-white">
                {{ __('Cadastral Plot & Villa Master Plan') }}
            </h3>
            <p class="text-xs sm:text-sm text-white/60 mt-1 max-w-2xl">
                {{ __('Units glow brass on hover. Click any plot to inspect specifications, instant cost sheets, or proceed to direct online booking.') }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Mode Toggle: CAD Blueprint vs Drone Aerial -->
            <div class="bg-black/40 p-1 rounded-full border border-white/10 flex items-center text-xs font-mono">
                <button type="button" onclick="setBlueprintMode('cad')" id="btnCadMode" class="px-3.5 py-1.5 rounded-full bg-[#D4E845] text-[#0E1520] font-bold shadow transition-all">
                    {{ __('CAD Grid') }}
                </button>
                <button type="button" onclick="setBlueprintMode('aerial')" id="btnAerialMode" class="px-3.5 py-1.5 rounded-full text-white/70 hover:text-white transition-all">
                    {{ __('Drone Aerial') }}
                </button>
            </div>

            <!-- Legend Pills -->
            <div class="flex items-center gap-3 bg-white/5 px-4 py-2 rounded-full border border-white/10 text-[11px] font-mono">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span>
                    <span>{{ __('Available') }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-amber-400"></span>
                    <span>{{ __('On Hold') }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-slate-500"></span>
                    <span>{{ __('Sold') }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-[#C8A24B] shadow-sm"></span>
                    <span>{{ __('Hover') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Master Plan Layout Viewport -->
    <div id="masterPlanViewport" class="relative mt-8 rounded-2xl p-6 sm:p-10 border border-[#D4E845]/15 bg-[#070D16] min-h-[580px] flex flex-col justify-between overflow-x-auto transition-all duration-500" style="background-image: radial-gradient(rgba(212, 232, 69, 0.08) 1px, transparent 1px); background-size: 24px 24px;">
        
        <!-- Drone Aerial Background Layer (Hidden by default) -->
        <div id="aerialLayer" class="absolute inset-0 bg-cover bg-center opacity-0 pointer-events-none transition-opacity duration-500 rounded-2xl" style="background-image: url('/media/img/2021/04/Kedia-Nikunj-1-1920.webp');">
            <div class="absolute inset-0 bg-[#0E1520]/60 backdrop-blur-[1px]"></div>
        </div>

        <!-- Compass Rose & Cadastral Scale -->
        <div class="absolute top-6 right-8 flex flex-col items-center opacity-40 pointer-events-none font-mono text-[10px]">
            <div class="w-8 h-8 rounded-full border border-white/30 flex items-center justify-center font-bold text-white mb-1">
                N
            </div>
            <span>{{ __('1:200 CADASTRAL') }}</span>
        </div>

        <!-- Top Sector Avenue Road -->
        <div class="relative z-10 text-center font-mono text-[10px] tracking-widest uppercase text-white/30 border-b border-dashed border-white/10 pb-2 mb-6">
            {{ __('← MAIN AVENUE BOULEVARD · 60 FT WIDE SECTOR ROAD →') }}
        </div>

        <!-- Plot Matrix -->
        <div class="relative z-10 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 my-auto">
            
            <!-- Row 1: Plots 11 to 16 -->
            @foreach(array_slice($blueprintUnits, 0, 6) as $plot)
                <button type="button" 
                    onclick='openPlotDossier(@json($plot))'
                    class="group relative rounded-xl p-3 text-left border transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl 
                        {{ $plot['status'] === 'available' ? 'bg-[#0E1E2E]/80 border-emerald-500/40 hover:border-[#C8A24B] hover:bg-[#1A2E3B]' : '' }}
                        {{ $plot['status'] === 'on_hold' ? 'bg-[#2E2412]/80 border-amber-500/40 hover:border-[#C8A24B] hover:bg-[#3E3219]' : '' }}
                        {{ $plot['status'] === 'sold' ? 'bg-[#151D24]/60 border-slate-700 opacity-60 cursor-not-allowed' : '' }}">
                    <div class="flex items-center justify-between font-mono text-xs">
                        <span class="font-bold text-white group-hover:text-[#D4E845]">{{ $plot['code'] }}</span>
                        <span class="w-2 h-2 rounded-full {{ $plot['status'] === 'available' ? 'bg-emerald-400' : ($plot['status'] === 'on_hold' ? 'bg-amber-400' : 'bg-slate-500') }}"></span>
                    </div>
                    <div class="mt-2 text-[11px] font-mono text-white/60 group-hover:text-white">{{ $plot['size'] }}</div>
                    <div class="text-[11px] font-semibold text-[#D4E845] mt-0.5">{{ $plot['price'] }}</div>
                    <div class="mt-2 text-[9px] font-mono text-white/40 uppercase">{{ $plot['facing'] }}</div>
                </button>
            @endforeach

            <!-- Row 2: Plots 17-19, Central Club Park, Plots 20-22 -->
            <div class="col-span-2 sm:col-span-4 lg:col-span-6 grid grid-cols-1 lg:grid-cols-6 gap-4 my-4 items-center">
                
                <!-- Left Wing Plots (17-19) -->
                <div class="lg:col-span-2 grid grid-cols-3 gap-3">
                    @foreach(array_slice($blueprintUnits, 6, 3) as $plot)
                        <button type="button" 
                            onclick='openPlotDossier(@json($plot))'
                            class="group relative rounded-xl p-3 text-left border transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl 
                                {{ $plot['status'] === 'available' ? 'bg-[#0E1E2E]/80 border-emerald-500/40 hover:border-[#C8A24B] hover:bg-[#1A2E3B]' : '' }}
                                {{ $plot['status'] === 'on_hold' ? 'bg-[#2E2412]/80 border-amber-500/40 hover:border-[#C8A24B] hover:bg-[#3E3219]' : '' }}
                                {{ $plot['status'] === 'sold' ? 'bg-[#151D24]/60 border-slate-700 opacity-60 cursor-not-allowed' : '' }}">
                            <div class="flex items-center justify-between font-mono text-xs">
                                <span class="font-bold text-white group-hover:text-[#D4E845]">{{ $plot['code'] }}</span>
                                <span class="w-2 h-2 rounded-full {{ $plot['status'] === 'available' ? 'bg-emerald-400' : ($plot['status'] === 'on_hold' ? 'bg-amber-400' : 'bg-slate-500') }}"></span>
                            </div>
                            <div class="mt-2 text-[11px] font-mono text-white/60 group-hover:text-white">{{ $plot['size'] }}</div>
                            <div class="text-[11px] font-semibold text-[#D4E845] mt-0.5">{{ $plot['price'] }}</div>
                            <div class="mt-1 text-[9px] font-mono text-white/40 uppercase">{{ $plot['facing'] }}</div>
                        </button>
                    @endforeach
                </div>

                <!-- Central Haveli Club & Green Spine -->
                <div class="lg:col-span-2 rounded-2xl bg-gradient-to-br from-[#12241F] to-[#0A1813] border border-emerald-500/30 p-5 text-center flex flex-col items-center justify-center shadow-lg relative overflow-hidden group">
                    <div class="text-2xl mb-1 text-emerald-400 group-hover:scale-110 transition-transform">&#127963;</div>
                    <div class="font-serif font-bold text-base text-white tracking-wide">{{ __('HAVELI CLUBHOUSE') }}</div>
                    <div class="text-[11px] font-mono text-emerald-300/80 uppercase tracking-widest mt-0.5">{{ __('& Central Eco Greens') }}</div>
                    <div class="text-[10px] text-white/50 mt-1.5">{{ __('Infinity Pool · Gym · Vastu Temple') }}</div>
                </div>

                <!-- Right Wing Plots (20-22) -->
                <div class="lg:col-span-2 grid grid-cols-3 gap-3">
                    @foreach(array_slice($blueprintUnits, 9, 3) as $plot)
                        <button type="button" 
                            onclick='openPlotDossier(@json($plot))'
                            class="group relative rounded-xl p-3 text-left border transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl 
                                {{ $plot['status'] === 'available' ? 'bg-[#0E1E2E]/80 border-emerald-500/40 hover:border-[#C8A24B] hover:bg-[#1A2E3B]' : '' }}
                                {{ $plot['status'] === 'on_hold' ? 'bg-[#2E2412]/80 border-amber-500/40 hover:border-[#C8A24B] hover:bg-[#3E3219]' : '' }}
                                {{ $plot['status'] === 'sold' ? 'bg-[#151D24]/60 border-slate-700 opacity-60 cursor-not-allowed' : '' }}">
                            <div class="flex items-center justify-between font-mono text-xs">
                                <span class="font-bold text-white group-hover:text-[#D4E845]">{{ $plot['code'] }}</span>
                                <span class="w-2 h-2 rounded-full {{ $plot['status'] === 'available' ? 'bg-emerald-400' : ($plot['status'] === 'on_hold' ? 'bg-amber-400' : 'bg-slate-500') }}"></span>
                            </div>
                            <div class="mt-2 text-[11px] font-mono text-white/60 group-hover:text-white">{{ $plot['size'] }}</div>
                            <div class="text-[11px] font-semibold text-[#D4E845] mt-0.5">{{ $plot['price'] }}</div>
                            <div class="mt-1 text-[9px] font-mono text-white/40 uppercase">{{ $plot['facing'] }}</div>
                        </button>
                    @endforeach
                </div>

            </div>

            <!-- Row 3: Plots 23, 24 and repeat available -->
            @foreach(array_slice($blueprintUnits, 12, 2) as $plot)
                <button type="button" 
                    onclick='openPlotDossier(@json($plot))'
                    class="group relative rounded-xl p-3 text-left border transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl 
                        {{ $plot['status'] === 'available' ? 'bg-[#0E1E2E]/80 border-emerald-500/40 hover:border-[#C8A24B] hover:bg-[#1A2E3B]' : '' }}
                        {{ $plot['status'] === 'on_hold' ? 'bg-[#2E2412]/80 border-amber-500/40 hover:border-[#C8A24B] hover:bg-[#3E3219]' : '' }}
                        {{ $plot['status'] === 'sold' ? 'bg-[#151D24]/60 border-slate-700 opacity-60 cursor-not-allowed' : '' }}">
                    <div class="flex items-center justify-between font-mono text-xs">
                        <span class="font-bold text-white group-hover:text-[#D4E845]">{{ $plot['code'] }}</span>
                        <span class="w-2 h-2 rounded-full {{ $plot['status'] === 'available' ? 'bg-emerald-400' : ($plot['status'] === 'on_hold' ? 'bg-amber-400' : 'bg-slate-500') }}"></span>
                    </div>
                    <div class="mt-2 text-[11px] font-mono text-white/60 group-hover:text-white">{{ $plot['size'] }}</div>
                    <div class="text-[11px] font-semibold text-[#D4E845] mt-0.5">{{ $plot['price'] }}</div>
                    <div class="mt-1 text-[9px] font-mono text-white/40 uppercase">{{ $plot['facing'] }}</div>
                </button>
            @endforeach

            <!-- Repeat 4 sample plots to complete the bottom row symmetry -->
            @foreach(array_slice($blueprintUnits, 0, 4) as $plot)
                <button type="button" 
                    onclick='openPlotDossier(@json($plot))'
                    class="group relative rounded-xl p-3 text-left border transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl bg-[#0E1E2E]/80 border-emerald-500/40 hover:border-[#C8A24B] hover:bg-[#1A2E3B]">
                    <div class="flex items-center justify-between font-mono text-xs">
                        <span class="font-bold text-white group-hover:text-[#D4E845]">{{ $plot['code'] }}-B</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    </div>
                    <div class="mt-2 text-[11px] font-mono text-white/60 group-hover:text-white">{{ $plot['size'] }}</div>
                    <div class="text-[11px] font-semibold text-[#D4E845] mt-0.5">{{ $plot['price'] }}</div>
                    <div class="mt-1 text-[9px] font-mono text-white/40 uppercase">{{ $plot['facing'] }}</div>
                </button>
            @endforeach

        </div>

        <!-- Bottom Sector Road & Title Certifications -->
        <div class="relative z-10 pt-6 mt-6 border-t border-dashed border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] font-mono text-white/50">
            <div class="flex items-center gap-2">
                <span>{{ __('© Cadastral Coordinate System: JDA Sanction Zone Jaipur') }}</span>
                <span class="hidden sm:inline">·</span>
                <span class="text-[#D4E845]">{{ __('Scale 1:200') }}</span>
            </div>
            <div class="text-amber-400 font-semibold">
                {{ __('* 100% Clear Land Titles with JDA Patta & RERA Sanctions') }}
            </div>
        </div>

    </div>
</div>

<!-- Plot Dossier Modal (Triggered on Plot Click) -->
<div id="plotDossierModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-[#0E1520] border border-[#D4E845]/30 rounded-3xl p-6 sm:p-8 max-w-lg w-full text-white shadow-2xl transform scale-95 transition-transform duration-300 relative" id="plotDossierCard">
        <button type="button" onclick="closePlotDossier()" class="absolute top-5 right-5 text-white/50 hover:text-white p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="flex items-center gap-2 text-xs font-mono uppercase tracking-widest text-[#D4E845] mb-2">
            <span>{{ __('Architectural Plot Dossier') }}</span>
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            <span id="dossierStatusBadge" class="uppercase">{{ __('Available') }}</span>
        </div>

        <h3 id="dossierPlotCode" class="font-serif text-3xl font-bold text-white mb-1">{{ __('Plot P-11') }}</h3>
        <p id="dossierPlotType" class="text-xs text-white/60 mb-6">{{ __('Residential Villa Plot · JDA Approved Township') }}</p>

        <!-- Specs Grid -->
        <div class="grid grid-cols-2 gap-3 mb-6 bg-white/5 p-4 rounded-2xl border border-white/10 text-xs font-mono">
            <div>
                <div class="text-white/50 text-[10px] uppercase">{{ __('Plot Size') }}</div>
                <div id="dossierPlotSize" class="text-white font-bold text-sm mt-0.5">{{ __('111 SQ.YD') }}</div>
            </div>
            <div>
                <div class="text-white/50 text-[10px] uppercase">{{ __('Dimensions') }}</div>
                <div id="dossierPlotDimensions" class="text-white font-bold text-sm mt-0.5">{{ __('25 x 40 ft') }}</div>
            </div>
            <div>
                <div class="text-white/50 text-[10px] uppercase">{{ __('Vastu Facing') }}</div>
                <div id="dossierPlotFacing" class="text-white font-bold text-sm mt-0.5">{{ __('East Facing') }}</div>
            </div>
            <div>
                <div class="text-white/50 text-[10px] uppercase">{{ __('Total Cost Estimate') }}</div>
                <div id="dossierPlotPrice" class="text-[#D4E845] font-bold text-sm mt-0.5">{{ __('₹ 32.83 Lakh') }}</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
            {{-- The plots drawn on this plan are illustrative (they are not inventory rows),
                 so the button opens the real unit list instead of a checkout for a fake code. --}}
            <a id="dossierBookBtn" href="{{ ($project ?? null) ? route('projects.show', $project->slug) . '#inventorySection' : route('projects.index') }}" class="flex-1 py-3 px-4 rounded-full text-center text-xs font-bold uppercase tracking-wider bg-[#D4E845] hover:bg-[#c2d733] text-[#0E1520] transition-colors shadow-lg">
                {{ __('View Available Units →') }}
            </a>
            <button type="button" onclick="openSiteVisitModal()" class="py-3 px-4 rounded-full text-xs font-bold uppercase tracking-wider bg-white/10 hover:bg-white/20 text-white transition-colors border border-white/10">
                {{ __('Schedule Visit') }}
            </button>
        </div>
    </div>
</div>

<script>
    function setBlueprintMode(mode) {
        const aerialLayer = document.getElementById('aerialLayer');
        const btnCad = document.getElementById('btnCadMode');
        const btnAerial = document.getElementById('btnAerialMode');

        if (mode === 'aerial') {
            aerialLayer.classList.remove('opacity-0', 'pointer-events-none');
            aerialLayer.classList.add('opacity-40');
            btnAerial.className = 'px-3.5 py-1.5 rounded-full bg-[#D4E845] text-[#0E1520] font-bold shadow transition-all';
            btnCad.className = 'px-3.5 py-1.5 rounded-full text-white/70 hover:text-white transition-all';
        } else {
            aerialLayer.classList.add('opacity-0', 'pointer-events-none');
            aerialLayer.classList.remove('opacity-40');
            btnCad.className = 'px-3.5 py-1.5 rounded-full bg-[#D4E845] text-[#0E1520] font-bold shadow transition-all';
            btnAerial.className = 'px-3.5 py-1.5 rounded-full text-white/70 hover:text-white transition-all';
        }
    }

    function openPlotDossier(plot) {
        document.getElementById('dossierPlotCode').innerText = t('Plot') + ' ' + plot.code;
        document.getElementById('dossierPlotType').innerText = (plot.type || 'Villa Plot') + ' · JDA Approved Township';
        document.getElementById('dossierPlotSize').innerText = plot.size || '120 SQ.YD';
        document.getElementById('dossierPlotDimensions').innerText = plot.dimensions || '25 x 45 ft';
        document.getElementById('dossierPlotFacing').innerText = (plot.facing || 'East') + ' Facing';
        document.getElementById('dossierPlotPrice').innerText = plot.price || '₹ 35.00 Lakh';
        document.getElementById('dossierStatusBadge').innerText = t({available: 'Available', on_hold: 'On Hold', sold: 'Sold'}[plot.status] || 'Available');

        const bookBtn = document.getElementById('dossierBookBtn');
        if (plot.status === 'sold') {
            bookBtn.classList.add('opacity-50', 'pointer-events-none');
            bookBtn.innerText = t('Unit Reserved / Sold');
        } else {
            bookBtn.classList.remove('opacity-50', 'pointer-events-none');
            bookBtn.innerText = t('View Available Units →');
        }

        const modal = document.getElementById('plotDossierModal');
        const card = document.getElementById('plotDossierCard');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    }

    function closePlotDossier() {
        const modal = document.getElementById('plotDossierModal');
        const card = document.getElementById('plotDossierCard');
        modal.classList.add('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
    }
</script>
