@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <x-ui.button variant="outline" disabled>
                {!! __('pagination.previous') !!}
            </x-ui.button>
        @else
            <x-ui.button variant="outline" as="a" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                {!! __('pagination.previous') !!}
            </x-ui.button>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <x-ui.button variant="outline" as="a" href="{{ $paginator->nextPageUrl() }}" rel="next">
                {!! __('pagination.next') !!}
            </x-ui.button>
        @else
            <x-ui.button variant="outline" disabled>
                {!! __('pagination.next') !!}
            </x-ui.button>
        @endif
    </nav>
@endif
