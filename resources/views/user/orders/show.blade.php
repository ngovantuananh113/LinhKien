@extends('layouts.shop-synth')

@section('title', 'Đơn #'.$order->id)

@push('head')
<style>
    .order-detail-chamfer {
        clip-path: polygon(0 0, 100% 0, 100% calc(100% - 14px), calc(100% - 14px) 100%, 0 100%);
    }
</style>
@endpush

@section('content')
@php
    $trangThaiDon = ['pending' => 'Chờ xử lý', 'processing' => 'Đang xử lý', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'];
    $statusPillClass = [
        'pending' => 'border-outline-variant/40 text-outline bg-surface-container-highest/40',
        'processing' => 'border-secondary-container/50 text-secondary-container bg-secondary-container/10 shadow-[0_0_14px_rgba(0,244,254,0.15)]',
        'completed' => 'border-primary-container/35 text-primary bg-primary-container/10',
        'cancelled' => 'border-red-400/35 text-red-300/90 bg-red-500/5',
    ];
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-8 w-full pb-16 md:pb-20">
    <p class="mb-8">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-secondary-container font-headline text-xs uppercase tracking-widest hover:text-primary transition-colors group">
            <span class="material-symbols-outlined text-lg group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            Danh sách đơn hàng
        </a>
    </p>

    <header class="relative mb-10 md:mb-12 overflow-hidden border border-outline-variant/20 bg-surface-container-low p-6 md:p-8 lg:p-10 order-detail-chamfer">
        <div class="absolute -right-12 -top-16 w-56 h-56 bg-primary-container/12 blur-3xl rounded-full pointer-events-none" aria-hidden="true"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div>
                <p class="font-headline text-[10px] uppercase tracking-[0.35em] text-outline mb-3">Chi tiết đơn</p>
                <h1 class="font-headline text-3xl sm:text-4xl md:text-5xl font-bold tracking-tighter uppercase text-primary">
                    #<span class="text-secondary-container">{{ $order->id }}</span>
                </h1>
                <p class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-on-surface-variant">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-secondary-container text-lg" aria-hidden="true">calendar_today</span>
                        {{ $order->created_at->format('d/m/Y') }}
                        <span class="text-outline">{{ $order->created_at->format('H:i') }}</span>
                    </span>
                </p>
            </div>
            <div class="flex flex-col items-start lg:items-end gap-3 shrink-0">
                <span class="text-[10px] font-headline uppercase tracking-widest text-outline">Trạng thái</span>
                <span class="inline-flex items-center px-4 py-2 text-xs font-headline font-bold uppercase tracking-wider border {{ $statusPillClass[$order->status] ?? 'border-outline-variant/30 text-on-surface-variant' }}">
                    {{ $trangThaiDon[$order->status] ?? $order->status }}
                </span>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
        <div class="lg:col-span-7 xl:col-span-8 space-y-8">
            <section aria-labelledby="order-lines-heading">
                <h2 id="order-lines-heading" class="font-headline text-sm uppercase tracking-[0.25em] text-primary mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary-container text-xl" aria-hidden="true">inventory_2</span>
                    Sản phẩm trong đơn
                </h2>
                <ul class="space-y-4">
                    @foreach($order->items as $item)
                        @php $p = $item->product; @endphp
                        <li class="border border-outline-variant/20 bg-surface-container-low hover:border-outline-variant/35 transition-colors order-detail-chamfer">
                            <div class="flex flex-col sm:flex-row gap-4 p-4 sm:p-5">
                                <a href="{{ route('products.show', $p) }}" class="shrink-0 w-full sm:w-28 h-36 sm:h-28 bg-surface-container-highest block overflow-hidden border border-outline-variant/15">
                                    @if($p->imageUrl())
                                        <img src="{{ $p->imageUrl() }}" alt="" class="w-full h-full object-cover mix-blend-lighten opacity-90 hover:opacity-100 transition-opacity">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-4xl text-outline-variant/30">image</span></div>
                                    @endif
                                </a>
                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <a href="{{ route('products.show', $p) }}" class="font-headline text-lg text-on-background hover:text-secondary-container transition-colors leading-snug">{{ $p->name }}</a>
                                    <div class="mt-3 flex flex-wrap items-baseline gap-x-6 gap-y-1 text-sm">
                                        <span class="text-outline">Đơn giá <span class="text-on-surface-variant tabular-nums">{{ number_format($item->price, 0, ',', '.') }} đ</span></span>
                                        <span class="text-outline">SL <span class="text-on-background font-headline tabular-nums">{{ $item->quantity }}</span></span>
                                    </div>
                                </div>
                                <div class="sm:text-right flex sm:flex-col justify-center shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-outline-variant/15 sm:border-0">
                                    <span class="text-[10px] font-headline uppercase tracking-widest text-outline">Thành tiền</span>
                                    <span class="font-headline text-lg text-secondary-container tabular-nums mt-1">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>

        <aside class="lg:col-span-5 xl:col-span-4 lg:sticky lg:top-24 space-y-6">
            <div class="border border-outline-variant/20 bg-surface-container-high p-6 order-detail-chamfer relative overflow-hidden">
                <div class="absolute top-0 right-0 w-28 h-28 bg-secondary-container/8 blur-2xl rounded-full -mr-10 -mt-6 pointer-events-none" aria-hidden="true"></div>
                <h3 class="font-headline text-xs uppercase tracking-[0.2em] text-secondary-container mb-5 relative">Thanh toán</h3>
                <dl class="space-y-3 text-sm relative">
                    <div class="flex justify-between gap-4">
                        <dt class="text-outline">Tạm tính</dt>
                        <dd class="text-on-background font-headline tabular-nums">{{ number_format($order->items->sum(fn ($i) => $i->price * $i->quantity), 0, ',', '.') }} đ</dd>
                    </div>
                    <div class="flex justify-between gap-4 pt-3 border-t border-outline-variant/15">
                        <dt class="text-primary font-headline text-xs uppercase tracking-wider">Tổng đơn</dt>
                        <dd class="text-xl font-headline text-secondary-container tabular-nums drop-shadow-[0_0_8px_rgba(99,247,255,0.25)]">{{ number_format($order->total_price, 0, ',', '.') }} đ</dd>
                    </div>
                </dl>
            </div>

            <div class="border border-outline-variant/20 bg-surface-container-low p-6 order-detail-chamfer">
                <h3 class="font-headline text-xs uppercase tracking-[0.2em] text-primary mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary-container text-lg" aria-hidden="true">local_shipping</span>
                    Giao hàng
                </h3>
                <dl class="space-y-4 text-sm">
                    @if($order->recipient_name)
                        <div>
                            <dt class="font-headline text-[10px] uppercase tracking-widest text-outline mb-1">Người nhận</dt>
                            <dd class="text-on-background">{{ $order->recipient_name }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="font-headline text-[10px] uppercase tracking-widest text-outline mb-1">Điện thoại</dt>
                        <dd class="text-on-background font-mono">{{ $order->phone }}</dd>
                    </div>
                    <div>
                        <dt class="font-headline text-[10px] uppercase tracking-widest text-outline mb-1">Địa chỉ</dt>
                        <dd class="text-on-surface-variant leading-relaxed whitespace-pre-wrap">{{ $order->address }}</dd>
                    </div>
                    @if($order->city || $order->postal_code)
                        <div>
                            <dt class="font-headline text-[10px] uppercase tracking-widest text-outline mb-1">Khu vực</dt>
                            <dd class="text-on-background">
                                @if($order->city){{ $order->city }}@endif
                                @if($order->city && $order->postal_code)<span class="text-outline mx-1">·</span>@endif
                                @if($order->postal_code)<span class="text-secondary-container font-headline">{{ $order->postal_code }}</span>@endif
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <a href="{{ route('orders.index') }}" class="flex items-center justify-center gap-2 w-full py-3.5 border border-outline-variant/40 text-outline text-xs font-headline uppercase tracking-widest hover:border-secondary-container/50 hover:text-secondary-container transition-colors order-detail-chamfer">
                <span class="material-symbols-outlined text-lg">list_alt</span>
                Xem tất cả đơn
            </a>
        </aside>
    </div>
</div>
@endsection
