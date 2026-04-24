@extends('layouts.admin-synth')

@include('admin.orders.partials.synth-order-ui')

@section('title', 'Quản lý đơn hàng')

@push('head')
    <style>
        @keyframes admin-order-row-in {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .admin-order-row { animation: admin-order-row-in 0.4s ease-out both; }
    </style>
@endpush

@section('content')
    @php
        $stLabels = [
            'pending' => 'CHỜ XỬ LÝ',
            'processing' => 'ĐANG XỬ LÝ',
            'completed' => 'HOÀN THÀNH',
            'cancelled' => 'ĐÃ HỦY',
        ];
        $stClass = [
            'pending' => 'bg-amber-950/40 border-amber-500/35 text-amber-400 shadow-[0_0_10px_rgba(245,158,11,0.15)]',
            'processing' => 'bg-blue-950/40 border-blue-500/35 text-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.15)]',
            'completed' => 'bg-emerald-950/40 border-emerald-500/35 text-emerald-400 shadow-[0_0_10px_rgba(34,197,94,0.15)]',
            'cancelled' => 'bg-red-950/40 border-red-500/35 text-red-400 shadow-[0_0_10px_rgba(239,68,68,0.15)]',
        ];
        $stDot = [
            'pending' => 'bg-amber-400 animate-pulse',
            'processing' => 'bg-blue-400',
            'completed' => 'bg-emerald-400',
            'cancelled' => 'bg-red-400',
        ];
        $filterStatus = request('status', '');
        $exportUrl = route('admin.orders.index', array_merge(array_filter(['q' => request('q'), 'status' => request('status')]), ['export' => 'csv']));
    @endphp

    @if(session('success'))
        <div class="mb-6 px-4 py-3 border border-secondary-container/40 text-secondary-container text-sm font-headline relative z-10">{{ session('success') }}</div>
    @endif

    {{-- Tiêu đề + thống kê (thiết kế ORDER_MANAGEMENT) --}}
    <div class="mb-8 lg:mb-12 flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="h-px w-8 bg-secondary-container shrink-0"></span>
                <span class="text-secondary-container font-headline text-[10px] sm:text-xs tracking-[0.35em] uppercase font-bold">Giao thức Alpha</span>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black font-headline tracking-tighter text-on-background uppercase">Quản lý đơn hàng</h1>
            <p class="mt-2 text-on-surface-variant font-headline text-xs tracking-widest uppercase opacity-80">Quản lý đơn — đồng bộ trạng thái &amp; vận đơn</p>
        </div>
        <div class="flex flex-wrap gap-3 sm:gap-4">
            <div class="bg-surface-container-low px-4 py-3 flex flex-col min-w-[9rem] border-b-2 border-primary-container shadow-[inset_0_1px_0_rgba(255,255,255,0.03)]">
                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Tổng sản lượng</span>
                <span class="text-lg sm:text-xl font-headline font-bold text-primary tabular-nums">{{ number_format($totalVolumeUnits) }} <span class="text-xs text-primary/70">đv</span></span>
            </div>
            <div class="bg-surface-container-low px-4 py-3 flex flex-col min-w-[9rem] border-b-2 border-secondary-container shadow-[inset_0_1px_0_rgba(255,255,255,0.03)]">
                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Đơn đang xử lý</span>
                <span class="text-lg sm:text-xl font-headline font-bold text-secondary-container tabular-nums">{{ number_format($activeTransfers) }} <span class="text-xs text-secondary-container/70">sync</span></span>
            </div>
        </div>
    </div>

    {{-- Thanh công cụ: lọc + tải / in --}}
    <form id="admin-orders-filter" method="get" action="{{ route('admin.orders.index') }}" class="bg-surface-container-low p-3 sm:p-4 mb-1 flex flex-wrap items-center justify-between gap-4 border border-outline-variant/10">
        <div class="flex flex-wrap gap-3 sm:gap-4 items-center">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <div class="aos-select-wrap min-w-[13.5rem] sm:min-w-[15rem]">
                <span class="aos-corner-tl" aria-hidden="true"></span>
                <span class="aos-corner-br" aria-hidden="true"></span>
                <label class="sr-only" for="filter-order-status">Lọc theo trạng thái</label>
                <select name="status" id="filter-order-status" onchange="this.form.submit()" class="aos-select" title="Trạng thái đơn hàng">
                    <option value="" @selected($filterStatus === '')>TẤT CẢ TRẠNG THÁI</option>
                    <option value="pending" @selected($filterStatus === 'pending')>CHỜ XỬ LÝ</option>
                    <option value="processing" @selected($filterStatus === 'processing')>ĐANG XỬ LÝ</option>
                    <option value="completed" @selected($filterStatus === 'completed')>HOÀN THÀNH</option>
                    <option value="cancelled" @selected($filterStatus === 'cancelled')>ĐÃ HỦY</option>
                </select>
                <span class="material-symbols-outlined aos-select-icon">expand_more</span>
            </div>
            <button type="submit" class="flex items-center gap-2 px-4 py-2.5 border border-outline-variant/50 hover:bg-surface-container-highest hover:border-secondary-container/40 transition-all text-[10px] font-bold font-headline tracking-[0.2em] uppercase text-on-surface-variant">
                <span class="material-symbols-outlined text-base text-secondary-container">filter_list</span>
                Áp dụng lọc
            </button>
        </div>
        <div class="flex gap-2">
            <a href="{{ $exportUrl }}" class="p-2.5 border border-outline-variant/40 hover:border-primary-container/50 hover:text-primary transition-colors" title="Tải CSV">
                <span class="material-symbols-outlined text-xl">download</span>
            </a>
            <button type="button" onclick="window.print()" class="p-2.5 border border-outline-variant/40 hover:border-primary-container/50 hover:text-primary transition-colors" title="In trang">
                <span class="material-symbols-outlined text-xl">print</span>
            </button>
        </div>
    </form>

    {{-- Bảng --}}
    <div class="bg-surface-container-low border border-outline-variant/10 overflow-hidden">
        <div class="overflow-x-auto admin-orders-scrollbar">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-surface-container-high border-b border-surface-container-lowest">
                        <th class="px-4 sm:px-6 py-4 text-[10px] font-black tracking-[0.2em] text-gray-500 uppercase font-headline">Mã đơn</th>
                        <th class="px-4 sm:px-6 py-4 text-[10px] font-black tracking-[0.2em] text-gray-500 uppercase font-headline">Người đặt / Nguồn</th>
                        <th class="px-4 sm:px-6 py-4 text-[10px] font-black tracking-[0.2em] text-gray-500 uppercase font-headline">Giá trị</th>
                        <th class="px-4 sm:px-6 py-4 text-[10px] font-black tracking-[0.2em] text-gray-500 uppercase font-headline">Trạng thái</th>
                        <th class="px-4 sm:px-6 py-4 text-[10px] font-black tracking-[0.2em] text-gray-500 uppercase font-headline text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-lowest/80">
                    @forelse($orders as $order)
                        @php
                            $uid = '#ORD-'.str_pad((string) $order->id, 4, '0', STR_PAD_LEFT).'-'.strtoupper(substr(md5((string) $order->id), 0, 1));
                            $displayName = $order->user?->name ?? $order->recipient_name ?? 'Khách';
                            $nodeName = mb_strtoupper(preg_replace('/\s+/', '_', trim($displayName)) ?: 'USER_NODE');
                            $subLine = $order->phone ?? $order->user?->email ?? '—';
                            $st = $order->status;
                        @endphp
                        <tr class="admin-order-row group hover:bg-surface-container-high/80 transition-colors duration-200" style="animation-delay: {{ min($loop->index * 40, 500) }}ms;">
                            <td class="px-4 sm:px-6 py-5 font-headline font-medium text-secondary-container text-sm tracking-widest tabular-nums">{{ $uid }}</td>
                            <td class="px-4 sm:px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-surface-container-highest flex items-center justify-center border border-outline-variant/40 shrink-0 group-hover:border-secondary-container/40 transition-colors">
                                        <span class="material-symbols-outlined text-lg text-primary">person</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold uppercase tracking-tight text-on-background truncate max-w-[14rem]" title="{{ $displayName }}">{{ $nodeName }}</div>
                                        <div class="text-[9px] text-gray-500 font-mono tracking-tight truncate max-w-[16rem]">{{ $subLine }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-5 font-headline font-bold text-on-background tabular-nums">{{ number_format((float) $order->total_price, 0, ',', '.') }} đ</td>
                            <td class="px-4 sm:px-6 py-5">
                                <div class="inline-flex items-center gap-2 px-3 py-1 border {{ $stClass[$st] ?? 'border-outline-variant/30 text-gray-400' }}">
                                    <div class="w-1.5 h-1.5 shrink-0 rounded-full {{ $stDot[$st] ?? 'bg-gray-500' }}"></div>
                                    <span class="text-[9px] font-bold uppercase tracking-[0.12em]">{{ $stLabels[$st] ?? $st }}</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-3 flex-wrap">
                                    <form action="{{ route('admin.orders.update-status', $order) }}" method="post" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="return_to" value="index">
                                        <input type="hidden" name="return_q" value="{{ request('q') }}">
                                        <input type="hidden" name="filter_status" value="{{ request('status') }}">
                                        <input type="hidden" name="return_page" value="{{ $orders->currentPage() }}">
                                        <label class="sr-only" for="status-{{ $order->id }}">Đổi trạng thái đơn {{ $uid }}</label>
                                        <div class="aos-select-wrap aos-select-wrap--sm w-[7.25rem] sm:w-[8rem]">
                                            <span class="aos-corner-tl" aria-hidden="true"></span>
                                            <span class="aos-corner-br" aria-hidden="true"></span>
                                            <select name="status" id="status-{{ $order->id }}" onchange="this.form.submit()" class="aos-select" title="Cập nhật trạng thái">
                                                @foreach(['pending' => 'CHỜ', 'processing' => 'XỬ LÝ', 'completed' => 'XONG', 'cancelled' => 'HỦY'] as $val => $short)
                                                    <option value="{{ $val }}" @selected($order->status === $val)>{{ $short }}</option>
                                                @endforeach
                                            </select>
                                            <span class="material-symbols-outlined aos-select-icon">expand_more</span>
                                        </div>
                                    </form>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center justify-center p-2 text-gray-500 hover:text-secondary-container border border-transparent hover:border-outline-variant/40 transition-colors" title="Xem chi tiết">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 px-6 text-center text-on-surface-variant font-headline text-sm">Chưa có đơn hàng nào khớp bộ lọc.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->total() > 0)
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-surface-container-low px-4 sm:px-6 py-4 border-t border-outline-variant/10">
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest text-center sm:text-left">
                    Hiển thị {{ $orders->firstItem() }} – {{ $orders->lastItem() }} / {{ number_format($orders->total()) }} kết quả
                </div>
                <div class="flex justify-center sm:justify-end w-full sm:w-auto">
                    {{ $orders->links('vendor.pagination.synth-inventory') }}
                </div>
            </div>
        @endif
    </div>
@endsection
