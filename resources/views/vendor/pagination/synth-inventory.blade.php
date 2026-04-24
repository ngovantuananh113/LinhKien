@if ($paginator->hasPages())
    <nav class="flex flex-wrap items-center justify-end gap-1" role="navigation" aria-label="Phân trang">
        @if ($paginator->onFirstPage())
            <span class="inline-flex w-8 h-8 items-center justify-center bg-surface-container-high text-on-surface-variant/40 cursor-not-allowed" aria-disabled="true">
                <span class="material-symbols-outlined text-lg">chevron_left</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex w-8 h-8 items-center justify-center bg-surface-container text-gray-400 hover:bg-primary-container hover:text-white transition-all" aria-label="Trang trước">
                <span class="material-symbols-outlined text-lg">chevron_left</span>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex min-w-[2rem] h-8 items-center justify-center text-[10px] font-headline text-gray-500 px-1">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="inline-flex w-8 h-8 items-center justify-center bg-primary-container text-white font-headline text-xs font-bold tabular-nums">{{ str_pad((string) $page, 2, '0', STR_PAD_LEFT) }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex w-8 h-8 items-center justify-center bg-surface-container text-gray-400 hover:bg-primary-container hover:text-white font-headline text-xs tabular-nums transition-all" aria-label="Trang {{ $page }}">{{ str_pad((string) $page, 2, '0', STR_PAD_LEFT) }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex w-8 h-8 items-center justify-center bg-surface-container text-gray-400 hover:bg-primary-container hover:text-white transition-all" aria-label="Trang sau">
                <span class="material-symbols-outlined text-lg">chevron_right</span>
            </a>
        @else
            <span class="inline-flex w-8 h-8 items-center justify-center bg-surface-container-high text-on-surface-variant/40 cursor-not-allowed" aria-disabled="true">
                <span class="material-symbols-outlined text-lg">chevron_right</span>
            </span>
        @endif
    </nav>
@endif
