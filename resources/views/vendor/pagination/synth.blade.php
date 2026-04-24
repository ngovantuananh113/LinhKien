@if ($paginator->hasPages())
    <nav class="synth-pagination w-full max-w-full" role="navigation" aria-label="Phân trang sản phẩm">
        {{-- Mobile: nút trước / sau + icon --}}
        <div class="flex sm:hidden items-center justify-between gap-3 w-full">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center gap-2 min-h-[44px] px-4 py-2 text-xs font-headline uppercase tracking-widest text-on-surface-variant/50 border border-outline-variant/20 rounded-full cursor-not-allowed bg-surface-container-highest/50">
                    <span class="material-symbols-outlined text-lg opacity-50">chevron_left</span>
                    Trước
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center gap-2 min-h-[44px] px-4 py-2 text-xs font-headline uppercase tracking-widest text-on-surface border border-outline-variant/40 rounded-full bg-surface-container-low hover:border-secondary-container hover:text-secondary-container hover:shadow-[0_0_16px_rgba(0,244,254,0.2)] active:scale-[0.98] transition-all duration-200">
                    <span class="material-symbols-outlined text-lg text-secondary-container">chevron_left</span>
                    Trước
                </a>
            @endif

            <span class="text-[10px] font-headline uppercase tracking-widest text-outline whitespace-nowrap">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center gap-2 min-h-[44px] px-4 py-2 text-xs font-headline uppercase tracking-widest text-on-surface border border-outline-variant/40 rounded-full bg-surface-container-low hover:border-secondary-container hover:text-secondary-container hover:shadow-[0_0_16px_rgba(0,244,254,0.2)] active:scale-[0.98] transition-all duration-200">
                    Tiếp
                    <span class="material-symbols-outlined text-lg text-secondary-container">chevron_right</span>
                </a>
            @else
                <span class="inline-flex items-center justify-center gap-2 min-h-[44px] px-4 py-2 text-xs font-headline uppercase tracking-widest text-on-surface-variant/50 border border-outline-variant/20 rounded-full cursor-not-allowed bg-surface-container-highest/50">
                    Tiếp
                    <span class="material-symbols-outlined text-lg opacity-50">chevron_right</span>
                </span>
            @endif
        </div>

        {{-- Desktop & tablet --}}
        <div class="hidden sm:flex flex-col gap-4 w-full">
            <p class="text-[11px] sm:text-xs font-body text-on-surface-variant/80 tracking-wide text-center sm:text-left">
                <span class="text-outline uppercase tracking-widest text-[10px] mr-1">Hiển thị</span>
                @if ($paginator->firstItem())
                    <span class="font-headline text-secondary-container tabular-nums">{{ $paginator->firstItem() }}</span>
                    <span class="text-outline mx-1">→</span>
                    <span class="font-headline text-secondary-container tabular-nums">{{ $paginator->lastItem() }}</span>
                @else
                    <span class="font-headline text-secondary-container tabular-nums">{{ $paginator->count() }}</span>
                @endif
                <span class="text-outline mx-2">/</span>
                <span class="tabular-nums text-on-surface">{{ $paginator->total() }}</span>
                <span class="text-outline ml-1">kết quả</span>
            </p>

            <div class="flex flex-wrap items-center justify-center sm:justify-end gap-2">
                {{-- Trang trước --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center w-10 h-10 shrink-0 rounded-full border border-outline-variant/25 bg-surface-container-highest/40 text-on-surface-variant/40 cursor-not-allowed" aria-disabled="true" title="Trang trước">
                        <span class="material-symbols-outlined text-xl">chevron_left</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-10 h-10 shrink-0 rounded-full border border-outline-variant/40 bg-surface-container-low text-primary hover:border-secondary-container hover:text-secondary-container hover:shadow-[0_0_18px_rgba(0,244,254,0.25)] active:scale-95 transition-all duration-200" title="Trang trước" aria-label="Trang trước">
                        <span class="material-symbols-outlined text-xl">chevron_left</span>
                    </a>
                @endif

                {{-- Số trang --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center min-w-[2.5rem] h-10 px-2 text-sm font-headline text-outline select-none">…</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex items-center justify-center min-w-[2.5rem] h-10 px-3 rounded-full border-2 border-secondary-container bg-secondary-container/15 text-secondary-container font-headline text-sm font-bold shadow-[0_0_20px_rgba(0,244,254,0.35)] tabular-nums">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[2.5rem] h-10 px-3 rounded-full border border-outline-variant/35 bg-surface-container-low text-on-surface font-headline text-sm tabular-nums hover:border-secondary-container/60 hover:text-secondary-container hover:bg-surface-container-high transition-all duration-200 active:scale-95" aria-label="Trang {{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Trang sau --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-10 h-10 shrink-0 rounded-full border border-outline-variant/40 bg-surface-container-low text-primary hover:border-secondary-container hover:text-secondary-container hover:shadow-[0_0_18px_rgba(0,244,254,0.25)] active:scale-95 transition-all duration-200" title="Trang sau" aria-label="Trang sau">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </a>
                @else
                    <span class="inline-flex items-center justify-center w-10 h-10 shrink-0 rounded-full border border-outline-variant/25 bg-surface-container-highest/40 text-on-surface-variant/40 cursor-not-allowed" aria-disabled="true" title="Trang sau">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
