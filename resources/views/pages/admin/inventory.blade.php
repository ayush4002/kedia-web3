@extends('pages.admin.shell')

@section('title', 'Admin · Inventory | Kedia Builders')
@section('admin_title', __('Inventory'))
@section('admin_actions')
    <button type="button" data-open="addUnitModal" class="btn-brass btn-sm">+ {{ __('Add unit') }}</button>
@endsection

@section('admin')
<form method="GET" class="flex flex-wrap items-end gap-3">
    <div>
        <label for="inv_project" class="field-label">{{ __('Project') }}</label>
        <select id="inv_project" name="project" class="field !py-2">
            <option value="">{{ __('All projects') }}</option>
            @foreach($projects as $p)<option value="{{ $p->slug }}" @selected(($filters['project'] ?? '') === $p->slug)>{{ $p->name }}</option>@endforeach
        </select>
    </div>
    <div>
        <label for="inv_status" class="field-label">{{ __('Status') }}</label>
        <select id="inv_status" name="status" class="field !py-2">
            <option value="">{{ __('Any') }}</option>
            @foreach(['available' => __('Available'), 'on_hold' => __('On Hold'), 'sold' => __('Sold')] as $v => $l)<option value="{{ $v }}" @selected(($filters['status'] ?? '') === $v)>{{ $l }}</option>@endforeach
        </select>
    </div>
    <div>
        <label for="inv_search" class="field-label">{{ __('Search') }}</label>
        <input id="inv_search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('Code, number, configuration') }}" class="field !py-2">
    </div>
    <button class="btn-navy btn-sm">{{ __('Filter') }}</button>
    <span class="label ml-auto text-ink/50">{{ $units->total() }} {{ __('units') }}</span>
</form>

<div class="mt-6 overflow-x-auto rounded-2xl border border-navy/10 bg-paper-50">
    <table class="table-clean min-w-[56rem]">
        <thead><tr><th>{{ __('Unit') }}</th><th>{{ __('Project') }}</th><th>{{ __('Type') }}</th><th>{{ __('Area') }}</th><th>{{ __('Price') }}</th><th>{{ __('Status') }}</th><th></th></tr></thead>
        <tbody>
            @foreach($units as $u)
                <tr>
                    <td><a href="{{ $u->url }}" target="_blank" class="font-mono text-xs underline">{{ $u->unit_code }}</a><div class="text-xs text-ink/50">{{ $u->unit_number }}</div></td>
                    <td class="text-xs">{{ $u->project_name }}</td>
                    <td class="text-xs">{{ $u->unit_type }} · {{ $u->configuration }}</td>
                    <td class="font-mono text-xs">{{ \App\Support\Format::inr($u->area_sqft) }}</td>
                    <td>
                        <form action="{{ route('admin.units.price', $u->id) }}" method="POST" class="flex items-center gap-1">
                            @csrf
                            <label for="price-{{ $u->id }}" class="sr-only">{{ __('Price for') }} {{ $u->unit_code }}</label>
                            <input id="price-{{ $u->id }}" name="price_num" type="number" min="100000" step="1000" value="{{ $u->total_price }}" class="w-32 rounded-lg border border-navy/15 bg-white px-2 py-1 font-mono text-xs">
                            <button class="rounded-lg px-2 py-1 text-xs text-navy hover:bg-navy/5" aria-label="{{ __('Save price') }}">✓</button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.units.status', $u->id) }}" method="POST">
                            @csrf
                            <label for="status-{{ $u->id }}" class="sr-only">{{ __('Status of') }} {{ $u->unit_code }}</label>
                            <select id="status-{{ $u->id }}" name="status" onchange="this.form.submit()" class="rounded-full border border-navy/15 bg-white px-2.5 py-1 text-xs">
                                @foreach(['available' => __('Available'), 'on_hold' => __('On Hold'), 'sold' => __('Sold')] as $v => $l)<option value="{{ $v }}" @selected($u->status === $v)>{{ $l }}</option>@endforeach
                            </select>
                        </form>
                    </td>
                    <td class="whitespace-nowrap text-right">
                        <a href="{{ route('admin.units.edit', $u->id) }}" class="label text-navy underline">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.units.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Delete unit') }} {{ $u->unit_code }}?')">
                            @csrf @method('DELETE')
                            <button class="label ml-3 text-terracotta underline">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $units->links('components.pagination') }}</div>

{{-- Add unit --}}
<div id="addUnitModal" data-panel hidden aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="addUnitTitle" class="no-print fixed inset-0 z-[70] grid place-items-center bg-navy/60 p-4 opacity-0 backdrop-blur-sm transition-opacity [&.is-open]:opacity-100">
    <form action="{{ route('admin.units.store') }}" method="POST" class="grid w-full max-w-lg grid-cols-2 gap-4 rounded-[1.5rem] bg-paper p-6 sm:p-8">
        @csrf
        <div class="col-span-2 flex items-center justify-between">
            <h2 id="addUnitTitle" class="font-serif text-2xl text-navy">{{ __('Add unit') }}</h2>
            <button type="button" data-close class="text-2xl text-ink/50 hover:text-terracotta" aria-label="{{ __('Close') }}">&times;</button>
        </div>
        <div class="col-span-2">
            <label for="au_project" class="field-label">{{ __('Project') }} *</label>
            <select id="au_project" name="project_slug" required class="field">@foreach($projects as $p)<option value="{{ $p->slug }}">{{ $p->name }}</option>@endforeach</select>
        </div>
        <div><label for="au_code" class="field-label">{{ __('Unit code') }} *</label><input id="au_code" name="unit_code" required placeholder="e.g. KED-Plot25" class="field"></div>
        <div>
            <label for="au_type" class="field-label">{{ __('Type') }} *</label>
            <select id="au_type" name="unit_type" class="field"><option>Villa</option><option>Plot</option><option>Apartment</option></select>
        </div>
        <div class="col-span-2"><label for="au_config" class="field-label">{{ __('Configuration') }}</label><input id="au_config" name="configuration" placeholder="e.g. 3 BHK Duplex Villa" class="field"></div>
        <div><label for="au_area" class="field-label">{{ __('Area (sq ft)') }} *</label><input id="au_area" name="area_sqft" type="number" min="1" required class="field"></div>
        <div><label for="au_price" class="field-label">{{ __('Total price (INR)') }} *</label><input id="au_price" name="total_price" type="number" min="100000" required class="field"></div>
        <div><label for="au_facing" class="field-label">{{ __('Facing') }}</label><input id="au_facing" name="facing" placeholder="East" class="field"></div>
        <div>
            <label for="au_status" class="field-label">{{ __('Status') }}</label>
            <select id="au_status" name="status" class="field"><option value="available">{{ __('Available') }}</option><option value="on_hold">{{ __('On Hold') }}</option><option value="sold">{{ __('Sold') }}</option></select>
        </div>
        <button class="btn-navy col-span-2">{{ __('Save unit') }}</button>
    </form>
</div>
@endsection
