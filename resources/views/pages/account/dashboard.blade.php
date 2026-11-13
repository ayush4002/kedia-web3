@extends('layouts.app')

@section('title', 'My Account | Kedia Builders & Colonizers')
@section('robots', 'noindex, nofollow')

@php
    $tabs = ['bookings' => __('Bookings'), 'payments' => __('Payments'), 'documents' => __('Receipts & documents'), 'progress' => __('Construction updates'), 'shortlist' => __('Shortlist')];
    $statusClass = fn ($s) => match ($s) {
        'confirmed', 'holding' => 'pill-available',
        'cancelled', 'refunded' => 'pill-sold',
        default => 'pill-hold',
    };
@endphp

@section('content')
<section class="section-navy overflow-hidden">
    <div class="blueprint-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <div class="container-site relative flex flex-wrap items-end justify-between gap-6 py-12">
        <div>
            <div class="eyebrow">{{ __('My account') }}</div>
            <h1 class="display-md mt-3 text-paper">{{ $name ? __('Namaste,') . ' ' . \Illuminate\Support\Str::before($name, ' ') : __('Welcome') }}</h1>
            <p class="label mt-2 text-paper/50">{{ __('Signed in as') }} ••••••{{ substr($phone, -4) }}</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <dl class="flex gap-8 text-paper">
                <div><dt class="label text-paper/40">{{ __('Active bookings') }}</dt><dd class="font-serif text-3xl">{{ $active->count() }}</dd></div>
                <div><dt class="label text-paper/40">{{ __('Next payment') }}</dt><dd class="font-serif text-3xl text-brass">{{ $reminders->first() ? '₹ ' . \App\Support\Format::inr($reminders->first()['amount']) : '—' }}</dd></div>
            </dl>
            <form action="{{ route('account.logout') }}" method="POST" class="self-end">
                @csrf
                <button type="submit" class="btn-ghost-light btn-sm">{{ __('Sign out') }}</button>
            </form>
        </div>
    </div>
</section>

