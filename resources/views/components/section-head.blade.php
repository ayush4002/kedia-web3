@props(['eyebrow' => null, 'title', 'dark' => false, 'align' => 'left'])
<div {{ $attributes->merge(['class' => ($align === 'center' ? 'mx-auto text-center ' : '') . 'max-w-3xl']) }} data-reveal>
    @if($eyebrow)
        <div class="eyebrow flex items-center gap-3 {{ $align === 'center' ? 'justify-center' : '' }}">
            <span class="h-px w-8 bg-brass" aria-hidden="true"></span>{{ $eyebrow }}
        </div>
    @endif
    <h2 class="display-lg mt-4 {{ $dark ? 'text-paper' : 'text-navy' }}">{{ $title }}</h2>
    @if(trim($slot))
        <div class="mt-5 text-base leading-relaxed {{ $dark ? 'text-paper/70' : 'text-ink/70' }}">{{ $slot }}</div>
    @endif
</div>
