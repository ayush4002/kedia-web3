@extends('layouts.app')

@section('robots', 'noindex, nofollow')

@php
    $nav = [
        'admin.dashboard' => __('Overview'),
        'admin.inventory' => __('Inventory'),
        'admin.projects' => __('Projects'),
        'admin.bookings' => __('Bookings'),
        'admin.leads' => __('Leads'),
    ];
@endphp

@section('content')
<section class="section-navy">
    <div class="container-site flex flex-wrap items-center justify-between gap-4 py-6">
        <div>
            <div class="eyebrow">{{ __('Admin console') }}</div>
            <h1 class="font-serif text-3xl text-paper">@yield('admin_title')</h1>
        </div>
        <div class="flex flex-wrap gap-2">
            @yield('admin_actions')
        </div>
    </div>
    <nav class="container-site flex gap-1 overflow-x-auto" aria-label="{{ __('Admin sections') }}">
        @foreach($nav as $r => $label)
            <a href="{{ route($r) }}" class="flex-none rounded-t-xl px-5 py-3 text-xs font-semibold uppercase tracking-[0.14em] transition {{ request()->routeIs($r . '*') ? 'bg-paper text-navy' : 'text-paper/60 hover:text-paper' }}" @if(request()->routeIs($r . '*')) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </nav>
</section>
<section class="section-paper min-h-[60vh] py-10">
    <div class="container-site">
        @yield('admin')
    </div>
</section>
@endsection