<section class="section-paper py-10" data-tabs>
    <div class="container-site">
        <div class="flex gap-2 overflow-x-auto border-b border-navy/10 pb-px" role="tablist" aria-label="{{ __('Account sections') }}">
            @foreach($tabs as $key => $label)
                <button type="button" role="tab" id="t-{{ $key }}" aria-controls="p-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    class="{{ $loop->first ? 'is-active' : '' }} -mb-px flex-none border-b-2 border-transparent px-4 py-3 text-xs font-semibold uppercase tracking-[0.14em] text-ink/50 transition hover:text-navy [&.is-active]:border-brass [&.is-active]:text-navy">{{ $label }}</button>
            @endforeach
        </div>

        {{-- BOOKINGS --}}
        <div id="p-bookings" role="tabpanel" aria-labelledby="t-bookings" class="mt-8 space-y-5">
            @forelse($bookings as $booking)
                <article class="grid gap-6 rounded-[1.5rem] border border-navy/10 bg-paper-50 p-6 md:grid-cols-[8rem_minmax(0,1fr)_auto]">
                    <img src="{{ $booking->project ? \App\Models\Project::media($booking->project->cover_image, 600) : asset('media/img/2021/04/Kedia-Nikunj-1-1920.webp') }}" alt="" loading="lazy" class="arch-soft hidden h-36 w-32 object-cover md:block">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="{{ $statusClass($booking->status) }}">{{ __($booking->status_label) }}</span>
                            <span class="font-mono text-xs text-ink/50">{{ $booking->booking_id }}</span>
                        </div>
                        <h2 class="mt-2 font-serif text-2xl text-navy">{{ $booking->unit->title ?? $booking->unit_number }} · {{ $booking->unit_code }}</h2>
                        <p class="text-sm text-ink/60">{{ $booking->project->title ?? $booking->project_name }} · {{ __('Booked') }} {{ optional($booking->booking_date)->format('d M Y') }}</p>
                        @php $schedule = collect($booking->payment_schedule ?? []); $paidAmt = $schedule->where('status', 'Paid')->sum('amount'); $pct = $booking->total_amount ? round($paidAmt / $booking->total_amount * 100) : 0; @endphp
                        <div class="mt-4 max-w-md">
                            <div class="flex justify-between text-xs text-ink/60"><span>{{ __('Paid') }} ₹ {{ \App\Support\Format::inr($paidAmt) }}</span><span>{{ __('of') }} ₹ {{ \App\Support\Format::inr($booking->total_amount) }}</span></div>
                            <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-navy/10" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ __('Amount paid') }}"><div class="h-full bg-brass" style="width: {{ $pct }}%"></div></div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-start gap-2 md:flex-col md:items-end">
                        @if($booking->status === \App\Models\Booking::STATUS_AWAITING_PAYMENT)
                            <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('booking.pay', ['bookingId' => $booking->booking_id]) }}" class="btn-brass btn-sm">{{ __('Complete payment') }}</a>
                        @else
                            <a href="{{ $booking->voucherUrl() }}" class="btn-navy btn-sm">{{ __('View receipt') }}</a>
                        @endif
                        @if($booking->unit)<a href="{{ $booking->unit->url }}" class="btn-ghost btn-sm">{{ __('Unit page') }}</a>@endif
                    </div>
                </article>
            @empty
                <div class="rounded-[2rem] border border-dashed border-navy/20 p-12 text-center">
                    <h2 class="font-serif text-3xl text-navy">{{ __('No bookings on this number yet') }}</h2>
                    <p class="mt-2 text-ink/60">{{ __('Bookings made with this mobile number appear here automatically.') }}</p>
                    <a href="{{ route('projects.index', ['view' => 'units']) }}" class="btn-navy mt-6">{{ __('Browse available units') }}</a>
                </div>
            @endforelse
        </div>

        {{-- PAYMENTS --}}
        <div id="p-payments" role="tabpanel" aria-labelledby="t-payments" class="mt-8" hidden>
            @if($reminders->isEmpty())
                <p class="text-ink/60">{{ __('No upcoming payments.') }}</p>
            @else
                @php $next = $reminders->first(); @endphp
                <div class="mb-6 flex flex-wrap items-center justify-between gap-4 rounded-[1.5rem] border border-brass/40 bg-brass/10 p-6">
                    <div>
                        <div class="label text-brass-700">{{ __('Reminder') }}</div>
                        <div class="mt-1 font-serif text-2xl text-navy">₹ {{ \App\Support\Format::inr($next['amount']) }} {{ __('due') }} {{ \Illuminate\Support\Carbon::parse($next['due_date'])->format('d M Y') }}</div>
                        <div class="text-sm text-ink/60">{{ __($next['milestone']) }} · {{ $next['booking']->booking_id }}</div>
                    </div>
                    <span class="label text-ink/50">{{ \Illuminate\Support\Carbon::parse($next['due_date'])->diffForHumans() }}</span>
                </div>
                <div class="overflow-x-auto rounded-[1.5rem] border border-navy/10 bg-paper-50">
                    <table class="table-clean min-w-[36rem]">
                        <thead><tr><th>{{ __('Milestone') }}</th><th>{{ __('Booking') }}</th><th>{{ __('Due') }}</th><th class="text-right">{{ __('Amount') }}</th></tr></thead>
                        <tbody>
                            @foreach($reminders as $m)
                                <tr>
                                    <td class="text-navy">{{ __($m['milestone']) }}</td>
                                    <td class="font-mono text-xs">{{ $m['booking']->booking_id }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($m['due_date'])->format('d M Y') }}</td>
                                    <td class="text-right font-mono">₹ {{ \App\Support\Format::inr($m['amount']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-xs text-ink/50">{{ __('Milestone dates are indicative and follow construction progress; we confirm each demand by email and SMS.') }}</p>
            @endif
        </div>

        {{-- DOCUMENTS --}}
        <div id="p-documents" role="tabpanel" aria-labelledby="t-documents" class="mt-8 space-y-6" hidden>
            @forelse($active as $booking)
                <div class="rounded-[1.5rem] border border-navy/10 bg-paper-50 p-6">
                    <h2 class="font-serif text-xl text-navy">{{ $booking->booking_id }} · {{ $booking->unit_code }}</h2>
                    <ul class="mt-4 divide-y divide-navy/10 text-sm">
                        <li class="flex items-center justify-between gap-4 py-3"><span>{{ __('Booking receipt') }}</span><a href="{{ $booking->status === \App\Models\Booking::STATUS_AWAITING_PAYMENT ? '#' : $booking->voucherUrl() }}" class="label text-navy underline">{{ __('Open') }}</a></li>
                        @if($booking->unit_id)<li class="flex items-center justify-between gap-4 py-3"><span>{{ __('Cost sheet') }}</span><a href="{{ route('units.cost-sheet', $booking->unit_id) }}" target="_blank" class="label text-navy underline">{{ __('Open') }}</a></li>@endif
                        @if($booking->project?->brochure_url)<li class="flex items-center justify-between gap-4 py-3"><span>{{ __('Project brochure') }}</span><a href="{{ $booking->project->brochure_url }}" download class="label text-navy underline">{{ __('Download') }}</a></li>@endif
                        @foreach([__('Allotment letter'), __('Agreement to sale (draft)'), __('JDA approved layout plan')] as $doc)
                            <li class="flex items-center justify-between gap-4 py-3 text-ink/60"><span class="flex items-center gap-2">{{ $doc }} <x-sample-tag /></span><span class="label">{{ __('After verification') }}</span></li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <p class="text-ink/60">{{ __('Documents appear here once you have a booking.') }}</p>
            @endforelse
        </div>

        {{-- CONSTRUCTION --}}
        <div id="p-progress" role="tabpanel" aria-labelledby="t-progress" class="mt-8 space-y-6" hidden>
            @forelse($projects as $project)
                <div class="rounded-[1.5rem] bg-navy p-6 text-paper sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="font-serif text-2xl">{{ $project->name }}</h2>
                        <x-sample-tag />
                    </div>
                    <ol class="mt-6 space-y-4">
                        @foreach(config('kedia.construction') as $phase)
                            <li>
                                <div class="flex justify-between text-sm"><span>{{ __($phase['phase']) }}</span><span class="font-mono text-xs text-paper/60">{{ $phase['progress'] }}% · {{ __($phase['date']) }}</span></div>
                                <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-paper/10"><div class="h-full bg-brass" style="width: {{ $phase['progress'] }}%"></div></div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @empty
                <p class="text-ink/60">{{ __('Construction updates for your project appear here once you have a booking.') }}</p>
            @endforelse
        </div>

        {{-- SHORTLIST --}}
        <div id="p-shortlist" role="tabpanel" aria-labelledby="t-shortlist" class="mt-8" hidden>
            <p class="text-ink/60">{{ __('Your shortlist is saved on this device.') }}</p>
            <div class="mt-4 flex flex-wrap gap-3">
                <button type="button" data-open="wishlistDrawer" class="btn-navy">{{ __('Open my shortlist') }}</button>
                <a href="{{ route('compare') }}" class="btn-ghost">{{ __('Compare units') }}</a>
            </div>
            <section class="mt-10" data-recent-section hidden aria-label="{{ __('Recently viewed') }}">
                <h2 class="eyebrow">{{ __('Recently viewed') }}</h2>
                <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4" data-recent-list></div>
            </section>
        </div>
    </div>
</section>
@endsection
