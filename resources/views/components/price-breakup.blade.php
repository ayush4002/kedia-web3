{{-- Itemised cost sheet rows for a unit (from Unit::computePricing()). --}}
@props(['pricing', 'dark' => false])
@php
    $rows = [
        [__('Base agreement value'), $pricing['base_price']],
        [__('Infrastructure development charges'), $pricing['development_charges']],
        [__('Club membership'), $pricing['club_charges']],
        [__('Parking'), $pricing['parking_charges']],
        [__('GST'), $pricing['gst']],
        [__('Stamp duty & registration (est.)'), $pricing['stamp_duty_estimate']],
        [__('Other charges'), $pricing['other_charges']],
    ];
    $muted = $dark ? 'text-paper/60' : 'text-ink/60';
    $line = $dark ? 'border-paper/10' : 'border-navy/10';
@endphp
<dl {{ $attributes->merge(['class' => 'text-sm']) }}>
    @foreach($rows as $i => [$label, $amount])
        @if($amount != 0 || $i === 0)
            <div class="flex items-center justify-between gap-4 border-b {{ $line }} py-2.5">
                <dt class="{{ $muted }}">{{ $label }}</dt>
                <dd class="font-mono {{ $i === 0 ? 'font-medium' : '' }}">₹ {{ \App\Support\Format::inr($amount) }}</dd>
            </div>
        @endif
    @endforeach
    @if($pricing['gst'] == 0)
        <p class="pt-2 text-xs {{ $muted }}">{{ __('No GST applies to this unit.') }}</p>
    @endif
    <div class="flex items-end justify-between gap-4 pt-4">
        <dt class="label {{ $muted }}">{{ __('All-inclusive total') }}</dt>
        <dd class="font-serif text-3xl {{ $dark ? 'text-brass' : 'text-navy' }}">₹ {{ \App\Support\Format::inr($pricing['grand_total']) }}</dd>
    </div>
</dl>
