@extends('layouts.app')

@section('title', $project->title . ' · ' . $project->location . ' | Kedia Builders & Colonizers')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $project->description) ?: ($project->title . ' by Kedia Builders & Colonizers in ' . $project->location . ', Jaipur.'), 155))
@section('og_image', $project->hero_image)

@push('structured_data')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'Residence',
    'name' => $project->title,
    'description' => \Illuminate\Support\Str::limit(strip_tags((string) $project->description), 300),
    'url' => url()->current(),
    'image' => url($project->hero_image),
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => $project->address ?: $project->location, 'addressLocality' => 'Jaipur', 'addressRegion' => 'Rajasthan', 'addressCountry' => 'IN'],
    'geo' => ($project->latitude && $project->longitude) ? ['@type' => 'GeoCoordinates', 'latitude' => (float) $project->latitude, 'longitude' => (float) $project->longitude] : null,
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endpush

@section('content')

<!-- Project Hero Banner -->
<section class="relative bg-[#0E1520] text-white pt-10 pb-16 overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24 relative z-10">
        
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs font-mono text-white/50 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white">{{ __('Home') }}</a>
            <span>/</span>
            <a href="{{ route('projects.index') }}" class="hover:text-white">{{ __('Townships') }}</a>
            <span>/</span>
            <span class="text-[#D4E845]">{{ $project->title }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <div class="lg:col-span-7 space-y-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-[10px] font-mono uppercase tracking-wider font-semibold {{ $project->status === 'ongoing' ? 'bg-[#D4E845] text-[#0E1520]' : 'bg-emerald-500 text-white' }}">
                        {{ $project->status === 'ongoing' ? 'Under Construction' : 'Ready to Move' }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-[10px] font-mono uppercase tracking-wider font-semibold bg-white/10 text-white border border-white/15">
                        {{ ucfirst($project->type) }} Community
                    </span>
                    <span class="px-3 py-1 rounded-full text-[10px] font-mono uppercase tracking-wider font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                        {{ __('100% JDA Approved') }}
                    </span>
                </div>

                <h1 class="font-serif text-4xl sm:text-6xl font-bold tracking-tight text-white">
                    {{ $project->title }}
                </h1>

                <p class="text-sm sm:text-base text-white/70 max-w-2xl leading-relaxed">
                    {{ $project->description }}
                </p>

                <div class="flex flex-wrap items-center gap-6 pt-2 font-mono text-xs">
                    <div>
                        <div class="text-white/40 uppercase text-[10px]">{{ __('Location') }}</div>
                        <div class="text-white font-bold text-sm">{{ $project->location }}</div>
                    </div>
                    <div class="border-l border-white/20 pl-6">
                        <div class="text-white/40 uppercase text-[10px]">{{ __('Price Starting') }}</div>
                        <div class="text-[#D4E845] font-bold text-sm">{{ $project->price_range }}</div>
                    </div>
                    <div class="border-l border-white/20 pl-6">
                        <div class="text-white/40 uppercase text-[10px]">{{ __('RERA Number') }}</div>
                        <div class="text-white font-bold text-sm">{{ $project->rera_number }}</div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4 pt-4">
                    <button type="button" onclick="openSiteVisitModal()" class="px-6 py-3.5 rounded-full bg-[#D4E845] hover:bg-[#c2d733] text-[#0E1520] font-bold text-xs uppercase tracking-wider transition-all shadow-lg">
                        {{ __('Schedule Site Visit →') }}
                    </button>
                    <a href="#inventorySection" class="px-6 py-3.5 rounded-full bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wider transition-colors border border-white/20">
                        View Live Units ({{ count($project->units) }})
                    </a>
                    @if($project->brochure_url)
                        <a href="{{ $project->brochure_url }}" target="_blank" rel="noopener" download class="px-6 py-3.5 rounded-full bg-transparent hover:bg-white/10 text-white font-bold text-xs uppercase tracking-wider transition-colors border border-white/20">
                            {{ __('Download Brochure (PDF)') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Hero Image Preview -->
            <div class="lg:col-span-5">
                <div class="rounded-3xl overflow-hidden border-2 border-white/10 shadow-2xl aspect-[4/3] bg-black/40">
                    <img fetchpriority="high" src="{{ $project->hero_image }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Township Cadastral Blueprint Component -->
<section class="py-16 bg-[#080E17]">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        <div class="mb-8">
            <span class="text-xs font-mono uppercase tracking-widest text-[#D4E845]">{{ __('Sector Blueprint') }}</span>
            <h2 class="font-serif text-3xl font-bold text-white mt-1">{{ __('Township Sector Layout & Cadastral Plots') }}</h2>
        </div>
        @include('components.living-blueprint', ['project' => $project])
    </div>
</section>

<!-- Unit Inventory Product Listing (E-Commerce Style Table) -->
<section id="inventorySection" class="py-20 bg-[#FAF8F5]">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Live Product Catalog') }}</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#0E1520] mt-1">Available Units in {{ $project->title }}</h2>
                <p class="text-xs sm:text-sm text-[#0E1520]/70 mt-1">{{ __('Every unit is an individually registered asset. Compare specifications and lock your booking with a test advance token.') }}</p>
            </div>
            <div class="flex items-center gap-3 text-xs font-mono">
                <span class="px-3 py-1.5 rounded-full bg-white border border-[#0E1520]/10 font-bold">
                    {{ $unitStats['available'] }} Available
                </span>
                <span class="px-3 py-1.5 rounded-full bg-amber-100 text-amber-800 font-bold">
                    {{ $unitStats['on_hold'] }} On Hold
                </span>
                <span class="px-3 py-1.5 rounded-full bg-slate-200 text-slate-800 font-bold">
                    {{ $unitStats['sold'] }} Sold
                </span>
            </div>
        </div>

        <!-- Inventory Units Table -->
        <div class="bg-white rounded-3xl border border-[#0E1520]/10 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#FAF8F5] border-b border-[#0E1520]/10 text-[10px] font-mono uppercase tracking-wider text-[#0E1520]/60">
                            <th class="py-4 px-6">{{ __('Unit Code') }}</th>
                            <th class="py-4 px-6">{{ __('Type & Config') }}</th>
                            <th class="py-4 px-6">{{ __('Carpet / Super Area') }}</th>
                            <th class="py-4 px-6">{{ __('Facing') }}</th>
                            <th class="py-4 px-6">{{ __('Floor / Block') }}</th>
                            <th class="py-4 px-6">{{ __('Price') }}</th>
                            <th class="py-4 px-6">{{ __('Status') }}</th>
                            <th class="py-4 px-6 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#0E1520]/5 font-mono">
                        @forelse($project->units as $unit)
                            <tr class="hover:bg-[#FAF8F5]/80 transition-colors">
                                <td class="py-4 px-6 font-bold text-sm text-[#0E1520]">
                                    <a href="{{ route('units.show', [$unit->project_slug, $unit->unit_code]) }}" class="hover:text-[#C8A24B] underline hover:no-underline">
                                        {{ $unit->unit_code }}
                                    </a>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-[#0E1520]">{{ $unit->title }}</div>
                                    <div class="text-[10px] text-[#0E1520]/50">{{ ucfirst($unit->type) }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-[#0E1520]">{{ $unit->area_sqft }} SQ.FT</div>
                                    @if($unit->plot_size_sqyd)
                                        <div class="text-[10px] text-[#0E1520]/50">Plot: {{ $unit->plot_size_sqyd }} SQ.YD</div>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-[#0E1520]/80">
                                    {{ $unit->facing ?? 'East Facing' }}
                                </td>
                                <td class="py-4 px-6 text-[#0E1520]/80">
                                    {{ $unit->floor ?? 'Ground + 1' }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-sm text-[#0E1520]">{{ $unit->price_formatted }}</div>
                                    <div class="text-[9px] text-[#0E1520]/40">{{ __('Excl. Registry/GST') }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] uppercase font-bold
                                        {{ $unit->status === 'available' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $unit->status === 'on_hold' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $unit->status === 'sold' ? 'bg-slate-200 text-slate-700' : '' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $unit->status === 'available' ? 'bg-emerald-500' : ($unit->status === 'on_hold' ? 'bg-amber-500' : 'bg-slate-400') }}"></span>
                                        <span>{{ str_replace('_', ' ', $unit->status) }}</span>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    @if($unit->status === 'available')
                                        <a href="{{ route('booking.checkout', $unit->id) }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-[#D4E845] hover:bg-[#c2d733] text-[#0E1520] font-bold text-[11px] uppercase tracking-wider transition-colors shadow">
                                            <span>{{ __('Book Now') }}</span>
                                            <span>{{ __('→') }}</span>
                                        </a>
                                    @elseif($unit->status === 'on_hold')
                                        <span class="inline-block px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-semibold border border-amber-200">
                                            {{ __('Under Holding') }}
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-semibold">
                                            {{ __('Sold Out') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-[#0E1520]/60">
                                    {{ __('No units logged yet for this township. Contact customer care for offline allotment.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<!-- Amenities & Technical Specs Grid -->
<section class="py-16 bg-white border-t border-[#0E1520]/10">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl bg-[#FAF8F5] border border-[#0E1520]/10">
                <div class="text-xl font-bold font-serif mb-4 text-[#0E1520]">{{ __('Sanctions & Legal') }}</div>
                <ul class="space-y-3 text-xs text-[#0E1520]/80 font-mono">
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> {{ __('100% Individual JDA Patta') }}</li>
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> RERA Registered: {{ $project->rera_number }}</li>
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> {{ __('Approved by all Leading Banks (SBI, HDFC, ICICI)') }}</li>
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> {{ __('Non-Encumbrance Title Guarantee') }}</li>
                </ul>
            </div>

            <div class="p-8 rounded-3xl bg-[#FAF8F5] border border-[#0E1520]/10">
                <div class="text-xl font-bold font-serif mb-4 text-[#0E1520]">{{ __('Infrastructure Standards') }}</div>
                <ul class="space-y-3 text-xs text-[#0E1520]/80 font-mono">
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> {{ __('40-60 Ft Wide Asphalt Sector Roads') }}</li>
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> {{ __('Underground Concealed Electrical Cabling') }}</li>
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> {{ __('Centralized Water Overhead Reservoir') }}</li>
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> {{ __('Rainwater Harvesting Recharge Pits') }}</li>
                </ul>
            </div>

            <div class="p-8 rounded-3xl bg-[#FAF8F5] border border-[#0E1520]/10">
                <div class="text-xl font-bold font-serif mb-4 text-[#0E1520]">{{ __('Lifestyle Amenities') }}</div>
                <ul class="space-y-3 text-xs text-[#0E1520]/80 font-mono">
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> {{ __('Gated Security & 24x7 CCTV Surveillance') }}</li>
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> {{ __('Clubhouse & Indoor Gymnasium') }}</li>
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> {{ __('Landscaped Thematic Gardens & Kids Play Area') }}</li>
                    <li class="flex items-center gap-2"><span class="text-emerald-600">✓</span> {{ __('Vedic Vastu Compliant Plot Alignment') }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

@endsection
