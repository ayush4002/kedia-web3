@extends('pages.admin.shell')

@section('title', 'Admin · Edit ' . $unit->unit_code . ' | Kedia Builders')
@section('admin_title', __('Edit unit') . ' ' . $unit->unit_code)
@section('admin_actions')
    <a href="{{ $unit->url }}" target="_blank" class="btn-ghost-light btn-sm">{{ __('View on site') }}</a>
@endsection

@section('admin')
<form action="{{ route('admin.units.update', $unit->id) }}" method="POST" class="grid gap-8 lg:grid-cols-12">
    @csrf @method('PUT')
    <div class="grid gap-5 rounded-2xl border border-navy/10 bg-paper-50 p-6 sm:grid-cols-2 lg:col-span-8">
        <div><span class="field-label">{{ __('Project') }}</span><div class="py-3 text-navy">{{ $unit->project_name }}</div></div>
        <div><span class="field-label">{{ __('Unit code') }}</span><div class="py-3 font-mono text-navy">{{ $unit->unit_code }}</div></div>
        @foreach([
            ['unit_number', __('Unit number'), 'text', true], ['tower_or_block', __('Block / phase'), 'text', false],
            ['configuration', __('Configuration'), 'text', true], ['area_sqft', __('Area (sq ft)'), 'number', true],
            ['area_sqyd', __('Plot size (sq yd)'), 'number', false], ['bedrooms', __('Bedrooms'), 'number', false],
            ['bathrooms', __('Bathrooms'), 'number', false], ['facing', __('Facing'), 'text', false],
            ['floor_level', __('Floor'), 'text', false], ['possession', __('Possession'), 'text', false],
        ] as [$name, $label, $type, $required])
            <div>
                <label for="ue_{{ $name }}" class="field-label">{{ $label }}{{ $required ? ' *' : '' }}</label>
                <input id="ue_{{ $name }}" name="{{ $name }}" type="{{ $type }}" @if($type === 'number') step="any" min="0" @endif @if($required) required @endif value="{{ old($name, $unit->$name) }}" class="field">
            </div>
        @endforeach
        <div>
            <label for="ue_type" class="field-label">{{ __('Type') }} *</label>
            <select id="ue_type" name="unit_type" class="field">@foreach(['Villa', 'Plot', 'Apartment'] as $t)<option @selected($unit->unit_type === $t)>{{ $t }}</option>@endforeach</select>
        </div>
        <div>
            <label for="ue_status" class="field-label">{{ __('Status') }} *</label>
            <select id="ue_status" name="status" class="field">@foreach(['available' => __('Available'), 'on_hold' => __('On Hold'), 'sold' => __('Sold')] as $v => $l)<option value="{{ $v }}" @selected($unit->status === $v)>{{ $l }}</option>@endforeach</select>
        </div>
        <label class="flex items-center gap-2 text-sm sm:col-span-2"><input type="checkbox" name="is_featured" value="1" class="h-4 w-4 accent-navy" @checked($unit->is_featured)> {{ __('Featured unit') }}</label>
    </div>
    <div class="space-y-4 lg:col-span-4">
        <div class="rounded-2xl bg-navy p-6 text-paper">
            <label for="ue_price" class="label mb-1.5 block text-paper/60">{{ __('All-inclusive price (INR)') }} *</label>
            <input id="ue_price" name="total_price" type="number" min="100000" step="1000" required value="{{ old('total_price', $unit->total_price) }}" class="field-dark font-mono text-lg">
            <p class="mt-3 text-xs text-paper/50">{{ __('Changing the price rescales the cost-sheet breakdown so its parts still add up.') }}</p>
            <x-price-breakup :pricing="$unit->computePricing()" dark class="mt-4" />
        </div>
        <button class="btn-navy w-full">{{ __('Save changes') }}</button>
        <a href="{{ route('admin.inventory', ['project' => $unit->project_slug]) }}" class="btn-ghost w-full">{{ __('Cancel') }}</a>
    </div>
</form>
@endsection
