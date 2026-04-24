@extends('layouts.admin-synth')

@include('admin.orders.partials.synth-order-ui')

@section('title', 'Đơn #'.$order->id)

@push('head')
    <style>
        .order-detail-chamfer {
            clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 12px 100%, 0 calc(100% - 12px));
        }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; color: #000 !important; }
        }
    </style>
@endpush

@section('content')
    @php
        $orderUid = '#ORD-'.str_pad((string) $order->id, 4, '0', STR_PAD_LEFT).'-'.strtoupper(substr(md5((string) $order->id), 0, 1));
        $stLabels = [
            'pending' => 'CHỜ XỬ LÝ',
            'processing' => 'ĐANG XỬ LÝ',
            'completed' => 'HOÀN THÀNH',
            'cancelled' => 'ĐÃ HỦY',
        ];
        $stClass = [
            'pending' => 'border-amber-500/40 text-amber-400 bg-amber-950/30 shadow-[0_0_16px_rgba(245,158,11,0.15)]',
            'processing' => 'border-blue-500/40 text-blue-400 bg-blue-950/30 shadow-[0_0_16px_rgba(59,130,246,0.15)]',
            'completed' => 'border-emerald-500/40 text-emerald-400 bg-emerald-950/30 shadow-[0_0_16px_rgba(34,197,94,0.15)]',
            'cancelled' => 'border-red-500/40 text-red-400 bg-red-950/30 shadow-[0_0_16px_rgba(239,68,68,0.15)]',
        ];
        $stDot = [
            'pending' => 'bg-amber-400 animate-pulse',
            'processing' => 'bg-blue-400',
            'completed' => 'bg-emerald-400',
            'cancelled' => 'bg-red-400',
        ];
        $st = $order->status;
    @endphp

    @if(session('success'))
        <div class="mb-6 px-4 py-3 border border-secondary-container/40 text-secondary-container text-sm font-headline">{{ session('success') }}</div>
    @endif

    <nav class="no-print mb-8 flex flex-wrap items-center gap-3 text-[10px] font-headline uppercase tracking-[0.25em] text-gray-500">
        <a href="{{ route('admin.orders.index', array_filter(['q' => request('q'), 'status' => request('status')])) }}" class="inline-flex items-center gap-2 text-secondary-container hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Danh sách đơn
        </a>
        <span class="text-outline-variant">/</span>
        <span class="text-on-surface-variant">Chi tiết node</span>
    </nav>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 mb-10">
        {{-- Cột trái: mã đơn + meta --}}
        <div class="xl:col-span-7 space-y-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="h-px w-8 bg-secondary-container shrink-0"></span>
                    <span class="text-secondary-container font-headline text-[10px] tracking-[0.35em] uppercase font-bold">Giao thức Alpha</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black font-headline tracking-tight text-on-background uppercase">Chi tiết đơn hàng</h1>
                <p class="mt-2 font-mono text-sm sm:text-base text-secondary-container tracking-widest">{{ $orderUid }}</p>
                <p class="mt-1 text-[10px] font-headline text-gray-500 uppercase tracking-widest">Tạo lúc {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div class="inline-flex items-center gap-2 px-4 py-2 border {{ $stClass[$st] ?? 'border-outline-variant' }}">
                <span class="w-2 h-2 rounded-full {{ $stDot[$st] ?? 'bg-gray-500' }} shrink-0"></span>
                <span class="text-[10px] font-bold uppercase tracking-[0.15em]">{{ $stLabels[$st] ?? $st }}</span>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div class="border border-outline-variant/25 bg-surface-container-low/80 p-4 order-detail-chamfer">
                    <p class="text-[10px] font-headline font-bold uppercase tracking-widest text-primary mb-2">Tài khoản</p>
                    <p class="text-sm font-headline text-on-background">{{ $order->user?->name ?? '—' }}</p>
                    <p class="text-xs text-on-surface-variant font-mono mt-1 truncate">{{ $order->user?->email ?? '—' }}</p>
                </div>
                <div class="border border-outline-variant/25 bg-surface-container-low/80 p-4 order-detail-chamfer">
                    <p class="text-[10px] font-headline font-bold uppercase tracking-widest text-secondary-container mb-2">Liên hệ giao</p>
                    <p class="text-sm font-headline text-on-background">{{ $order->phone ?? '—' }}</p>
                    @if($order->recipient_name)
                        <p class="text-xs text-on-surface-variant mt-2">Nhận: <span class="text-on-background">{{ $order->recipient_name }}</span></p>
                    @endif
                </div>
            </div>

            <div class="border border-outline-variant/20 bg-surface-container-lowest/90 p-5 sm:p-6">
                <p class="text-[10px] font-headline font-bold uppercase tracking-[0.2em] text-gray-500 mb-3">Địa chỉ giao hàng</p>
                <p class="text-sm leading-relaxed text-on-background whitespace-pre-wrap">{{ $order->address }}</p>
                @if($order->city || $order->postal_code)
                    <p class="mt-3 text-sm text-on-surface-variant">
                        @if($order->city)<span>{{ $order->city }}</span>@endif
                        @if($order->city && $order->postal_code) <span class="text-outline">·</span> @endif
                        @if($order->postal_code)<span class="font-mono text-secondary-container">{{ $order->postal_code }}</span>@endif
                    </p>
                @endif
            </div>
        </div>

        {{-- Cột phải: cập nhật trạng thái --}}
        <div class="xl:col-span-5">
            <div class="sticky top-24 border border-outline-variant/30 bg-[#0e0e0e] p-5 sm:p-6 shadow-[inset_0_0_40px_rgba(123,47,247,0.06)]">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-secondary-container">tune</span>
                    <h2 class="font-headline text-sm font-bold uppercase tracking-[0.2em] text-on-background">Điều khiển trạng thái</h2>
                </div>
                <p class="text-[10px] text-gray-500 font-headline uppercase tracking-wider mb-4 leading-relaxed">Chọn trạng thái mới — hệ thống lưu ngay khi bạn thay đổi.</p>

                <form action="{{ route('admin.orders.update-status', $order) }}" method="post" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="return_to" value="show">

                    <div>
                        <label for="order-show-status" class="block text-[10px] font-bold font-headline uppercase tracking-[0.2em] text-primary mb-2">Trạng thái đơn</label>
                        <div class="aos-select-wrap w-full max-w-full">
                            <span class="aos-corner-tl" aria-hidden="true"></span>
                            <span class="aos-corner-br" aria-hidden="true"></span>
                            <select name="status" id="order-show-status" onchange="this.form.submit()" class="aos-select text-xs sm:text-[0.7rem] py-3" title="Trạng thái">
                                @foreach(['pending' => 'CHỜ XỬ LÝ', 'processing' => 'ĐANG XỬ LÝ', 'completed' => 'HOÀN THÀNH', 'cancelled' => 'ĐÃ HỦY'] as $val => $label)
                                    <option value="{{ $val }}" @selected($order->status === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined aos-select-icon">expand_more</span>
                        </div>
                    </div>
                    <noscript>
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-primary-container to-secondary-container text-white text-xs font-headline font-bold uppercase tracking-widest">Lưu trạng thái</button>
                    </noscript>
                </form>
            </div>
        </div>
    </div>

    {{-- Bảng sản phẩm --}}
    <div class="border border-outline-variant/15 bg-surface-container-low overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-outline-variant/10 bg-surface-container-high/50 flex flex-wrap items-center justify-between gap-2">
            <h3 class="font-headline text-[10px] font-black uppercase tracking-[0.25em] text-gray-500">Danh sách line item</h3>
            <span class="text-[9px] font-mono text-gray-600">{{ $order->items->count() }} mặt hàng</span>
        </div>
        <div class="overflow-x-auto admin-orders-scrollbar">
            <table class="w-full text-left border-collapse min-w-[640px]">
                <thead>
                    <tr class="bg-surface-container-high border-b border-surface-container-lowest">
                        <th class="py-4 px-4 sm:px-6 text-[10px] font-black uppercase tracking-[0.15em] text-gray-500 font-headline">Sản phẩm</th>
                        <th class="py-4 px-4 sm:px-6 text-[10px] font-black uppercase tracking-[0.15em] text-gray-500 font-headline text-center">SL</th>
                        <th class="py-4 px-4 sm:px-6 text-[10px] font-black uppercase tracking-[0.15em] text-gray-500 font-headline text-right">Đơn giá</th>
                        <th class="py-4 px-4 sm:px-6 text-[10px] font-black uppercase tracking-[0.15em] text-gray-500 font-headline text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @foreach($order->items as $item)
                        <tr class="hover:bg-surface-container-high/40 transition-colors group">
                            <td class="py-4 px-4 sm:px-6">
                                <span class="text-sm font-headline font-bold text-on-background group-hover:text-primary transition-colors">{{ $item->product->name }}</span>
                            </td>
                            <td class="py-4 px-4 sm:px-6 text-center font-mono text-sm text-on-surface-variant tabular-nums">{{ $item->quantity }}</td>
                            <td class="py-4 px-4 sm:px-6 text-right font-mono text-sm tabular-nums text-on-surface-variant">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                            <td class="py-4 px-4 sm:px-6 text-right font-headline text-sm font-bold text-secondary-container tabular-nums">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 sm:px-6 py-5 bg-surface-container-lowest border-t border-outline-variant/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <span class="text-[10px] font-headline uppercase tracking-widest text-gray-500">Tổng thanh toán</span>
            <span class="text-2xl sm:text-3xl font-black font-headline text-transparent bg-clip-text bg-gradient-to-r from-primary-container via-primary to-secondary-container tabular-nums drop-shadow-[0_0_12px_rgba(0,244,254,0.25)]">
                {{ number_format($order->total_price, 0, ',', '.') }} đ
            </span>
        </div>
    </div>
@endsection
