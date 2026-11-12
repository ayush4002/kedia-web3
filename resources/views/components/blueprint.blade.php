{{-- Faint blueprint line drawing that draws itself on scroll (see site.js wireDraw). --}}
@props(['variant' => 'haveli'])
<svg {{ $attributes->merge(['class' => 'pointer-events-none']) }} viewBox="0 0 600 420" fill="none" stroke="currentColor" stroke-width="1" data-draw aria-hidden="true">
    @if($variant === 'plots')
        <rect x="20" y="20" width="560" height="380" rx="6"/>
        <path d="M20 210h560M300 20v380"/>
        <path d="M20 80h280M20 145h280M300 275h280M300 340h280"/>
        <path d="M90 20v190M160 20v190M230 20v190M370 210v190M440 210v190M510 210v190"/>
        <path d="M300 20h280v190H300z" opacity=".5"/>
        <circle cx="440" cy="115" r="48"/>
        <path d="M392 115h96M440 67v96"/>
        <path d="M20 210h280v190H20z" opacity=".5"/>
        <path d="M60 300a60 60 0 01120 0"/>
    @else
        <path d="M60 400V170h480v230"/>
        <path d="M60 170l240-120 240 120"/>
        <path d="M240 400V270a60 60 0 01120 0v130"/>
        <path d="M110 230h70v80h-70zM420 230h70v80h-70z"/>
        <path d="M110 270h70M145 230v80M420 270h70M455 230v80"/>
        <path d="M30 400h540"/>
        <path d="M150 130a20 20 0 1140 0v40h-40zM410 130a20 20 0 1140 0v40h-40z"/>
        <path d="M300 50V20M285 20h30"/>
        <circle cx="300" cy="200" r="22"/>
        <path d="M278 200h44M300 178v44"/>
    @endif
</svg>
