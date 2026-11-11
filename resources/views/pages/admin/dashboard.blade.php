@extends('layouts.app')

@section('title', 'Admin Command Center | Kedia Builders E-Commerce')

@section('content')

<section class="py-10 bg-[#FAF8F5]">
    <div class="max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 2xl:px-24 space-y-10">
        
        <!-- Header & Quick Export Controls -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-[#0E1520]/10">
            <div>
                <div class="flex items-center gap-2 text-xs font-mono uppercase tracking-widest text-amber-600 font-bold mb-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Kedia E-Commerce Command Center</span>
                </div>
                <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#0E1520]">
                    Real Estate Inventory &amp; Order Operations
                </h1>
                <p class="text-xs text-[#0E1520]/60 font-mono mt-1">Live administration of 16 signature projects, inventory status, and online token reservations.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.export.orders') }}" class="px-4 py-2.5 rounded-full bg-white border border-[#0E1520]/15 hover:border-[#0E1520] text-xs font-mono font-bold text-[#0E1520] shadow-sm transition-all flex items-center gap-2">
                    <span>📥</span>
                    <span>Export Orders (CSV)</span>
                </a>
                <a href="{{ route('admin.export.catalog') }}" class="px-4 py-2.5 rounded-full bg-white border border-[#0E1520]/15 hover:border-[#0E1520] text-xs font-mono font-bold text-[#0E1520] shadow-sm transition-all flex items-center gap-2">
                    <span>📊</span>
                    <span>Export Catalog (JSON)</span>
                </a>
                <button type="button" onclick="document.getElementById('addUnitModal').classList.remove('hidden')" class="px-5 py-2.5 rounded-full bg-[#D4E845] hover:bg-[#c2d733] text-xs font-bold text-[#0E1520] uppercase tracking-wider shadow transition-all">
                    + Add New Unit
                </button>
            </div>
        </div>

        <!-- 6 KPI Revenue & Inventory Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 font-mono">
            
            <div class="p-5 rounded-2xl bg-[#0E1520] text-white shadow">
                <div class="text-[10px] text-white/50 uppercase">Total Inventory GMV</div>
                <div class="font-serif text-xl sm:text-2xl font-bold text-[#D4E845] mt-1">
                    ₹ {{ number_format($totalGMV / 10000000, 2) }} Cr
                </div>
                <div class="text-[9px] text-white/40 mt-1">Contract value in pipeline</div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#0E1520]/10 shadow-sm">
                <div class="text-[10px] text-[#0E1520]/50 uppercase">Token Collections</div>
                <div class="font-serif text-xl sm:text-2xl font-bold text-emerald-600 mt-1">
                    ₹ {{ number_format($tokenRevenue / 100000, 2) }} L
                </div>
                <div class="text-[9px] text-[#0E1520]/40 mt-1">{{ $totalBookingsCount }} Online tokens received</div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#0E1520]/10 shadow-sm">
                <div class="text-[10px] text-[#0E1520]/50 uppercase">Total Units</div>
                <div class="font-serif text-xl sm:text-2xl font-bold text-[#0E1520] mt-1">
                    {{ $totalUnits }}
                </div>
                <div class="text-[9px] text-[#0E1520]/40 mt-1">Across 16 townships</div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-emerald-500/20 shadow-sm">
                <div class="text-[10px] text-emerald-700 uppercase">Available Units</div>
                <div class="font-serif text-xl sm:text-2xl font-bold text-emerald-600 mt-1">
                    {{ $availableUnits }}
                </div>
                <div class="text-[9px] text-emerald-700/60 mt-1">Ready for instant booking</div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-amber-500/20 shadow-sm">
                <div class="text-[10px] text-amber-700 uppercase">On Hold</div>
                <div class="font-serif text-xl sm:text-2xl font-bold text-amber-600 mt-1">
                    {{ $onHoldUnits }}
                </div>
                <div class="text-[9px] text-amber-700/60 mt-1">Token reserved / Agreement</div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#0E1520]/10 shadow-sm">
                <div class="text-[10px] text-slate-500 uppercase">Sold Out</div>
                <div class="font-serif text-xl sm:text-2xl font-bold text-slate-700 mt-1">
                    {{ $soldUnits }}
                </div>
                <div class="text-[9px] text-slate-400 mt-1">Registry completed</div>
            </div>

        </div>

        <!-- Section 1: Recent Online Bookings -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#0E1520]/10 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#0E1520]/10">
                <div>
                    <h2 class="font-serif text-2xl font-bold text-[#0E1520]">Recent Online Reservations</h2>
                    <p class="text-xs text-[#0E1520]/60 font-mono">Live customer orders with token payments &amp; contact dossiers.</p>
                </div>
                <span class="text-xs font-mono font-bold text-[#0E1520]">{{ count($recentBookings) }} Orders</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs font-mono">
                    <thead>
                        <tr class="bg-[#FAF8F5] border-b border-[#0E1520]/10 text-[10px] text-[#0E1520]/60 uppercase">
                            <th class="py-3 px-4">Booking ID</th>
                            <th class="py-3 px-4">Customer Name</th>
                            <th class="py-3 px-4">Contact</th>
                            <th class="py-3 px-4">Project &amp; Unit</th>
                            <th class="py-3 px-4">Token Paid</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#0E1520]/5">
                        @forelse($recentBookings as $b)
                            <tr class="hover:bg-[#FAF8F5]">
                                <td class="py-3 px-4 font-bold text-[#0E1520]">{{ $b->booking_id }}</td>
                                <td class="py-3 px-4">{{ $b->customer_name }}</td>
                                <td class="py-3 px-4 text-[#0E1520]/70">{{ $b->customer_phone }}</td>
                                <td class="py-3 px-4">
                                    <span class="font-bold text-[#0E1520]">{{ $b->unit->unit_code ?? 'N/A' }}</span>
                                    <span class="text-[#0E1520]/50 block text-[10px]">{{ $b->project->title ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-4 font-bold text-emerald-700">₹ {{ number_format($b->token_amount) }}</td>
                                <td class="py-3 px-4">
                                    <form action="{{ route('admin.bookings.status', $b->id) }}" method="POST">
                                        @csrf
                                        <select name="booking_status" onchange="this.form.submit()" class="px-2 py-1 rounded bg-[#FAF8F5] border border-[#0E1520]/20 text-[10px] font-bold">
                                            <option value="confirmed" {{ $b->booking_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="holding" {{ $b->booking_status === 'holding' ? 'selected' : '' }}>Holding</option>
                                            <option value="cancelled" {{ $b->booking_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('booking.confirmation', $b->booking_id) }}" target="_blank" class="text-blue-700 hover:underline">
                                        View Voucher &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-[#0E1520]/50">No reservations placed yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 2: Full Inventory Unit Manager (Live Status & Price Editing) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#0E1520]/10 shadow-sm space-y-6">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-[#0E1520]/10">
                <div>
                    <h2 class="font-serif text-2xl font-bold text-[#0E1520]">Inventory Unit Manager</h2>
                    <p class="text-xs text-[#0E1520]/60 font-mono">Toggle unit availability (Available / On Hold / Sold) or edit pricing in real-time.</p>
                </div>

                <!-- Project Filter Dropdown -->
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <select name="project" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl bg-[#FAF8F5] border border-[#0E1520]/20 text-xs font-mono font-bold text-[#0E1520] cursor-pointer">
                        <option value="all">All 16 Townships</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->slug }}" {{ request('project') === $p->slug ? 'selected' : '' }}>
                                {{ $p->title }} ({{ $p->units_count }} units)
                            </option>
                        @endforeach
                    </select>

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search unit code..." class="px-3 py-2 rounded-xl bg-[#FAF8F5] border border-[#0E1520]/20 text-xs font-mono text-[#0E1520]">
                </form>
            </div>

            <!-- Units Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs font-mono">
                    <thead>
                        <tr class="bg-[#FAF8F5] border-b border-[#0E1520]/10 text-[10px] text-[#0E1520]/60 uppercase">
                            <th class="py-3 px-4">Unit Code</th>
                            <th class="py-3 px-4">Township</th>
                            <th class="py-3 px-4">Type &amp; Area</th>
                            <th class="py-3 px-4">Base Price</th>
                            <th class="py-3 px-4">Status Toggle</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#0E1520]/5">
                        @foreach($units as $u)
                            <tr class="hover:bg-[#FAF8F5]">
                                <td class="py-3 px-4 font-bold text-sm text-[#0E1520]">
                                    <a href="{{ route('units.show', $u->unit_code) }}" target="_blank" class="hover:text-blue-700 underline">
                                        {{ $u->unit_code }}
                                    </a>
                                </td>
                                <td class="py-3 px-4">{{ $u->project->title ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold">{{ $u->title }}</div>
                                    <div class="text-[10px] text-[#0E1520]/50">{{ $u->area_sqft }} SQ.FT · {{ $u->facing ?? 'East' }}</div>
                                </td>
                                <td class="py-3 px-4 font-bold text-[#0E1520]">
                                    {{ $u->price_formatted }}
                                </td>
                                <td class="py-3 px-4">
                                    <!-- Direct Status Toggle Form -->
                                    <form action="{{ route('admin.units.status', $u->id) }}" method="POST">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase cursor-pointer
                                            {{ $u->status === 'available' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : '' }}
                                            {{ $u->status === 'on_hold' ? 'bg-amber-100 text-amber-800 border border-amber-300' : '' }}
                                            {{ $u->status === 'sold' ? 'bg-slate-200 text-slate-700 border border-slate-300' : '' }}">
                                            <option value="available" {{ $u->status === 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="on_hold" {{ $u->status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                            <option value="sold" {{ $u->status === 'sold' ? 'selected' : '' }}>Sold</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.units.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove unit {{ $u->unit_code }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-bold">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $units->appends(request()->query())->links() }}
            </div>

        </div>

        <!-- Section 3: Inbound Site Visit Leads -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#0E1520]/10 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#0E1520]/10">
                <div>
                    <h2 class="font-serif text-2xl font-bold text-[#0E1520]">Inbound Site Visit &amp; Callback Leads</h2>
                    <p class="text-xs text-[#0E1520]/60 font-mono">Real-time requests received via website and VIP schedule forms.</p>
                </div>
                <span class="text-xs font-mono font-bold text-[#0E1520]">{{ count($recentLeads) }} Leads</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs font-mono">
                    <thead>
                        <tr class="bg-[#FAF8F5] border-b border-[#0E1520]/10 text-[10px] text-[#0E1520]/60 uppercase">
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Visitor Name</th>
                            <th class="py-3 px-4">Phone</th>
                            <th class="py-3 px-4">Project</th>
                            <th class="py-3 px-4">Visit Date &amp; Slot</th>
                            <th class="py-3 px-4">Pickup</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#0E1520]/5">
                        @forelse($recentLeads as $l)
                            <tr class="hover:bg-[#FAF8F5]">
                                <td class="py-3 px-4 text-[#0E1520]/60">{{ $l->created_at->format('M d, H:i') }}</td>
                                <td class="py-3 px-4 font-bold text-[#0E1520]">{{ $l->name }}</td>
                                <td class="py-3 px-4"><a href="tel:{{ $l->phone }}" class="text-blue-700 hover:underline">{{ $l->phone }}</a></td>
                                <td class="py-3 px-4">{{ $l->project_name }}</td>
                                <td class="py-3 px-4">{{ $l->preferred_date }} · {{ $l->time_slot }}</td>
                                <td class="py-3 px-4">
                                    @if($l->pickup_requested)
                                        <span class="text-emerald-700 font-bold">Yes (Chauffeur)</span>
                                    @else
                                        <span class="text-[#0E1520]/40">Self</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-[#0E1520]/50">No site visit leads logged yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<!-- Add Unit Modal -->
<div id="addUnitModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative border border-[#0E1520]/10">
        <button type="button" onclick="document.getElementById('addUnitModal').classList.add('hidden')" class="absolute top-5 right-5 text-gray-500 hover:text-black p-2">
            &times;
        </button>

        <h3 class="font-serif text-2xl font-bold text-[#0E1520] mb-4">+ Add New Property Unit</h3>

        <form action="{{ route('admin.units.store') }}" method="POST" class="space-y-4 text-xs font-mono">
            @csrf
            <div>
                <label class="block text-[#0E1520]/70 uppercase mb-1">Township *</label>
                <select name="project_slug" required class="w-full px-3 py-2 rounded-xl border border-gray-300">
                    @foreach($projects as $p)
                        <option value="{{ $p->slug }}">{{ $p->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[#0E1520]/70 uppercase mb-1">Unit Code *</label>
                    <input type="text" name="unit_code" required placeholder="e.g. P-25" class="w-full px-3 py-2 rounded-xl border border-gray-300">
                </div>
                <div>
                    <label class="block text-[#0E1520]/70 uppercase mb-1">Property Type</label>
                    <select name="unit_type" class="w-full px-3 py-2 rounded-xl border border-gray-300">
                        <option value="Villa">Villa</option>
                        <option value="Plot">Plot</option>
                        <option value="Apartment">Apartment</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[#0E1520]/70 uppercase mb-1">Title / Designation *</label>
                <input type="text" name="configuration" required placeholder="e.g. 3 BHK Duplex Villa" class="w-full px-3 py-2 rounded-xl border border-gray-300">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[#0E1520]/70 uppercase mb-1">Super Area (Sq.Ft) *</label>
                    <input type="number" name="area_sqft" required value="1600" class="w-full px-3 py-2 rounded-xl border border-gray-300">
                </div>
                <div>
                    <label class="block text-[#0E1520]/70 uppercase mb-1">Total Price (INR) *</label>
                    <input type="number" name="total_price" required min="100000" value="4500000" class="w-full px-3 py-2 rounded-xl border border-gray-300">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[#0E1520]/70 uppercase mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-gray-300">
                        <option value="available">Available</option>
                        <option value="on_hold">On Hold</option>
                        <option value="sold">Sold</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[#0E1520]/70 uppercase mb-1">Facing</label>
                    <input type="text" name="facing" value="East Facing" class="w-full px-3 py-2 rounded-xl border border-gray-300">
                </div>
            </div>

            <button type="submit" class="w-full py-3 rounded-full bg-[#D4E845] hover:bg-[#c2d733] text-[#0E1520] font-bold uppercase tracking-wider text-xs shadow mt-2">
                Save Unit to Live Catalog
            </button>
        </form>
    </div>
</div>

@endsection
