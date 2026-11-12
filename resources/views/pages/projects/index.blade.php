@extends('layouts.app')

@section('title', 'All Projects & Townships | Kedia Builders & Colonizers')

@section('content')

<!-- Header Banner -->
<section class="pt-12 pb-10 bg-[#FAF8F5] border-b border-[#0E1520]/10">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <span class="text-xs font-mono uppercase tracking-widest text-[#0E1520]/60">{{ __('Signature Catalog') }}</span>
                <h1 class="font-serif text-4xl sm:text-5xl font-bold text-[#0E1520] tracking-tight">
                    {{ __('Projects & Master-Planned Townships') }}
                </h1>
                <p class="text-sm text-[#0E1520]/70 max-w-2xl">
                    {{ __('Explore our diverse portfolio of JDA-approved luxury villas, turnkey plots, and residential communities across Jaipur\'s key corridors.') }}
                </p>
            </div>
            <div class="flex items-center gap-3 font-mono text-xs text-[#0E1520]">
                <span class="px-3 py-1.5 rounded-full bg-white border border-[#0E1520]/10 font-bold">
                    {{ count($projects) }} Developments
                </span>
                <span class="px-3 py-1.5 rounded-full bg-[#D4E845] text-[#0E1520] font-bold">
                    {{ __('100% JDA Patta') }}
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search Toolbar -->
<section class="py-6 bg-white border-b border-[#0E1520]/10 sticky top-20 z-40 backdrop-blur-md bg-white/95">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        <form method="GET" action="{{ route('projects.index') }}" class="flex flex-wrap items-center justify-between gap-4">
            
            <!-- Type Filters -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('projects.index') }}" class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ !request('type') || request('type') == 'all' ? 'bg-[#0E1520] text-white shadow' : 'bg-[#FAF8F5] text-[#0E1520]/70 hover:text-[#0E1520]' }}">
                    All Types ({{ $stats['total'] }})
                </a>
                <a href="{{ route('projects.index', ['type' => 'villa']) }}" class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ request('type') == 'villa' ? 'bg-[#0E1520] text-white shadow' : 'bg-[#FAF8F5] text-[#0E1520]/70 hover:text-[#0E1520]' }}">
                    Villas ({{ $stats['villas'] }})
                </a>
                <a href="{{ route('projects.index', ['type' => 'plot']) }}" class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ request('type') == 'plot' ? 'bg-[#0E1520] text-white shadow' : 'bg-[#FAF8F5] text-[#0E1520]/70 hover:text-[#0E1520]' }}">
                    Plots ({{ $stats['plots'] }})
                </a>
                <a href="{{ route('projects.index', ['type' => 'apartment']) }}" class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ request('type') == 'apartment' ? 'bg-[#0E1520] text-white shadow' : 'bg-[#FAF8F5] text-[#0E1520]/70 hover:text-[#0E1520]' }}">
                    Apartments ({{ $stats['apartments'] }})
                </a>
            </div>

            <!-- Status & Sort Controls -->
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select name="status" onchange="this.form.submit()" class="px-3.5 py-2 rounded-full bg-[#FAF8F5] border border-[#0E1520]/15 text-xs font-semibold text-[#0E1520] focus:outline-none cursor-pointer">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('All Status') }}</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>{{ __('Under Construction') }}</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Ready to Move') }}</option>
                </select>

                <select name="sort" onchange="this.form.submit()" class="px-3.5 py-2 rounded-full bg-[#FAF8F5] border border-[#0E1520]/15 text-xs font-semibold text-[#0E1520] focus:outline-none cursor-pointer">
                    <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>{{ __('Sort: Featured') }}</option>
                    <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                    <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest Additions') }}</option>
                </select>
            </div>

        </form>
    </div>
</section>

<!-- Projects Grid -->
<section class="py-16 bg-[#FAF8F5]">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">
        
        @if($projects->isEmpty())
            <div class="text-center py-24 bg-white rounded-3xl border border-[#0E1520]/10 p-8">
                <div class="text-3xl mb-3">🔍</div>
                <h3 class="font-serif text-2xl font-bold text-[#0E1520]">{{ __('No projects found') }}</h3>
                <p class="text-xs text-[#0E1520]/60 mt-1 mb-6">{{ __('Try clearing your filters to see our full catalogue.') }}</p>
                <a href="{{ route('projects.index') }}" class="px-6 py-2.5 rounded-full bg-[#0E1520] text-white font-bold text-xs uppercase tracking-wider">
                    {{ __('Reset Filters') }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($projects as $project)
                    <div class="group rounded-3xl bg-white border border-[#0E1520]/10 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col justify-between relative">
                        <div>
                            <!-- Arch Frame Cover -->
                            <div class="relative aspect-[16/10] overflow-hidden bg-[#0E1520]">
                                <img fetchpriority="high" src="{{ $project->hero_image }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='/media/img/2021/04/Kedia-Nikunj-1-1920.webp'">
                                
                                <!-- Status Tag -->
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-mono uppercase tracking-wider font-semibold {{ $project->status === 'ongoing' ? 'bg-[#D4E845] text-[#0E1520]' : 'bg-emerald-500 text-white' }}">
                                        {{ $project->status === 'ongoing' ? 'Under Construction' : 'Ready to Move' }}
                                    </span>
                                </div>

                                <!-- Shortlist Heart Button -->
                                <button type="button" 
                                    onclick='addToShortlist({ id: "{{ $project->slug }}", title: "{{ $project->title }}", location: "{{ $project->location }}", price: "{{ $project->price_range }}", url: "{{ route('projects.show', $project->slug) }}" })'
                                    class="absolute top-4 right-4 w-8 h-8 rounded-full bg-black/60 hover:bg-rose-500 text-white flex items-center justify-center transition-colors shadow"
                                    title="Add to Shortlist">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>

                                <!-- RERA Code -->
                                <div class="absolute bottom-3 left-4 right-4 flex items-center justify-between text-[10px] font-mono text-white/90 bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-lg">
                                    <span>{{ $project->rera_number }}</span>
                                    <span class="text-[#D4E845]">{{ $project->available_units_count ?? 8 }} Units Available</span>
                                </div>
                            </div>

                            <!-- Body Details -->
                            <div class="p-6 space-y-3">
                                <div class="text-[11px] font-mono text-[#0E1520]/50 uppercase tracking-wider">
                                    {{ $project->location }}
                                </div>
                                <h2 class="font-serif text-2xl font-bold text-[#0E1520] group-hover:text-[#C8A24B] transition-colors">
                                    <a href="{{ route('projects.show', $project->slug) }}">{{ $project->title }}</a>
                                </h2>
                                <p class="text-xs text-[#0E1520]/70 line-clamp-2 leading-relaxed">
                                    {{ $project->description }}
                                </p>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="p-6 pt-0 border-t border-[#0E1520]/5 flex items-center justify-between mt-4">
                            <div>
                                <div class="text-[10px] font-mono text-[#0E1520]/50 uppercase">{{ __('Starting From') }}</div>
                                <div class="font-bold text-sm text-[#0E1520]">{{ $project->price_range }}</div>
                            </div>
                            <a href="{{ route('projects.show', $project->slug) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#FAF8F5] border border-[#0E1520]/15 text-xs font-bold text-[#0E1520] group-hover:bg-[#D4E845] group-hover:border-[#D4E845] transition-colors">
                                <span>{{ __('Inspect Township') }}</span>
                                <span>{{ __('→') }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>

@endsection
