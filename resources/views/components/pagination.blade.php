@if ($paginator->hasPages())
    <nav class="flex items-center justify-between gap-4" aria-label="{{ __('Page navigation') }}">
        @if ($paginator->onFirstPage())
            <span class="btn-ghost btn-sm pointer-events-none opacity-40" aria-disabled="true">← {{ __('Previous') }}</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn-ghost btn-sm">← {{ __('Previous') }}</a>
        @endif

        <span class="label text-ink/50">{{ __('Page') }} {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn-ghost btn-sm">{{ __('Next') }} →</a>
        @else
            <span class="btn-ghost btn-sm pointer-events-none opacity-40" aria-disabled="true">{{ __('Next') }} →</span>
        @endif
    </nav>
@endif
