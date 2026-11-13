@extends('pages.admin.shell')

@section('title', 'Admin · Bookings | Kedia Builders')
@section('admin_title', __('Bookings'))
@section('admin_actions')
    <a href="{{ route('admin.export.orders') }}" class="btn-ghost-light btn-sm">{{ __('Export CSV') }}</a>
@endsection

@section('admin')
<div class="flex flex-wrap gap-2">
    <a href="{{ route('admin.bookings') }}" class="chip {{ !$status ? '!border-navy !bg-navy !text-paper' : '' }}">{{ __('All') }} ({{ $counts->sum() }})</a>
    @foreach(\App\Models\Booking::STATUSES as $key => $label)
        <a href="{{ route('admin.bookings', ['status' => $key]) }}" class="chip {{ $status === $key ? '!border-navy !bg-navy !text-paper' : '' }}">{{ __($label) }} ({{ $counts[$key] ?? 0 }})</a>
    @endforeach
</div>

<div class="mt-6 overflow-x-auto rounded-2xl border border-navy/10 bg-paper-50">
    <table class="table-clean min-w-[60rem]">
        <thead><tr><th>{{ __('Booking') }}</th><th>{{ __('Date') }}</th><th>{{ __('Buyer') }}</th><th>{{ __('Unit') }}</th><th class="text-right">{{ __('Token') }}</th><th class="text-right">{{ __('Total') }}</th><th>{{ __('Status') }}</th></tr></thead>
        <tbody>
            @forelse($bookings as $b)
                <tr>
                    <td><a href="{{ $b->voucherUrl() }}" target="_blank" class="font-mono text-xs underline">{{ $b->booking_id }}</a><div class="font-mono text-[10px] text-ink/40">{{ $b->transaction_ref ?: '—' }}</div></td>
                    <td class="text-xs">{{ $b->created_at->format('d M Y') }}</td>
                    <td><div class="text-sm">{{ $b->customer_name }}</div><div class="text-xs text-ink/50">{{ $b->customer_phone }} · {{ $b->customer_email }}</div></td>
                    <td class="text-xs">{{ $b->unit_code }}<div class="text-ink/50">{{ $b->project_name }}</div></td>
                    <td class="text-right font-mono text-xs">₹ {{ \App\Support\Format::inr($b->token_amount) }}</td>
                    <td class="text-right font-mono text-xs">₹ {{ \App\Support\Format::inr($b->total_amount) }}</td>
                    <td>
                        <form action="{{ route('admin.bookings.status', $b->id) }}" method="POST">
                            @csrf
                            <label for="bs-{{ $b->id }}" class="sr-only">{{ __('Status of') }} {{ $b->booking_id }}</label>
                            <select id="bs-{{ $b->id }}" name="booking_status" onchange="this.form.submit()" class="rounded-full border border-navy/15 bg-white px-2.5 py-1 text-xs">
                                @foreach(\App\Models\Booking::STATUSES as $key => $label)<option value="{{ $key }}" @selected($b->status === $key)>{{ __($label) }}</option>@endforeach
                            </select>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-ink/50">{{ __('No bookings.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $bookings->links('components.pagination') }}</div>
<p class="mt-4 text-xs text-ink/50">{{ __('Set a booking to Confirmed once the token has been verified; this marks the token milestone as paid and counts it as revenue. Cancelling or refunding puts the unit back on sale.') }}</p>
@endsection
