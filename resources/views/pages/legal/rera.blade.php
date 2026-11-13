@extends('pages.legal.layout', ['heading' => __('RERA disclosures')])

@section('title', 'RERA Disclosures & Registrations | Kedia Builders & Colonizers')
@section('meta_description', 'RAJ-RERA registration numbers for every Kedia Builders & Colonizers project in Jaipur, with links to verify on the official portal.')

@section('legal')
    <p>{{ __('Every Kedia project is JDA approved. Projects registered with the Rajasthan Real Estate Regulatory Authority (RAJ-RERA) are listed below. Verify any registration, the approved plans and quarterly progress on the official portal before you buy.') }}</p>
    <a href="https://rera.rajasthan.gov.in/" target="_blank" rel="noopener" class="btn-navy">{{ __('Open RAJ-RERA portal') }}</a>
    <div class="overflow-x-auto rounded-2xl border border-navy/10 bg-paper-50">
        <table class="table-clean min-w-[32rem]">
            <caption class="sr-only">{{ __('RERA registrations by project') }}</caption>
            <thead><tr><th scope="col">{{ __('Project') }}</th><th scope="col">{{ __('Location') }}</th><th scope="col">{{ __('Status') }}</th><th scope="col">RERA</th></tr></thead>
            <tbody>
                @foreach($projects as $p)
                    <tr>
                        <td><a href="{{ route('projects.show', $p->slug) }}" class="text-navy underline decoration-brass/60 underline-offset-4 hover:text-terracotta">{{ $p->name }}</a></td>
                        <td>{{ $p->location }}</td>
                        <td>{{ $p->status === 'completed' ? __('Completed') : __('Ongoing') }}</td>
                        <td class="font-mono text-xs">{{ $p->rera_number ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="text-sm text-ink/60">{{ __('Images, layouts and specifications on this website are indicative. The agreement to sale and approved plans prevail.') }}</p>
@endsection
