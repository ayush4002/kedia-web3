@extends('pages.admin.shell')

@section('title', 'Admin · Projects | Kedia Builders')
@section('admin_title', __('Projects'))
@section('admin_actions')
    <a href="{{ route('admin.projects.create') }}" class="btn-brass btn-sm">+ {{ __('Add project') }}</a>
@endsection

@section('admin')
<div class="overflow-x-auto rounded-2xl border border-navy/10 bg-paper-50">
    <table class="table-clean min-w-[52rem]">
        <thead><tr><th>{{ __('Project') }}</th><th>{{ __('Location') }}</th><th>{{ __('Status') }}</th><th>RERA</th><th class="text-right">{{ __('Units') }}</th><th class="text-right">{{ __('Available') }}</th><th></th></tr></thead>
        <tbody>
            @foreach($projects as $p)
                <tr>
                    <td><div class="font-serif text-lg text-navy">{{ $p->name }}</div><div class="text-xs text-ink/50">{{ $p->type }} · {{ $p->classification }}</div></td>
                    <td class="text-sm">{{ $p->location }}</td>
                    <td class="text-xs">{{ $p->status === 'completed' ? __('Completed') : __('Ongoing') }}</td>
                    <td class="font-mono text-xs">{{ $p->rera_number ?: '—' }}</td>
                    <td class="text-right font-mono text-xs">{{ $p->units_count }}</td>
                    <td class="text-right font-mono text-xs">{{ $p->available_units_count }}</td>
                    <td class="whitespace-nowrap text-right">
                        <a href="{{ route('admin.projects.edit', $p->id) }}" class="label text-navy underline">{{ __('Edit') }}</a>
                        <a href="{{ route('admin.inventory', ['project' => $p->slug]) }}" class="label ml-3 text-navy underline">{{ __('Units') }}</a>
                        <a href="{{ route('projects.show', $p->slug) }}" target="_blank" class="label ml-3 text-ink/50 underline">{{ __('View') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
