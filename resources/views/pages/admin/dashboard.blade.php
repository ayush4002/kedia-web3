@extends('pages.admin.shell')

@section('title', 'Admin · Overview | Kedia Builders')
@section('admin_title', __('Overview'))
@section('admin_actions')
    <a href="{{ route('admin.export.orders') }}" class="btn-ghost-light btn-sm">{{ __('Export orders (CSV)') }}</a>
    <a href="{{ route('admin.export.catalog') }}" class="btn-ghost-light btn-sm">{{ __('Export catalogue (JSON)') }}</a>
@endsection

@php
    // Chart status palette, validated for the light paper surface (see dataviz validator):
    $statusColors = ['available' => '#2E9A68', 'on_hold' => '#C8A24B', 'sold' => '#4A6FB0'];
    $statusLabels = ['available' => __('Available'), 'on_hold' => __('On Hold'), 'sold' => __('Sold')];
    $maxBookings = max(1, $monthly->max('bookings'));
    $maxUnits = max(1, $byProject->map(fn ($p) => $p->available + $p->on_hold + $p->sold)->max());
    $maxSource = max(1, $leadsBySource->max('total'));
    $kpiTile = 'rounded-2xl border border-navy/10 bg-paper-50 p-5';
@endphp

@section('admin')
{{-- KPI tiles --}}
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-6">
    <div class="{{ $kpiTile }} xl:col-span-2">
        <div class="label text-ink/50">{{ __('Verified token revenue') }}</div>
        <div class="mt-2 font-serif text-4xl text-navy">₹ {{ \App\Support\Format::inr($kpis['revenue']) }}</div>
        <div class="mt-1 text-xs text-ink/50">₹ {{ \App\Support\Format::inr($kpis['pending_tokens']) }} {{ __('awaiting verification') }}</div>
    </div>
    <div class="{{ $kpiTile }} xl:col-span-2">
        <div class="label text-ink/50">{{ __('Booked value (on hold + sold)') }}</div>
        <div class="mt-2 font-serif text-4xl text-navy">{{ \App\Support\Format::lakhCrore($kpis['gmv']) }}</div>
    </div>
    <div class="{{ $kpiTile }}">
        <div class="label text-ink/50">{{ __('Active bookings') }}</div>
        <div class="mt-2 font-serif text-4xl text-navy">{{ $kpis['bookings'] }}</div>
    </div>
    <a href="{{ route('admin.leads', ['due' => 1]) }}" class="{{ $kpiTile }} transition hover:border-brass">
        <div class="label text-ink/50">{{ __('Follow-ups due') }}</div>
        <div class="mt-2 font-serif text-4xl {{ $kpis['follow_ups_due'] ? 'text-terracotta' : 'text-navy' }}">{{ $kpis['follow_ups_due'] }}</div>
        <div class="mt-1 text-xs text-ink/50">{{ $kpis['leads_new'] }} {{ __('new leads') }}</div>
    </a>
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-12">
    {{-- Bookings per month: single series, so no legend --}}
    <figure class="rounded-2xl border border-navy/10 bg-paper-50 p-6 xl:col-span-5">
        <figcaption class="font-serif text-xl text-navy">{{ __('Bookings per month') }}</figcaption>
        <p class="text-xs text-ink/50">{{ __('Last 6 months, excluding unpaid checkouts') }}</p>
        <svg viewBox="0 0 360 200" class="mt-4 w-full" role="img" aria-label="{{ __('Bookings per month') }}: {{ $monthly->map(fn ($m) => $m['label'] . ' ' . $m['bookings'])->implode(', ') }}">
            @foreach([0, 0.5, 1] as $g)
                <line x1="30" x2="355" y1="{{ 170 - $g * 150 }}" y2="{{ 170 - $g * 150 }}" stroke="#0E1520" stroke-opacity=".08"/>
                <text x="24" y="{{ 174 - $g * 150 }}" text-anchor="end" font-size="9" font-family="JetBrains Mono, monospace" fill="#0E1520" fill-opacity=".5">{{ round($maxBookings * $g) }}</text>
            @endforeach
            @foreach($monthly as $i => $m)
                @php $h = $m['bookings'] / $maxBookings * 150; $x = 42 + $i * 52; @endphp
                <g>
                    <title>{{ $m['label'] }}: {{ $m['bookings'] }} {{ __('bookings') }}, ₹ {{ \App\Support\Format::inr($m['revenue']) }} {{ __('verified') }}</title>
                    <rect x="{{ $x - 6 }}" y="18" width="40" height="152" fill="transparent"/>
                    @if($h > 0)<path d="M{{ $x }} 170 V{{ 170 - $h + 4 }} q0 -4 4 -4 h20 q4 0 4 4 V170 Z" fill="#0E1520"/>@endif
                    <text x="{{ $x + 14 }}" y="{{ 166 - $h }}" text-anchor="middle" font-size="10" font-family="JetBrains Mono, monospace" fill="#0E1520">{{ $m['bookings'] ?: '' }}</text>
                    <text x="{{ $x + 14 }}" y="188" text-anchor="middle" font-size="10" font-family="JetBrains Mono, monospace" fill="#0E1520" fill-opacity=".6">{{ $m['label'] }}</text>
                </g>
            @endforeach
            <line x1="30" x2="355" y1="170" y2="170" stroke="#0E1520" stroke-opacity=".3"/>
        </svg>
    </figure>

    {{-- Inventory by project: stacked bars, status colours + legend + table view --}}
    <figure class="rounded-2xl border border-navy/10 bg-paper-50 p-6 xl:col-span-7">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <figcaption class="font-serif text-xl text-navy">{{ __('Inventory by project') }}</figcaption>
                <p class="text-xs text-ink/50">{{ $inventory['total'] }} {{ __('units') }} · {{ $inventory['available'] }} {{ __('available') }}</p>
            </div>
            <ul class="flex flex-wrap gap-4 text-xs text-ink/70" aria-label="{{ __('Legend') }}">
                @foreach($statusColors as $key => $color)
                    <li class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm" style="background: {{ $color }}" aria-hidden="true"></span>{{ $statusLabels[$key] }} ({{ $inventory[$key] }})</li>
                @endforeach
            </ul>
        </div>
        <div class="mt-5 space-y-2.5">
            @foreach($byProject as $p)
                @php $total = $p->available + $p->on_hold + $p->sold; @endphp
                <div class="grid grid-cols-[9rem_minmax(0,1fr)_2.5rem] items-center gap-3 text-xs">
                    <span class="truncate text-ink/70" title="{{ $p->project_name }}">{{ $p->project_name }}</span>
                    <div class="flex h-4 gap-[2px]" style="width: {{ $total / $maxUnits * 100 }}%">
                        @foreach(['available', 'on_hold', 'sold'] as $k)
                            @if($p->$k > 0)
                                <span class="h-full first:rounded-l last:rounded-r" style="flex: {{ $p->$k }}; background: {{ $statusColors[$k] }}" title="{{ $p->project_name }} · {{ $statusLabels[$k] }}: {{ $p->$k }}"></span>
                            @endif
                        @endforeach
                    </div>
                    <span class="text-right font-mono text-ink/60">{{ $total }}</span>
                </div>
            @endforeach
        </div>
        <details class="mt-5 text-sm">
            <summary class="label cursor-pointer text-ink/50">{{ __('Show as table') }}</summary>
            <table class="table-clean mt-3">
                <thead><tr><th scope="col">{{ __('Project') }}</th>@foreach($statusLabels as $l)<th scope="col" class="text-right">{{ $l }}</th>@endforeach</tr></thead>
                <tbody>
                    @foreach($byProject as $p)
                        <tr><td>{{ $p->project_name }}</td><td class="text-right font-mono">{{ $p->available }}</td><td class="text-right font-mono">{{ $p->on_hold }}</td><td class="text-right font-mono">{{ $p->sold }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </details>
    </figure>
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-12">
    <section class="rounded-2xl border border-navy/10 bg-paper-50 p-6 xl:col-span-7">
        <div class="flex items-center justify-between">
            <h2 class="font-serif text-xl text-navy">{{ __('Recent bookings') }}</h2>
            <a href="{{ route('admin.bookings') }}" class="label text-ink/50 underline hover:text-terracotta">{{ __('All bookings') }}</a>
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="table-clean min-w-[32rem]">
                <thead><tr><th>{{ __('Booking') }}</th><th>{{ __('Buyer') }}</th><th>{{ __('Unit') }}</th><th>{{ __('Status') }}</th><th class="text-right">{{ __('Token') }}</th></tr></thead>
                <tbody>
                    @forelse($recentBookings as $b)
                        <tr>
                            <td><a href="{{ $b->voucherUrl() }}" target="_blank" class="font-mono text-xs underline">{{ $b->booking_id }}</a></td>
                            <td>{{ $b->customer_name }}</td>
                            <td class="text-xs">{{ $b->unit_code }} · {{ $b->project_name }}</td>
                            <td class="text-xs">{{ $b->status_label }}</td>
                            <td class="text-right font-mono text-xs">₹ {{ \App\Support\Format::inr($b->token_amount) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-ink/50">{{ __('No bookings yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-2xl border border-navy/10 bg-paper-50 p-6 xl:col-span-5">
        <div class="flex items-center justify-between">
            <h2 class="font-serif text-xl text-navy">{{ __('Leads to follow up') }}</h2>
            <a href="{{ route('admin.leads') }}" class="label text-ink/50 underline hover:text-terracotta">{{ __('Lead tracker') }}</a>
        </div>
        <ul class="mt-4 divide-y divide-navy/10 text-sm">
            @forelse($dueLeads as $lead)
                <li class="flex items-start justify-between gap-3 py-3">
                    <div class="min-w-0">
                        <div class="truncate text-navy">{{ $lead->name }} <span class="text-ink/40">· {{ $lead->project }}</span></div>
                        <div class="label text-ink/40">{{ $lead->status_label }} · {{ $lead->assigned_advisor }}</div>
                    </div>
                    <span class="flex-none font-mono text-xs {{ $lead->is_overdue ? 'text-terracotta' : 'text-ink/60' }}">{{ $lead->follow_up_at ? ($lead->is_overdue ? '⚠ ' : '') . $lead->follow_up_at->format('d M') : __('No date') }}</span>
                </li>
            @empty
                <li class="py-3 text-ink/50">{{ __('No open leads.') }}</li>
            @endforelse
        </ul>
        @if($leadsBySource->isNotEmpty())
            <h3 class="label mt-6 text-ink/50">{{ __('Leads by source') }}</h3>
            <div class="mt-3 space-y-2">
                @foreach($leadsBySource as $s)
                    <div class="grid grid-cols-[8rem_minmax(0,1fr)_2rem] items-center gap-3 text-xs" title="{{ $s->source }}: {{ $s->total }}">
                        <span class="truncate text-ink/70">{{ str_replace('_', ' ', $s->source) }}</span>
                        <span class="h-3 rounded-r bg-navy" style="width: {{ $s->total / $maxSource * 100 }}%"></span>
                        <span class="text-right font-mono text-ink/60">{{ $s->total }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
