@extends('pages.admin.shell')

@section('title', 'Admin · Leads | Kedia Builders')
@section('admin_title', __('Lead tracker'))

@php $statuses = \App\Models\SiteVisitLead::STATUSES; $advisors = config('kedia.advisors'); @endphp

@section('admin')
{{-- Pipeline --}}
<ol class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-6" aria-label="{{ __('Pipeline') }}">
    @foreach($statuses as $key => $label)
        <li>
            <a href="{{ route('admin.leads', ['status' => $key]) }}" class="block rounded-2xl border px-4 py-3 transition {{ ($filters['status'] ?? null) === $key ? 'border-navy bg-navy text-paper' : 'border-navy/10 bg-paper-50 hover:border-brass' }}">
                <div class="label opacity-70">{{ __($label) }}</div>
                <div class="font-serif text-3xl">{{ $pipeline[$key] ?? 0 }}</div>
            </a>
        </li>
    @endforeach
</ol>

<form method="GET" class="mt-6 flex flex-wrap items-end gap-3">
    <div>
        <label for="lf_adv" class="field-label">{{ __('Advisor') }}</label>
        <select id="lf_adv" name="advisor" class="field !py-2"><option value="">{{ __('Anyone') }}</option>@foreach($advisors as $a)<option @selected(($filters['advisor'] ?? '') === $a)>{{ $a }}</option>@endforeach</select>
    </div>
    @if(!empty($filters['status']))<input type="hidden" name="status" value="{{ $filters['status'] }}">@endif
    <label class="flex items-center gap-2 pb-2 text-sm"><input type="checkbox" name="due" value="1" class="h-4 w-4 accent-navy" @checked(!empty($filters['due']))> {{ __('Follow-up due') }}</label>
    <button class="btn-navy btn-sm">{{ __('Filter') }}</button>
    <a href="{{ route('admin.leads') }}" class="label pb-2 text-ink/50 underline">{{ __('Reset') }}</a>
</form>

<div class="mt-6 space-y-3">
    @forelse($leads as $lead)
        <details class="group rounded-2xl border {{ $lead->is_overdue ? 'border-terracotta/50' : 'border-navy/10' }} bg-paper-50">
            <summary class="grid cursor-pointer list-none items-center gap-3 p-4 sm:grid-cols-12">
                <div class="sm:col-span-4">
                    <div class="text-navy">{{ $lead->name }} <span class="font-mono text-xs text-ink/40">{{ $lead->lead_code }}</span></div>
                    <div class="text-xs text-ink/50">{{ $lead->phone }}{{ $lead->email ? ' · ' . $lead->email : '' }}</div>
                </div>
                <div class="text-xs sm:col-span-3">{{ $lead->project }}<div class="text-ink/40">{{ str_replace('_', ' ', $lead->source) }} · {{ $lead->created_at->format('d M') }}</div></div>
                <div class="text-xs sm:col-span-2">{{ __($lead->status_label) }}<div class="text-ink/40">{{ $lead->assigned_advisor }}</div></div>
                <div class="font-mono text-xs sm:col-span-2 {{ $lead->is_overdue ? 'text-terracotta' : 'text-ink/60' }}">{{ $lead->follow_up_at ? ($lead->is_overdue ? '⚠ ' . __('Overdue') . ' ' : '') . $lead->follow_up_at->format('d M Y') : __('No follow-up set') }}</div>
                <div class="text-right text-brass transition group-open:rotate-45 sm:col-span-1" aria-hidden="true">+</div>
            </summary>
            <div class="grid gap-6 border-t border-navy/10 p-4 lg:grid-cols-2">
                <div class="space-y-2 text-sm text-ink/70">
                    @if($lead->preferred_date)<p><span class="label text-ink/40">{{ __('Visit requested') }}</span> {{ $lead->preferred_date }} · {{ $lead->preferred_time }}</p>@endif
                    @if($lead->message)<p class="whitespace-pre-line rounded-xl bg-paper-200/60 p-3">{{ $lead->message }}</p>@endif
                    <p class="flex gap-3"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $lead->phone) }}" class="label text-navy underline">{{ __('Call') }}</a><a href="https://wa.me/{{ preg_replace('/\D/', '', $lead->phone) }}" target="_blank" rel="noopener" class="label text-navy underline">WhatsApp</a></p>
                </div>
                <form action="{{ route('admin.leads.update', $lead->id) }}" method="POST" class="grid gap-3 sm:grid-cols-3">
                    @csrf @method('PUT')
                    <div>
                        <label for="ls-{{ $lead->id }}" class="field-label">{{ __('Stage') }}</label>
                        <select id="ls-{{ $lead->id }}" name="status" class="field !py-2">@foreach($statuses as $k => $l)<option value="{{ $k }}" @selected($lead->status === $k)>{{ __($l) }}</option>@endforeach</select>
                    </div>
                    <div>
                        <label for="la-{{ $lead->id }}" class="field-label">{{ __('Assign to') }}</label>
                        <select id="la-{{ $lead->id }}" name="assigned_advisor" class="field !py-2">@foreach($advisors as $a)<option @selected($lead->assigned_advisor === $a)>{{ $a }}</option>@endforeach</select>
                    </div>
                    <div>
                        <label for="lfu-{{ $lead->id }}" class="field-label">{{ __('Next follow-up') }}</label>
                        <input id="lfu-{{ $lead->id }}" name="follow_up_at" type="date" value="{{ optional($lead->follow_up_at)->toDateString() }}" class="field !py-2">
                    </div>
                    <div class="sm:col-span-3">
                        <label for="ln-{{ $lead->id }}" class="field-label">{{ __('Notes') }}</label>
                        <textarea id="ln-{{ $lead->id }}" name="notes" rows="2" class="field">{{ $lead->notes }}</textarea>
                    </div>
                    <button class="btn-navy btn-sm sm:col-span-3 sm:justify-self-start">{{ __('Save') }}</button>
                </form>
            </div>
        </details>
    @empty
        <p class="text-ink/50">{{ __('No leads match.') }}</p>
    @endforelse
</div>
<div class="mt-6">{{ $leads->links('components.pagination') }}</div>
@endsection
