@props(['eyebrow' => null, 'title', 'image' => null, 'crumb' => null])
<section class="section-navy overflow-hidden">
    <div class="blueprint-grid pointer-events-none absolute inset-0" aria-hidden="true"></div>
    <x-blueprint class="absolute -right-16 bottom-0 hidden h-80 w-[30rem] text-brass/15 md:block" />
    <div class="container-site relative grid items-end gap-10 py-14 lg:grid-cols-12 lg:py-20">
        <div class="{{ $image ? 'lg:col-span-7' : 'lg:col-span-9' }}">
            <nav class="label text-paper/40" aria-label="{{ __('Breadcrumb') }}">
                <a href="{{ route('home') }}" class="hover:text-brass">{{ __('Home') }}</a> / {{ $crumb ?? $title }}
            </nav>
            @if($eyebrow)<div class="eyebrow mt-6 flex items-center gap-3" data-reveal><span class="h-px w-8 bg-brass" aria-hidden="true"></span>{{ $eyebrow }}</div>@endif
            <h1 class="display-xl mt-4 text-paper" data-reveal data-reveal-delay="100">{{ $title }}</h1>
            @if(trim($slot))<div class="mt-6 max-w-2xl text-lg leading-relaxed text-paper/70" data-reveal data-reveal-delay="200">{{ $slot }}</div>@endif
        </div>
        @if($image)
            <div class="lg:col-span-5">
                <div class="arch aspect-[4/5] max-h-[28rem] border border-brass/40 bg-navy-900" data-reveal>
                    <img fetchpriority="high" src="{{ str_starts_with($image, 'http') ? $image : asset(ltrim($image, '/')) }}" alt="" class="h-full w-full object-cover">
                </div>
            </div>
        @endif
    </div>
</section>
