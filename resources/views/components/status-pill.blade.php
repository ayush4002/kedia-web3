@props(['status'])
@php $cls = ['available' => 'pill-available', 'on_hold' => 'pill-hold', 'sold' => 'pill-sold'][$status] ?? 'pill-sold'; @endphp
<span {{ $attributes->merge(['class' => $cls]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current" aria-hidden="true"></span>
    {{ ['available' => __('Available'), 'on_hold' => __('On Hold'), 'sold' => __('Sold')][$status] ?? ucfirst($status) }}
</span>
