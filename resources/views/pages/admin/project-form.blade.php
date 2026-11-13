@extends('pages.admin.shell')

@php $editing = $project->exists; @endphp

@section('title', 'Admin · ' . ($editing ? 'Edit ' . $project->name : 'New project') . ' | Kedia Builders')
@section('admin_title', $editing ? __('Edit project') . ' · ' . $project->name : __('New project'))

@section('admin')
<form action="{{ $editing ? route('admin.projects.update', $project->id) : route('admin.projects.store') }}" method="POST" class="grid gap-8 lg:grid-cols-12">
    @csrf
    @if($editing) @method('PUT') @endif
    <div class="grid gap-5 rounded-2xl border border-navy/10 bg-paper-50 p-6 sm:grid-cols-2 lg:col-span-8">
        @foreach([
            ['name', __('Name'), 'text', true], ['type', __('Type (e.g. Villas & Plots)'), 'text', true],
            ['location', __('Location'), 'text', true], ['rera_number', __('RERA number'), 'text', false],
            ['starting_price', __('Starting price (INR)'), 'number', false], ['possession_label', __('Possession'), 'text', false],
            ['latitude', __('Latitude'), 'number', false], ['longitude', __('Longitude'), 'number', false],
        ] as [$name, $label, $type, $required])
            <div>
                <label for="pf_{{ $name }}" class="field-label">{{ $label }}{{ $required ? ' *' : '' }}</label>
                <input id="pf_{{ $name }}" name="{{ $name }}" type="{{ $type }}" @if($type === 'number') step="any" @endif @if($required) required @endif value="{{ old($name, $project->$name) }}" class="field">
            </div>
        @endforeach
        <div>
            <label for="pf_class" class="field-label">{{ __('Property type') }} *</label>
            <select id="pf_class" name="classification" class="field">@foreach(['Residential', 'Commercial', 'Single-Family', 'Township'] as $c)<option @selected(old('classification', $project->classification) === $c)>{{ $c }}</option>@endforeach</select>
        </div>
        <div>
            <label for="pf_status" class="field-label">{{ __('Status') }} *</label>
            <select id="pf_status" name="status" class="field"><option value="ongoing" @selected(old('status', $project->status) === 'ongoing')>{{ __('Ongoing') }}</option><option value="completed" @selected(old('status', $project->status) === 'completed')>{{ __('Completed') }}</option></select>
        </div>
        <div class="sm:col-span-2"><label for="pf_address" class="field-label">{{ __('Address') }}</label><input id="pf_address" name="address" value="{{ old('address', $project->address) }}" class="field"></div>
        <div class="sm:col-span-2"><label for="pf_desc" class="field-label">{{ __('Description') }}</label><textarea id="pf_desc" name="description" rows="5" class="field">{{ old('description', $project->description) }}</textarea></div>
    </div>
    <div class="space-y-4 lg:col-span-4">
        <div class="rounded-2xl border border-navy/10 bg-paper-50 p-6">
            <label for="pf_amen" class="field-label">{{ __('Amenities (one per line)') }}</label>
            <textarea id="pf_amen" name="amenities_text" rows="10" class="field">{{ old('amenities_text', implode("\n", $project->amenities ?? [])) }}</textarea>
        </div>
        <button class="btn-navy w-full">{{ $editing ? __('Save changes') : __('Create project') }}</button>
        <a href="{{ route('admin.projects') }}" class="btn-ghost w-full">{{ __('Cancel') }}</a>
    </div>
</form>
@endsection
