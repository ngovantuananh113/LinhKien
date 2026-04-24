@extends('layouts.shop-synth')

@section('title', 'Tra cứu đơn hàng')

@push('head')
<style>
    .orders-chamfer {
        clip-path: polygon(0 0, 100% 0, 100% calc(100% - 14px), calc(100% - 14px) 100%, 0 100%);
    }
    /* Custom status dropdown — dark panel, không dùng native select */
    [data-status-panel] {
        transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1),
            transform 0.22s cubic-bezier(0.16, 1, 0.3, 1),
            visibility 0.22s;
        transform-origin: top center;
    }
    [data-status-panel][data-open="false"] {
        opacity: 0;
        transform: translateY(-6px) scale(0.98);
        pointer-events: none;
        visibility: hidden;
    }
    [data-status-panel][data-open="true"] {
        opacity: 1;
        transform: translateY(0) scale(1);
        visibility: visible;
    }
    [data-status-option] {
        transition: background-color 0.15s ease, border-color 0.15s ease, padding-left 0.15s ease;
    }
    @keyframes status-option-in {
        from {
            opacity: 0;
            transform: translateX(-6px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    [data-status-panel][data-open="true"] [data-status-option] {
        animation: status-option-in 0.28s cubic-bezier(0.22, 1, 0.36, 1) backwards;
    }
    [data-status-panel][data-open="true"] [data-status-option]:nth-child(1) { animation-delay: 0.02s; }
    [data-status-panel][data-open="true"] [data-status-option]:nth-child(2) { animation-delay: 0.04s; }
    [data-status-panel][data-open="true"] [data-status-option]:nth-child(3) { animation-delay: 0.06s; }
    [data-status-panel][data-open="true"] [data-status-option]:nth-child(4) { animation-delay: 0.08s; }
    [data-status-panel][data-open="true"] [data-status-option]:nth-child(5) { animation-delay: 0.1s; }
</style>
@endpush

@section('content')
@php
    $trangThaiDon = ['pending' => 'Chờ xử lý', 'processing' => 'Đang xử lý', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'];
    $statusPillClass = [
        'pending' => 'border-outline-variant/40 text-outline bg-surface-container-highest/40',
        'processing' => 'border-secondary-container/50 text-secondary-container bg-secondary-container/10 shadow-[0_0_12px_rgba(0,244,254,0.12)]',
        'completed' => 'border-primary-container/35 text-primary bg-primary-container/10',
        'cancelled' => 'border-red-400/35 text-red-300/90 bg-red-500/5',
    ];
    $statusDotClass = [
        '' => 'bg-gradient-to-br from-secondary-container to-primary-container shadow-[0_0_8px_rgba(0,244,254,0.45)]',
        'pending' => 'bg-outline shadow-[0_0_6px_rgba(149,141,162,0.4)]',
        'processing' => 'bg-secondary-container shadow-[0_0_8px_rgba(0,244,254,0.5)]',
        'completed' => 'bg-primary shadow-[0_0_8px_rgba(210,187,255,0.45)]',
        'cancelled' => 'bg-red-400/90 shadow-[0_0_6px_rgba(248,113,113,0.35)]',
    ];
    $statusFilterValue = request('status');
    if (! $statusFilterValue || ! isset($trangThaiDon[$statusFilterValue])) {
        $statusFilterValue = '';
    }
    $statusFilterLabel = $statusFilterValue === '' ? 'Tất cả' : $trangThaiDon[$statusFilterValue];
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-8 w-full pb-16 md:pb-20">
    <header class="mb-10 md:mb-12">
        <p class="font-headline text-[10px] sm:text-xs tracking-[0.35em] uppercase text-secondary-container mb-3">Tra cứu &amp; theo dõi</p>
        <h1 class="font-headline text-3xl sm:text-4xl md:text-5xl font-bold tracking-tighter uppercase text-primary drop-shadow-[0_0_12px_rgba(210,187,255,0.2)] leading-tight">
            Đơn hàng <span class="text-secondary-container">đã mua</span>
        </h1>
        <p class="text-on-surface-variant text-sm md:text-base mt-4 max-w-2xl leading-relaxed">
            Xem trạng thái và lịch sử mua hàng. Đặt đơn mới từ
            <a href="{{ route('cart.index') }}" class="text-secondary-container font-headline hover:underline underline-offset-4">giỏ hàng</a>.
        </p>
    </header>

    {{-- Không dùng orders-chamfer (clip-path) ở đây: clip-path cắt cả dropdown trạng thái position:absolute --}}
    <div class="relative mb-10 p-5 md:p-6 border border-outline-variant/20 bg-surface-container-low overflow-visible">
        <div class="absolute -right-8 -top-8 w-40 h-40 bg-primary-container/15 blur-3xl rounded-full pointer-events-none" aria-hidden="true"></div>
        <form id="orders-filter-form" method="get" action="{{ route('orders.index') }}" class="relative flex flex-col lg:flex-row lg:items-end gap-4 lg:gap-5">
            <div class="flex-1 min-w-0">
                <label for="order-q" class="block font-headline text-[10px] uppercase tracking-[0.25em] text-outline mb-2">Mã đơn</label>
                <div class="flex items-center gap-2 border border-outline-variant/35 bg-surface-container-lowest px-3 py-2.5 focus-within:border-secondary-container/50 transition-colors">
                    <span class="material-symbols-outlined text-outline-variant text-xl shrink-0" aria-hidden="true">tag</span>
                    <input type="text" name="q" id="order-q" value="{{ request('q') }}" inputmode="numeric" pattern="[0-9]*" placeholder="Ví dụ: 12" class="flex-1 min-w-0 bg-transparent border-0 p-0 text-on-background font-mono text-sm placeholder:text-outline-variant focus:ring-0">
                </div>
            </div>
            <div class="w-full lg:w-[min(100%,17rem)] shrink-0" id="order-status-dropdown">
                <span id="order-status-label" class="block font-headline text-[10px] uppercase tracking-[0.25em] text-outline mb-2">Trạng thái</span>
                <input type="hidden" name="status" id="order-status-value" value="{{ $statusFilterValue }}" autocomplete="off">
                <div class="relative z-[60]">
                    <button type="button" id="order-status-trigger" aria-haspopup="listbox" aria-expanded="false" aria-labelledby="order-status-label" data-status-trigger
                        class="group flex w-full items-center gap-2 border border-outline-variant/35 bg-surface-container-lowest pl-3 pr-2 py-2.5 text-left text-sm text-on-background transition-all duration-200 hover:border-secondary-container/45 focus:outline-none focus-visible:ring-2 focus-visible:ring-secondary-container/50 focus-visible:border-secondary-container/60">
                        <span class="material-symbols-outlined text-outline-variant text-xl shrink-0 transition-colors group-hover:text-secondary-container" aria-hidden="true">filter_list</span>
                        <span class="flex-1 min-w-0 truncate font-headline" data-status-label>{{ $statusFilterLabel }}</span>
                        <span class="material-symbols-outlined text-outline-variant text-xl shrink-0 transition-transform duration-300 ease-out" data-status-chevron aria-hidden="true">expand_more</span>
                    </button>
                    <div data-status-panel data-open="false" role="listbox" aria-labelledby="order-status-label"
                        class="absolute left-0 right-0 top-[calc(100%+6px)] max-h-[min(22rem,70vh)] overflow-y-auto overscroll-contain rounded-none border border-secondary-container/35 bg-[#141418] py-1.5 shadow-[0_12px_40px_rgba(0,0,0,0.65),0_0_0_1px_rgba(0,244,254,0.08)] ring-1 ring-secondary-container/15 synth-scrollbar z-[70]">
                        <button type="button" role="option" data-status-option data-value="" data-label="Tất cả" @if($statusFilterValue === '') aria-selected="true" @else aria-selected="false" @endif
                            class="flex w-full items-center gap-3 px-3 py-2.5 text-left text-sm border-l-[3px] border-transparent hover:bg-primary-container/12 hover:border-l-secondary-container/70 focus:outline-none focus-visible:bg-primary-container/15 {{ $statusFilterValue === '' ? 'bg-primary-container/10 border-l-secondary-container' : '' }}">
                            <span class="h-2 w-2 shrink-0 rounded-full {{ $statusDotClass[''] }}" aria-hidden="true"></span>
                            <span class="font-headline text-on-background">Tất cả</span>
                            @if($statusFilterValue === '')
                                <span class="material-symbols-outlined ml-auto text-secondary-container text-lg shrink-0" aria-hidden="true">check</span>
                            @endif
                        </button>
                        @foreach($trangThaiDon as $val => $label)
                            <button type="button" role="option" data-status-option data-value="{{ $val }}" data-label="{{ $label }}" @if($statusFilterValue === $val) aria-selected="true" @else aria-selected="false" @endif
                                class="flex w-full items-center gap-3 px-3 py-2.5 text-left text-sm border-l-[3px] border-transparent hover:bg-primary-container/12 hover:border-l-secondary-container/70 focus:outline-none focus-visible:bg-primary-container/15 {{ $statusFilterValue === $val ? 'bg-primary-container/10 border-l-secondary-container' : '' }}">
                                <span class="h-2 w-2 shrink-0 rounded-full {{ $statusDotClass[$val] ?? 'bg-outline' }}" aria-hidden="true"></span>
                                <span class="font-headline text-on-background">{{ $label }}</span>
                                @if($statusFilterValue === $val)
                                    <span class="material-symbols-outlined ml-auto text-secondary-container text-lg shrink-0" aria-hidden="true">check</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[46px] bg-gradient-to-r from-primary-container to-secondary-container text-on-primary font-headline text-xs font-bold uppercase tracking-widest hover:brightness-110 active:scale-[0.98] transition-all shadow-[0_0_20px_rgba(123,47,247,0.2)] orders-chamfer">
                    <span class="material-symbols-outlined text-lg">search</span>
                    Tra cứu
                </button>
                @if(request()->hasAny(['q', 'status']))
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center px-4 py-3 min-h-[46px] border border-outline-variant/40 text-outline text-xs font-headline uppercase tracking-widest hover:border-primary/40 hover:text-primary transition-colors">
                        Xóa lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-16 md:py-20 px-6 border border-dashed border-outline-variant/30 bg-surface-container-low/50 orders-chamfer">
            <span class="material-symbols-outlined text-5xl text-outline-variant/40 mb-4 block" aria-hidden="true">receipt_long</span>
            <p class="text-on-surface-variant font-body mb-2">Không có đơn phù hợp bộ lọc.</p>
            <p class="text-sm text-outline mb-8"><a href="{{ route('products.index') }}" class="text-secondary-container hover:underline">Khám phá sản phẩm</a> hoặc điều chỉnh tìm kiếm.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <article class="group relative border border-outline-variant/20 bg-surface-container-low hover:border-secondary-container/35 hover:bg-surface-container transition-all duration-200 orders-chamfer">
                    <div class="p-5 md:p-6 flex flex-col md:flex-row md:items-center gap-5 md:gap-8">
                        <div class="flex-1 min-w-0 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 md:items-center">
                            <div>
                                <p class="font-headline text-[10px] uppercase tracking-widest text-outline mb-1">Mã đơn</p>
                                <p class="font-mono text-lg md:text-xl text-secondary-container font-semibold tracking-tight">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <p class="font-headline text-[10px] uppercase tracking-widest text-outline mb-1">Đặt lúc</p>
                                <p class="text-sm text-on-background flex items-center gap-2">
                                    <span class="material-symbols-outlined text-outline-variant text-lg shrink-0" aria-hidden="true">schedule</span>
                                    {{ $order->created_at->format('d/m/Y') }}
                                    <span class="text-on-surface-variant">{{ $order->created_at->format('H:i') }}</span>
                                </p>
                            </div>
                            <div>
                                <p class="font-headline text-[10px] uppercase tracking-widest text-outline mb-1">Tổng thanh toán</p>
                                <p class="text-lg font-headline text-primary tabular-nums">{{ number_format($order->total_price, 0, ',', '.') }} <span class="text-sm text-outline">đ</span></p>
                            </div>
                            <div class="sm:col-span-2 lg:col-span-1 lg:justify-self-start">
                                <p class="font-headline text-[10px] uppercase tracking-widest text-outline mb-2">Trạng thái</p>
                                <span class="inline-flex items-center px-3 py-1.5 text-[11px] font-headline font-bold uppercase tracking-wider border {{ $statusPillClass[$order->status] ?? 'border-outline-variant/30 text-on-surface-variant' }}">
                                    {{ $trangThaiDon[$order->status] ?? $order->status }}
                                </span>
                            </div>
                        </div>
                        <div class="flex md:flex-col shrink-0 gap-2 md:min-w-[140px]">
                            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center justify-center gap-2 w-full md:w-auto px-5 py-3 border border-secondary-container/50 text-secondary-container font-headline text-xs font-bold uppercase tracking-widest hover:bg-secondary-container/15 hover:border-secondary-container transition-colors">
                                Chi tiết
                                <span class="material-symbols-outlined text-base group-hover:translate-x-0.5 transition-transform">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10 md:mt-12">
            {{ $orders->links('vendor.pagination.synth') }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    var root = document.getElementById('order-status-dropdown');
    if (!root) return;

    var trigger = root.querySelector('[data-status-trigger]');
    var panel = root.querySelector('[data-status-panel]');
    var hidden = document.getElementById('order-status-value');
    var labelEl = root.querySelector('[data-status-label]');
    var chevron = root.querySelector('[data-status-chevron]');
    var options = root.querySelectorAll('[data-status-option]');
    var open = false;

    function setOpen(v) {
        open = v;
        panel.setAttribute('data-open', v ? 'true' : 'false');
        trigger.setAttribute('aria-expanded', v ? 'true' : 'false');
        if (chevron) chevron.classList.toggle('rotate-180', v);
    }

    function syncSelection(selectedBtn) {
        var val = selectedBtn.getAttribute('data-value') || '';
        var lab = selectedBtn.getAttribute('data-label') || 'Tất cả';
        hidden.value = val;
        if (labelEl) labelEl.textContent = lab;

        options.forEach(function (btn) {
            var isSel = btn === selectedBtn;
            btn.setAttribute('aria-selected', isSel ? 'true' : 'false');
            btn.classList.toggle('bg-primary-container/10', isSel);
            btn.classList.toggle('border-l-secondary-container', isSel);
            var check = btn.querySelector('[data-status-check]');
            if (check) check.remove();
            if (isSel) {
                var icon = document.createElement('span');
                icon.className = 'material-symbols-outlined ml-auto text-secondary-container text-lg shrink-0';
                icon.setAttribute('data-status-check', '');
                icon.setAttribute('aria-hidden', 'true');
                icon.textContent = 'check';
                btn.appendChild(icon);
            }
        });
    }

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        setOpen(!open);
    });

    options.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            syncSelection(btn);
            setOpen(false);
        });
    });

    document.addEventListener('click', function () {
        if (open) setOpen(false);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && open) {
            setOpen(false);
        }
    });
})();
</script>
@endpush
